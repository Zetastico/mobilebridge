<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    /**
     * List all computers owned by the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->computers();

        if ($request->has('status')) {
            if ($request->status === 'online') {
                $query->online();
            } elseif ($request->status === 'offline') {
                $query->offline();
            }
        }

        $computers = $query->latest('last_seen_at')->get();

        return response()->json([
            'success' => true,
            'count' => $computers->count(),
            'computers' => $computers,
        ]);
    }

    /**
     * Store a new computer manually.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64', 'unique:computers,device_uuid'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'local_ip' => ['nullable', 'string', 'max:45'],
            'local_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'capabilities' => ['nullable', 'array'],
        ]);

        $computer = $request->user()->computers()->create([
            'device_uuid' => $validated['device_uuid'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'local_ip' => $validated['local_ip'] ?? null,
            'local_port' => $validated['local_port'] ?? 5050,
            'status' => 'offline',
            'capabilities' => $validated['capabilities'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Computador creado exitosamente',
            'computer' => $computer,
        ], 201);
    }

    /**
     * Display a specific computer owned by the authenticated user.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $computer = $request->user()->computers()->find($id);

        if (!$computer) {
            return response()->json([
                'success' => false,
                'message' => 'Computador no encontrado o no autorizado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'computer' => $computer,
        ]);
    }

    /**
     * Update an existing computer.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $computer = $request->user()->computers()->find($id);

        if (!$computer) {
            return response()->json([
                'success' => false,
                'message' => 'Computador no encontrado o no autorizado',
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'local_ip' => ['nullable', 'string', 'max:45'],
            'local_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'status' => ['nullable', 'string', 'in:online,offline'],
            'capabilities' => ['nullable', 'array'],
        ]);

        $computer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Computador actualizado exitosamente',
            'computer' => $computer,
        ]);
    }

    /**
     * Remove a computer from user devices.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $computer = $request->user()->computers()->find($id);

        if (!$computer) {
            return response()->json([
                'success' => false,
                'message' => 'Computador no encontrado o no autorizado',
            ], 404);
        }

        $computer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Computador eliminado correctamente',
        ]);
    }

    /**
     * Autonomous PC Agent Registration (Upsert by device_uuid).
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'local_ip' => ['nullable', 'string', 'max:45'],
            'local_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'capabilities' => ['nullable', 'array'],
        ]);

        $computer = Computer::updateOrCreate(
            [
                'device_uuid' => $validated['device_uuid'],
            ],
            [
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'local_ip' => $validated['local_ip'] ?? $request->ip(),
                'local_port' => $validated['local_port'] ?? 5050,
                'status' => 'online',
                'last_seen_at' => now(),
                'capabilities' => $validated['capabilities'] ?? [
                    'camera_preview' => true,
                    'camera_capture' => true,
                    'sensors' => true,
                    'file_transfer' => true,
                ],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'PC Agent registrado y activo',
            'computer' => $computer,
        ]);
    }

    /**
     * Heartbeat ping from PC Agent.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
            'local_ip' => ['nullable', 'string', 'max:45'],
            'local_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'capabilities' => ['nullable', 'array'],
        ]);

        $computer = $request->user()->computers()
            ->where('device_uuid', $validated['device_uuid'])
            ->first();

        if (!$computer) {
            return response()->json([
                'success' => false,
                'message' => 'Dispositivo no registrado para este usuario',
            ], 404);
        }

        $computer->recordHeartbeat(
            $validated['local_ip'] ?? null,
            $validated['local_port'] ?? null,
            $validated['capabilities'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat recibido',
            'last_seen_at' => $computer->last_seen_at,
            'is_online' => $computer->is_online,
        ]);
    }

    /**
     * Graceful offline notification from PC Agent.
     */
    public function offline(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
        ]);

        $computer = $request->user()->computers()
            ->where('device_uuid', $validated['device_uuid'])
            ->first();

        if (!$computer) {
            return response()->json([
                'success' => false,
                'message' => 'Dispositivo no encontrado',
            ], 404);
        }

        $computer->markOffline();

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo marcado como offline',
        ]);
    }
}
