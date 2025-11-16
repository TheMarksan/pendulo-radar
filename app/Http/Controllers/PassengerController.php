<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\TripProgress;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PassengerController extends Controller
{
    public function index()
    {
        return view('passenger.index');
    }

    public function register()
    {
        return view('passenger.register');
    }

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:4|max:8',
        ]);

        // Check if account already exists
        $existingAccount = Passenger::where('email', $validated['email'])
            ->where('password', $validated['password'])
            ->whereNull('scheduled_time')
            ->first();

        if ($existingAccount) {
            return back()->withErrors(['email' => 'Já existe uma conta com este email.'])
                ->withInput();
        }

        $passenger = Passenger::create($validated);

        // Auto login
        session(['passenger_id' => $passenger->id]);

        return redirect()->route('passenger.dashboard')
            ->with('success', 'Conta criada com sucesso!');
    }

    public function dashboard()
    {
        $passengerId = session('passenger_id');

        if (!$passengerId) {
            return redirect()->route('passenger.login')
                ->with('error', 'Faça login para continuar.');
        }

        $passenger = Passenger::find($passengerId);

        // Get only actual reservations (with scheduled_time)
        $reservations = Passenger::where('email', $passenger->email)
            ->whereNotNull('scheduled_time')
            ->orderBy('scheduled_time', 'desc')
            ->get();

        return view('passenger.dashboard', compact('passenger', 'reservations'));
    }

    public function create()
    {
        $passengerId = session('passenger_id');

        if (!$passengerId) {
            return redirect()->route('passenger.login')
                ->with('error', 'Faça login para continuar.');
        }

        $passenger = Passenger::find($passengerId);

        // Buscar rotas ativas
        $routes = \App\Models\Route::where('is_active', true)->get();

        return view('passenger.create', compact('passenger', 'routes'));
    }

    public function store(Request $request)
    {
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);

        $validated = $request->validate([
            'schedule_id' => 'required|exists:driver_schedules,id',
            'stop_id' => 'nullable|exists:stops,id',
            'scheduled_time_end' => 'required|date_format:H:i|after:scheduled_time_start',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'payment_method' => 'required|in:pix,dinheiro,vale',
        ]);

        $schedule = \App\Models\DriverSchedule::with('driver')->findOrFail($request->schedule_id);

        $validated['driver_id'] = $schedule->driver_id;
        $validated['scheduled_time'] = $schedule->date;
        $validated['scheduled_time_start'] = $schedule->departure_time;
        $validated['name'] = $passenger->name;
        $validated['email'] = $passenger->email;
        $validated['password'] = $passenger->password;

        $reservation = Passenger::create($validated);

        return redirect()->route('passenger.reservation.view', $reservation->id)
            ->with('success', 'Reserva realizada com sucesso!');
    }

    public function success($id)
    {
        return $this->viewReservation($id);
    }

    public function viewReservation($id)
    {
    $reservation = Passenger::with(['driver', 'driver.route.outboundStops', 'driver.route.returnStops', 'stop'])->findOrFail($id);
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);

        // Check if user is authorized
        if (!$passenger || $passenger->email !== $reservation->email) {
            return redirect()->route('passenger.login')
                ->with('error', 'Faça login para acessar esta reserva.');
        }

        // Verificar se esta é a última reserva do passageiro
        $lastReservation = Passenger::where('email', $passenger->email)
            ->whereNotNull('scheduled_time')
            ->orderBy('created_at', 'desc')
            ->first();

        $isLastReservation = ($lastReservation && $lastReservation->id === $reservation->id);

        // Buscar todos os embarques confirmados do mesmo dia
        $allBoardings = Passenger::whereNotNull('boarded_at')
            ->whereDate('scheduled_time', $reservation->scheduled_time->format('Y-m-d'))
            ->orderBy('boarded_at', 'asc')
            ->get(['id', 'name', 'boarded_at', 'boarded_latitude', 'boarded_longitude', 'address', 'latitude', 'longitude']);

        // Último embarque (posição atual do carro)
        $lastBoarding = Passenger::whereNotNull('boarded_at')
            ->whereDate('scheduled_time', $reservation->scheduled_time->format('Y-m-d'))
            ->orderBy('boarded_at', 'desc')
            ->first(['name', 'boarded_at', 'boarded_latitude', 'boarded_longitude']);

        // Buscar progresso das paradas
        $tripProgress = [];
        $currentDirection = 'outbound';

        if ($reservation->driver_id) {
            // Verificar se o motorista iniciou o retorno
            $returnStarted = session('return_started_driver_' . $reservation->driver_id);
            if ($returnStarted &&
                $returnStarted['date'] == $reservation->scheduled_time->format('Y-m-d') &&
                $returnStarted['time'] == $reservation->scheduled_time_start) {
                $currentDirection = 'return';
            }

            // Buscar progressos confirmados via TripProgress (apenas pelo driver_id)
            $confirmedStops = TripProgress::where('driver_id', $reservation->driver_id)
                ->whereNotNull('confirmed_at')
                ->pluck('stop_id')
                ->toArray();

            // Buscar paradas onde passageiros embarcaram (stop_id) apenas pelo driver_id
            $boardedStops = Passenger::where('driver_id', $reservation->driver_id)
                ->whereNotNull('boarded_at')
                ->whereNotNull('stop_id')
                ->pluck('stop_id')
                ->unique()
                ->toArray();

            // Combinar ambas as fontes de progresso
            $tripProgress = array_unique(array_merge($confirmedStops, $boardedStops));
        }

        // Obter paradas da rota
        $route = $reservation->driver->route ?? null;
        $outboundStops = $route ? $route->outboundStops : collect([]);
        $returnStops = $route ? $route->returnStops : collect([]);

        return view('passenger.view-reservation', compact(
            'reservation',
            'passenger',
            'allBoardings',
            'lastBoarding',
            'isLastReservation',
            'tripProgress',
            'currentDirection',
            'outboundStops',
            'returnStops'
        ));
    }

    public function editReservation($id)
    {
        $reservation = Passenger::findOrFail($id);
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);

        if (!$passenger || $passenger->email !== $reservation->email) {
            return redirect()->route('passenger.login')
                ->with('error', 'Faça login para acessar esta reserva.');
        }

        return view('passenger.edit-reservation', compact('reservation', 'passenger'));
    }

    public function updateReservation(Request $request, $id)
    {
        $reservation = Passenger::findOrFail($id);
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);

        if (!$passenger || $passenger->email !== $reservation->email) {
            abort(403);
        }

        $validated = $request->validate([
            'scheduled_time' => 'required|date|after_or_equal:today|before_or_equal:' . date('Y-m-d', strtotime('+7 days')),
            'scheduled_time_start' => 'required|date_format:H:i',
            'scheduled_time_end' => 'required|date_format:H:i|after:scheduled_time_start',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'payment_method' => 'required|in:pix,dinheiro,vale',
        ]);

        $reservation->update($validated);

        return redirect()->route('passenger.reservation.view', $reservation->id)
            ->with('success', 'Reserva atualizada com sucesso!');
    }

    public function deleteReservation($id)
    {
        $reservation = Passenger::findOrFail($id);
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);

        if (!$passenger || $passenger->email !== $reservation->email) {
            abort(403);
        }

        if ($reservation->receipt_path) {
            Storage::disk('public')->delete($reservation->receipt_path);
        }

        $reservation->delete();

        return redirect()->route('passenger.dashboard')
            ->with('success', 'Reserva excluída com sucesso!');
    }

    public function login()
    {
        return view('passenger.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4|max:8',
        ]);

        $passenger = Passenger::where('email', $request->email)
            ->where('password', $request->password)
            ->first();

        if (!$passenger) {
            return back()->withErrors(['email' => 'Email ou senha incorretos.'])
                ->withInput($request->only('email'));
        }

        // Store passenger ID in session
        session(['passenger_id' => $passenger->id]);

        return redirect()->route('passenger.dashboard')
            ->with('success', 'Login realizado com sucesso!');
    }

    public function logout()
    {
        session()->forget('passenger_id');
        return redirect()->route('home')
            ->with('success', 'Logout realizado com sucesso!');
    }

    public function uploadReceipt(Request $request, $id)
    {
        $passenger = Passenger::findOrFail($id);

        $request->validate([
            // max:10240 = 10MB (size in kilobytes)
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($passenger->receipt_path) {
                Storage::disk('public')->delete($passenger->receipt_path);
            }

            $path = $request->file('receipt')->store('receipts', 'public');
            $passenger->update(['receipt_path' => $path]);
        }

        return back()->with('success', 'Comprovante anexado com sucesso!');
    }

    public function confirmBoarding(Request $request, $id)
    {
        try {
            $reservation = Passenger::findOrFail($id);
            $passengerId = session('passenger_id');
            $passenger = Passenger::find($passengerId);

            if (!$passenger || $passenger->email !== $reservation->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autorizado'
                ], 403);
            }

            // Verificar se esta é a última reserva do passageiro
            $lastReservation = Passenger::where('email', $passenger->email)
                ->whereNotNull('scheduled_time')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$lastReservation || $lastReservation->id !== $reservation->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas a sua reserva mais recente pode ter o embarque confirmado.'
                ], 403);
            }

            // Usar o endereço cadastrado na reserva
            $reservation->update([
                'boarded' => true,
                'boarded_at' => now(),
                'boarded_latitude' => $reservation->latitude,
                'boarded_longitude' => $reservation->longitude,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Embarque confirmado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
