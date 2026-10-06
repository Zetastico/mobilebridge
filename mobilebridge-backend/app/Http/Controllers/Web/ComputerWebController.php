<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ComputerWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->computers();

        if ($request->filled('status')) {
            if ($request->status === 'online') {
                $query->online();
            } elseif ($request->status === 'offline') {
                $query->offline();
            }
        }

        $computers = $query->latest('last_seen_at')->paginate(10);

        return view('computers.index', compact('computers'));
    }

    public function create(): View
    {
        return view('computers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['nullable', 'string', 'max:64', 'unique:computers,device_uuid'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'local_ip' => ['nullable', 'string', 'max:45'],
            'local_port' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        $request->user()->computers()->create([
            'device_uuid' => !empty($validated['device_uuid']) ? $validated['device_uuid'] : (string) Str::uuid(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'local_ip' => $validated['local_ip'] ?? '127.0.0.1',
            'local_port' => $validated['local_port'],
            'status' => 'offline',
            'capabilities' => [
                'camera_preview' => true,
                'camera_capture' => true,
                'sensors' => true,
                'file_transfer' => true,
            ],
        ]);

        return redirect()->route('computers.index')->with('success', 'Computador creado exitosamente.');
    }

    public function edit(Request $request, Computer $computer): View
    {
        if ($computer->user_id !== $request->user()->id) {
            abort(403);
        }
        return view('computers.edit', compact('computer'));
    }

    public function update(Request $request, Computer $computer): RedirectResponse
    {
        if ($computer->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'local_ip' => ['nullable', 'string', 'max:45'],
            'local_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'status' => ['required', 'string', 'in:online,offline'],
        ]);

        $computer->update($validated);

        return redirect()->route('computers.index')->with('success', 'Computador actualizado exitosamente.');
    }

    public function destroy(Request $request, Computer $computer): RedirectResponse
    {
        if ($computer->user_id !== $request->user()->id) {
            abort(403);
        }

        $computer->delete();

        return redirect()->route('computers.index')->with('success', 'Computador eliminado.');
    }
}
