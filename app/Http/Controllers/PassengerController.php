<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
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

        return view('passenger.create', compact('passenger'));
    }

    public function store(Request $request)
    {
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);

        $validated = $request->validate([
            'scheduled_time' => 'required|date|after_or_equal:today|before_or_equal:' . date('Y-m-d', strtotime('+7 days')),
            'scheduled_time_start' => 'required|date_format:H:i',
            'scheduled_time_end' => 'required|date_format:H:i|after:scheduled_time_start',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'payment_method' => 'required|in:pix,dinheiro,vale',
        ]);

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
        $reservation = Passenger::findOrFail($id);
        $passengerId = session('passenger_id');
        $passenger = Passenger::find($passengerId);
        
        // Check if user is authorized
        if (!$passenger || $passenger->email !== $reservation->email) {
            return redirect()->route('passenger.login')
                ->with('error', 'Faça login para acessar esta reserva.');
        }

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
        
        return view('passenger.view-reservation', compact('reservation', 'passenger', 'allBoardings', 'lastBoarding'));
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
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

            // Get user's current location
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $reservation->update([
                'boarded' => true,
                'boarded_at' => now(),
                'boarded_latitude' => $validated['latitude'],
                'boarded_longitude' => $validated['longitude'],
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
