<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * List all phones owned by the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->phones();

        if ($request->has('status')) {
            if ($request->status === 'online') {
                $query->online();
            } elseif ($request->status === 'offline') {
                $query->offline();
            }
        }

        $phones = $query->latest('last_seen_at')->get();

        return response()->json([
            'success' => true,
            'count' => $phones->count(),
            'phones' => $phones,
        ]);
    }

    /**
     * Store a new phone manually.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64', 'unique:phones,device_uuid'],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:50'],
        ]);

        $phone = $request->user()->phones()->create([
            'device_uuid' => $validated['device_uuid'],
            'name' => $validated['name'],
            'model' => $validated['model'] ?? null,
            'android_version' => $validated['android_version'] ?? null,
            'status' => 'offline',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teléfono registrado exitosamente',
            'phone' => $phone,
        ], 201);
    }

    /**
     * Display a specific phone.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $phone = $request->user()->phones()->find($id);

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Teléfono no encontrado o no autorizado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'phone' => $phone,
        ]);
    }

    /**
     * Update an existing phone.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $phone = $request->user()->phones()->find($id);

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Teléfono no encontrado o no autorizado',
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'in:online,offline'],
        ]);

        $phone->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Teléfono actualizado exitosamente',
            'phone' => $phone,
        ]);
    }

    /**
     * Delete a phone.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $phone = $request->user()->phones()->find($id);

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Teléfono no encontrado o no autorizado',
            ], 404);
        }

        $phone->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teléfono eliminado exitosamente',
        ]);
    }

    /**
     * Autonomous Phone Registration (Upsert by device_uuid).
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:50'],
        ]);

        $phone = Phone::updateOrCreate(
            [
                'device_uuid' => $validated['device_uuid'],
            ],
            [
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'model' => $validated['model'] ?? null,
                'android_version' => $validated['android_version'] ?? null,
                'status' => 'online',
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Teléfono registrado y activo',
            'phone' => $phone,
        ]);
    }

    /**
     * Heartbeat ping from Phone.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
            'name' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:50'],
        ]);

        $phone = $request->user()->phones()
            ->where('device_uuid', $validated['device_uuid'])
            ->first();

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Teléfono no registrado para este usuario',
            ], 404);
        }

        $phone->recordHeartbeat(
            $validated['name'] ?? null,
            $validated['model'] ?? null,
            $validated['android_version'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat recibido',
            'last_seen_at' => $phone->last_seen_at,
            'is_online' => $phone->is_online,
        ]);
    }

    /**
     * Graceful offline notification from Phone.
     */
    public function offline(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['required', 'string', 'max:64'],
        ]);

        $phone = $request->user()->phones()
            ->where('device_uuid', $validated['device_uuid'])
            ->first();

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Teléfono no encontrado',
            ], 404);
        }

        $phone->markOffline();

        return response()->json([
            'success' => true,
            'message' => 'Teléfono marcado como offline',
        ]);
    }
}
