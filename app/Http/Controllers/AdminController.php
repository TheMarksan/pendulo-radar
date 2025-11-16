<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Passenger;
use App\Models\Driver;
use App\Models\AccessKey;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ...existing code...
    // Painel geral de viagens dos motoristas
    public function allDriversDashboard(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }
        $drivers = \App\Models\Driver::orderBy('name')->get();
        $selectedDriver = null;
        $passengers = collect();
        $route = null;
        $outboundStops = collect();
        $returnStops = collect();
        $tripProgress = [];
        if ($request->driver_id) {
            $selectedDriver = \App\Models\Driver::find($request->driver_id);
            if ($selectedDriver) {
                $route = $selectedDriver->route;
                $outboundStops = $route ? $route->outboundStops()->get() : collect();
                $returnStops = $route ? $route->returnStops()->get() : collect();
                $passengers = \App\Models\Passenger::with('stop')
                    ->where('driver_id', $selectedDriver->id)
                    ->orderBy('scheduled_time')
                    ->orderBy('scheduled_time_start')
                    ->get();
                $tripProgress = \App\Models\TripProgress::where('driver_id', $selectedDriver->id)
                    ->whereNotNull('confirmed_at')
                    ->pluck('stop_id')
                    ->toArray();
            }
        }
        return view('admin.all-drivers-dashboard', compact('drivers', 'selectedDriver', 'passengers', 'route', 'outboundStops', 'returnStops', 'tripProgress'));
    }
    // Login
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['email' => 'Email ou senha incorretos.'])
                ->withInput($request->only('email'));
        }

        // Check if it's first access
        if ($admin->first_access) {
            session(['admin_first_access_id' => $admin->id]);
            return redirect()->route('admin.first.access');
        }

        session(['admin_id' => $admin->id]);
        return redirect()->route('admin.dashboard')
            ->with('success', 'Login realizado com sucesso!');
    }

    public function firstAccess()
    {
        $adminId = session('admin_first_access_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::findOrFail($adminId);
        return view('admin.first-access', compact('admin'));
    }

    public function updateFirstAccess(Request $request)
    {
        $adminId = session('admin_first_access_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin = Admin::findOrFail($adminId);
        $admin->update([
            'password' => Hash::make($request->password),
            'first_access' => false,
        ]);

        session()->forget('admin_first_access_id');
        session(['admin_id' => $admin->id]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Senha atualizada com sucesso!');
    }

    public function logout()
    {
        session()->forget('admin_id');
        session()->forget('admin_first_access_id');
        return redirect()->route('home')
            ->with('success', 'Logout realizado com sucesso!');
    }

    // Dashboard
    public function dashboard()
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::findOrFail($adminId);

        $stats = [
            'passengers' => Passenger::whereNotNull('scheduled_time')->count(),
            'drivers' => Driver::count(),
            'routes' => Route::count(),
            'active_keys' => AccessKey::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('admin', 'stats'));
    }

    // User Management
    public function users()
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $passengers = Passenger::whereNotNull('email')
            ->whereNotNull('password')
            ->whereNull('scheduled_time')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('passengers'));
    }

    public function resetUserAccess($id)
    {
        $passenger = Passenger::findOrFail($id);
        $passenger->update([
            'password' => Hash::make($passenger->email), // Senha = email
        ]);

        return back()->with('success', 'Acesso resetado! Nova senha: ' . $passenger->email);
    }

    // Access Keys Management
    public function accessKeys()
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $keys = AccessKey::orderBy('created_at', 'desc')->get();
        return view('admin.access-keys', compact('keys'));
    }

    public function storeAccessKey(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);

        $key = AccessKey::create([
            'key' => strtoupper(Str::random(10)),
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Chave criada: ' . $key->key);
    }

    public function toggleAccessKey($id)
    {
        $key = AccessKey::findOrFail($id);
        $key->update(['is_active' => !$key->is_active]);

        return back()->with('success', 'Status da chave atualizado!');
    }

    public function deleteAccessKey($id)
    {
        AccessKey::findOrFail($id)->delete();
        return back()->with('success', 'Chave excluída com sucesso!');
    }

    // Routes Management
    public function routes()
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $routes = Route::with('stops')->orderBy('created_at', 'desc')->get();
        return view('admin.routes', compact('routes'));
    }

    public function storeRoute(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_return' => 'nullable|boolean',
        ]);

        Route::create([
            'name' => $request->name,
            'description' => $request->description,
            'has_return' => $request->has('has_return'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Rota criada com sucesso!');
    }

    public function toggleRoute($id)
    {
        $route = Route::findOrFail($id);
        $route->update(['is_active' => !$route->is_active]);

        return back()->with('success', 'Status da rota atualizado!');
    }

    public function deleteRoute($id)
    {
        Route::findOrFail($id)->delete();
        return back()->with('success', 'Rota excluída com sucesso!');
    }

    // Stops Management
    public function stops($routeId)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $route = Route::with('stops')->findOrFail($routeId);
        return view('admin.stops', compact('route'));
    }

    public function storeStop(Request $request, $routeId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:outbound,return',
        ]);

        $maxOrder = Stop::where('route_id', $routeId)->max('order') ?? 0;

        Stop::create([
            'route_id' => $routeId,
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'type' => $request->type,
            'order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Parada adicionada com sucesso!');
    }

    public function updateStopOrder(Request $request, $routeId)
    {
        $request->validate([
            'stops' => 'required|array',
            'stops.*.id' => 'required|exists:stops,id',
            'stops.*.order' => 'required|integer',
        ]);

        foreach ($request->stops as $stopData) {
            Stop::where('id', $stopData['id'])->update(['order' => $stopData['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada!']);
    }

    public function toggleStop($routeId, $id)
    {
        $stop = Stop::findOrFail($id);
        $stop->update(['is_active' => !$stop->is_active]);

        return back()->with('success', 'Status da parada atualizado!');
    }

    public function deleteStop($routeId, $id)
    {
        Stop::findOrFail($id)->delete();
        return back()->with('success', 'Parada excluída com sucesso!');
    }

    // Gerenciamento de Motoristas e Carros
    public function drivers()
    {
        $drivers = \App\Models\Driver::with('route')->get();
        return view('admin.drivers', compact('drivers'));
    }

    public function cars($driverId)
    {
        $driver = \App\Models\Driver::with(['cars.route', 'route'])->findOrFail($driverId);
        $routes = Route::where('is_active', true)->get();
        return view('admin.cars', compact('driver', 'routes'));
    }

    public function storeCar(Request $request, $driverId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date_format:H:i',
            'return_time' => 'required|date_format:H:i|after:departure_time',
        ]);

        \App\Models\Car::create([
            'driver_id' => $driverId,
            'route_id' => $validated['route_id'],
            'name' => $validated['name'],
            'departure_time' => $validated['departure_time'],
            'return_time' => $validated['return_time'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Carro criado com sucesso!');
    }

    public function toggleCar($driverId, $id)
    {
        $car = \App\Models\Car::findOrFail($id);
        $car->is_active = !$car->is_active;
        $car->save();
        return back()->with('success', 'Status do carro atualizado!');
    }

    public function deleteCar($driverId, $id)
    {
        \App\Models\Car::findOrFail($id)->delete();
        return back()->with('success', 'Carro excluído com sucesso!');
    }

    public function deleteDriver($id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        $driver->cars()->delete(); // Deleta todos os carros do motorista
        $driver->delete();
        return back()->with('success', 'Motorista excluído com sucesso!');
    }

    // Editar horários do motorista
    public function editDriverSchedule($driverId)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }
        $driver = \App\Models\Driver::findOrFail($driverId);
        return view('admin.edit-driver-schedule', compact('driver'));
    }

    public function updateDriverSchedule(Request $request, $driverId)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }
        $driver = \App\Models\Driver::findOrFail($driverId);

        $action = $request->input('action');
        if ($action === 'add') {
            $request->validate([
                'date' => 'required|date',
                'departure_time' => 'required',
                // 'return_time' => 'nullable',
            ]);
            $driver->schedules()->create([
                'route_id' => $driver->route_id,
                'date' => $request->date,
                'departure_time' => $request->departure_time,
                'return_time' => $request->return_time,
                'is_active' => true,
            ]);
            return back()->with('success', 'Horário adicionado com sucesso!');
        } elseif ($action === 'edit') {
            $request->validate([
                'schedule_id' => 'required|exists:driver_schedules,id',
                'date' => 'required|date',
                'departure_time' => 'required',
                // 'return_time' => 'nullable',
            ]);
            $schedule = $driver->schedules()->findOrFail($request->schedule_id);
            $schedule->date = $request->date;
            $schedule->departure_time = $request->departure_time;
            $schedule->return_time = $request->return_time;
            $schedule->is_active = $request->has('is_active');
            $schedule->save();
            return back()->with('success', 'Horário atualizado com sucesso!');
        } elseif ($action === 'delete') {
            $request->validate([
                'schedule_id' => 'required|exists:driver_schedules,id',
            ]);
            $schedule = $driver->schedules()->findOrFail($request->schedule_id);
            $schedule->delete();
            return back()->with('success', 'Horário excluído com sucesso!');
        }
        return back()->with('error', 'Ação inválida.');
    }
}

