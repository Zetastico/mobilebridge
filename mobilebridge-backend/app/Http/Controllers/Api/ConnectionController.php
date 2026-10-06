<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Computer;
use App\Models\Connection;
use App\Models\Phone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    /**
     * List all connections involving devices belonging to the user.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $connections = Connection::with(['phone', 'computer'])
            ->whereHas('computer', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest('started_at')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $connections->count(),
            'connections' => $connections,
        ]);
    }

    /**
     * Log a new connection session.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone_id' => ['required', 'exists:phones,id'],
            'computer_id' => ['required', 'exists:computers,id'],
            'status' => ['nullable', 'string', 'in:active,closed,failed'],
            'started_at' => ['nullable', 'date'],
        ]);

        // Ensure user owns both devices
        $user = $request->user();
        $phone = $user->phones()->find($validated['phone_id']);
        $computer = $user->computers()->find($validated['computer_id']);

        if (!$phone || !$computer) {
            return response()->json([
                'success' => false,
                'message' => 'Dispositivo no autorizado para este usuario',
            ], 403);
        }

        // Close any prior 'active' connection between these exact devices to prevent duplicates
        Connection::where('phone_id', $phone->id)
            ->where('computer_id', $computer->id)
            ->where('status', 'active')
            ->update([
                'status' => 'closed',
                'ended_at' => now(),
            ]);

        $connection = Connection::create([
            'phone_id' => $phone->id,
            'computer_id' => $computer->id,
            'status' => $validated['status'] ?? 'active',
            'started_at' => $validated['started_at'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Conexión registrada exitosamente',
            'connection' => $connection->load(['phone', 'computer']),
        ], 201);
    }

    /**
     * Show connection details.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $connection = Connection::with(['phone', 'computer'])
            ->whereHas('computer', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->find($id);

        if (!$connection) {
            return response()->json([
                'success' => false,
                'message' => 'Registro de conexión no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'connection' => $connection,
        ]);
    }

    /**
     * Update connection status (e.g. end a session).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $connection = Connection::whereHas('computer', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->find($id);

        if (!$connection) {
            return response()->json([
                'success' => false,
                'message' => 'Registro de conexión no encontrado',
            ], 404);
        }

        $validated = $request->validate([
            'status' => ['sometimes', 'required', 'string', 'in:active,closed,failed'],
            'ended_at' => ['nullable', 'date'],
        ]);

        if (isset($validated['status']) && $validated['status'] !== 'active' && empty($validated['ended_at'])) {
            $validated['ended_at'] = now();
        }

        $connection->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Conexión actualizada exitosamente',
            'connection' => $connection->load(['phone', 'computer']),
        ]);
    }

    /**
     * Delete a connection history record.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;

        $connection = Connection::whereHas('computer', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->find($id);

        if (!$connection) {
            return response()->json([
                'success' => false,
                'message' => 'Registro de conexión no encontrado',
            ], 404);
        }

        $connection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro de conexión eliminado',
        ]);
    }
}
