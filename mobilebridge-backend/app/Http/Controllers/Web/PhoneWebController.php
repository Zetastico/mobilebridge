<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Phone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PhoneWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->user()->phones();

        if ($request->filled('status')) {
            if ($request->status === 'online') {
                $query->online();
            } elseif ($request->status === 'offline') {
                $query->offline();
            }
        }

        $phones = $query->latest('last_seen_at')->paginate(10);

        return view('phones.index', compact('phones'));
    }

    public function create(): View
    {
        return view('phones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'device_uuid' => ['nullable', 'string', 'max:64', 'unique:phones,device_uuid'],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:50'],
        ]);

        $request->user()->phones()->create([
            'device_uuid' => !empty($validated['device_uuid']) ? $validated['device_uuid'] : (string) Str::uuid(),
            'name' => $validated['name'],
            'model' => $validated['model'] ?? 'Android Device',
            'android_version' => $validated['android_version'] ?? 'Android 14',
            'status' => 'offline',
        ]);

        return redirect()->route('phones.index')->with('success', 'Teléfono registrado exitosamente.');
    }

    public function edit(Request $request, Phone $phone): View
    {
        if ($phone->user_id !== $request->user()->id) {
            abort(403);
        }
        return view('phones.edit', compact('phone'));
    }

    public function update(Request $request, Phone $phone): RedirectResponse
    {
        if ($phone->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'android_version' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:online,offline'],
        ]);

        $phone->update($validated);

        return redirect()->route('phones.index')->with('success', 'Teléfono actualizado exitosamente.');
    }

    public function destroy(Request $request, Phone $phone): RedirectResponse
    {
        if ($phone->user_id !== $request->user()->id) {
            abort(403);
        }

        $phone->delete();

        return redirect()->route('phones.index')->with('success', 'Teléfono eliminado.');
    }
}
