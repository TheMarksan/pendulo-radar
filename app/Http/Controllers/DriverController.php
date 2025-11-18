<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Car;
use App\Models\Passenger;
use App\Models\TripProgress;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class DriverController extends Controller
{
    // Exibe formulário de edição de perfil do motorista
    public function editProfile(Request $request)
    {
        $driverId = session('driver_id');
        if (!$driverId) {
            return redirect()->route('driver.login');
        }
        $driver = Driver::findOrFail($driverId);
        return view('driver.edit_profile', compact('driver'));
    }

    // Atualiza perfil do motorista
    public function updateProfile(Request $request)
    {
        $driverId = session('driver_id');
        if (!$driverId) {
            return redirect()->route('driver.login');
        }
        $driver = Driver::findOrFail($driverId);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone' => 'required|string',
            'pix_key' => 'nullable|string',
        ]);
        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->phone = $request->phone;
        $driver->pix_key = $request->pix_key;
        $driver->save();
        return redirect()->route('driver.dashboard')->with('success', 'Perfil atualizado com sucesso!');
    }
    // Exibe tela de primeiro acesso para troca de senha
    public function firstAccess()
    {
        $driverId = session('driver_first_access_id');
        if (!$driverId) {
            return redirect()->route('driver.login');
        }
        $driver = Driver::findOrFail($driverId);
        return view('driver.first_access', compact('driver'));
    }

    // Processa troca de senha no primeiro acesso
    public function updateFirstAccess(Request $request)
    {
        $driverId = session('driver_first_access_id');
        if (!$driverId) {
            return redirect()->route('driver.login');
        }
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
        ]);
        $driver = Driver::findOrFail($driverId);
        $driver->password = Hash::make($request->password);
        $driver->first_access = false;
        $driver->save();
        session()->forget('driver_first_access_id');
        session(['driver_id' => $driver->id]);
        return redirect()->route('driver.dashboard')->with('success', 'Senha atualizada com sucesso!');
    }
    public function registerForm()
    {
        return view('driver.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email',
            'password' => 'required|string|min:4|max:8|confirmed',
            'phone' => 'required|string',
            'pix_key' => 'nullable|string',
            'access_key' => 'required|string',
            'route_id' => 'required|exists:routes,id',
        ]);

        // Verificar chave de acesso
        $accessKey = \App\Models\AccessKey::where('key', $validated['access_key'])
            ->where('is_active', true)
            ->first();

        if (!$accessKey) {
            return back()->withErrors(['access_key' => 'Chave de acesso inválida ou inativa.'])->withInput();
        }

        // Incrementar uso da chave
        $accessKey->increment('usage_count');

        // Criar motorista
        $driver = Driver::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'pix_key' => $validated['pix_key'],
            'access_key' => $validated['access_key'],
            'route_id' => $validated['route_id'],
            // Não exige troca de senha após cadastro
            'first_access' => false,
        ]);

        // Auto login
        session(['driver_id' => $driver->id]);

        return redirect()->route('driver.dashboard')
            ->with('success', 'Cadastro realizado com sucesso! Aguarde o administrador criar seu carro e configurar os horários.');
    }

    public function login()
    {
        return view('driver.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $driver = Driver::where('email', $request->email)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return back()->withErrors(['email' => 'Email ou senha incorretos.'])
                ->withInput($request->only('email'));
        }

        // Só força troca de senha se o admin resetou o acesso (first_access = true)
        if (!empty($driver->first_access)) {
            session(['driver_first_access_id' => $driver->id]);
            return redirect()->route('driver.first.access');
        }

        session(['driver_id' => $driver->id]);
        return redirect()->route('driver.dashboard')
            ->with('success', 'Login realizado com sucesso!');
    }

    public function logout()
    {
        session()->forget('driver_id');
        return redirect()->route('driver.login')
            ->with('success', 'Logout realizado com sucesso!');
    }

    public function dashboard(Request $request)
    {
        $driverId = session('driver_id');
        if (!$driverId) {
            return redirect()->route('driver.login')
                ->with('error', 'Faça login para continuar.');
        }

        $driver = Driver::findOrFail($driverId);


        // Verificar se o motorista possui pelo menos um horário cadastrado
        $hasSchedule = $driver->schedules()->exists();
        if (!$hasSchedule) {
            // Exibir view de aguardando admin
            return view('driver.index', [
                'passengers' => collect([]),
                'lastBoarding' => null,
                'driver' => $driver,
                'car' => null,
                'noCar' => true,
                'route' => $driver->route,
                'outboundStops' => collect([]),
                'returnStops' => collect([]),
                'tripProgress' => []
            ]);
        }

        // Buscar reservas do motorista
        $car = null;

        // Buscar reservas do motorista
        $query = Passenger::with('stop')->where('driver_id', $driver->id)->whereNotNull('scheduled_time');

        // Filter by date if provided
        if ($request->has('date')) {
            $query->whereDate('scheduled_time', $request->date);
        }

        // Filter by time range if provided
        if ($request->has('time_start')) {
            $query->where('scheduled_time_start', '>=', $request->time_start);
        }

        if ($request->has('time_end')) {
            $query->where('scheduled_time_end', '<=', $request->time_end);
        }

        $passengers = $query->orderBy('scheduled_time')->orderBy('scheduled_time_start')->get();

        // Último embarque do motorista
        $lastBoarding = Passenger::with('stop')->where('driver_id', $driver->id)
            ->where('boarded', true)
            ->whereNotNull('boarded_at')
            ->orderBy('boarded_at', 'desc')
            ->first();

        // Buscar rota e paradas
        $route = $driver->route;
        $outboundStops = $route ? $route->outboundStops()->get() : collect([]);
        $returnStops = $route ? $route->returnStops()->get() : collect([]);

        // Buscar progresso das paradas (todas as paradas confirmadas para este motorista)
        $tripProgress = TripProgress::where('driver_id', $driver->id)
            ->whereNotNull('confirmed_at')
            ->pluck('stop_id')
            ->toArray();

        return view('driver.index', [
            'passengers' => $passengers,
            'lastBoarding' => $lastBoarding,
            'driver' => $driver,
            'car' => $car,
            'noCar' => false,
            'route' => $route,
            'outboundStops' => $outboundStops,
            'returnStops' => $returnStops,
            'tripProgress' => $tripProgress
        ]);
    }

    public function viewReceipt($id)
    {
        $passenger = Passenger::findOrFail($id);

        if (!$passenger->receipt_path) {
            abort(404, 'Comprovante não encontrado');
        }

        return response()->file(storage_path('app/public/' . $passenger->receipt_path));
    }

    public function startReturn(Request $request)
    {
        $driverId = session('driver_id');

        if (!$driverId) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        $driver = Driver::findOrFail($driverId);

        $validated = $request->validate([
            'trip_date' => 'required|date',
            'time_start' => 'required',
        ]);

        // Marcar que iniciou o retorno (salvar no session ou criar registro)
        session(['return_started_driver_' . $driver->id => [
            'date' => $validated['trip_date'],
            'time' => $validated['time_start'],
            'started_at' => now(),
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'Retorno iniciado! Agora as paradas de retorno serão exibidas para os passageiros.',
        ]);
    }

    public function confirmStop(Request $request)
    {
        $driverId = session('driver_id');

        if (!$driverId) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        $driver = Driver::findOrFail($driverId);

        $validated = $request->validate([
            'stop_id' => 'required|exists:stops,id',
            'trip_date' => 'required|date',
            'time_start' => 'required',
            'direction' => 'required|in:outbound,return',
        ]);

        // Criar ou atualizar progresso
        $progress = TripProgress::updateOrCreate(
            [
                'driver_id' => $driver->id,
                'stop_id' => $validated['stop_id'],
                'trip_date' => $validated['trip_date'],
                'time_start' => $validated['time_start'],
                'direction' => $validated['direction'],
            ],
            [
                'confirmed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Parada confirmada!',
            'progress' => $progress,
        ]);
    }
}
