<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        // Only get actual reservations (with scheduled_time)
        $query = Passenger::whereNotNull('scheduled_time');

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

        // Get last boarding location
        $lastBoarding = Passenger::where('boarded', true)
            ->whereNotNull('boarded_at')
            ->orderBy('boarded_at', 'desc')
            ->first();

        return view('driver.index', compact('passengers', 'lastBoarding'));
    }

    public function viewReceipt($id)
    {
        $passenger = Passenger::findOrFail($id);
        
        if (!$passenger->receipt_path) {
            abort(404, 'Comprovante não encontrado');
        }

        return response()->file(storage_path('app/public/' . $passenger->receipt_path));
    }
}
