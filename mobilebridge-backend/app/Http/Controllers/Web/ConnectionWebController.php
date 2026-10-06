<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConnectionWebController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $connections = Connection::with(['phone', 'computer'])
            ->whereHas('computer', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest('started_at')
            ->paginate(15);

        return view('connections.index', compact('connections'));
    }
}
