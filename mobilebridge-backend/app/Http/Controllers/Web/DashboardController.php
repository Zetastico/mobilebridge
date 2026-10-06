<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use App\Models\Connection;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Statistics
        $totalUsers = User::count();
        $totalComputers = $user->computers()->count();
        $totalPhones = $user->phones()->count();
        
        $onlineComputers = $user->computers()->online()->count();
        $offlineComputers = $user->computers()->offline()->count();
        
        $onlinePhones = $user->phones()->online()->count();
        $offlinePhones = $user->phones()->offline()->count();

        $activeConnections = Connection::whereHas('computer', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'active')->count();

        $recentComputers = $user->computers()->latest('last_seen_at')->take(5)->get();
        $recentPhones = $user->phones()->latest('last_seen_at')->take(5)->get();
        $recentConnections = Connection::with(['phone', 'computer'])
            ->whereHas('computer', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest('started_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'totalComputers',
            'totalPhones',
            'onlineComputers',
            'offlineComputers',
            'onlinePhones',
            'offlinePhones',
            'activeConnections',
            'recentComputers',
            'recentPhones',
            'recentConnections'
        ));
    }
}
