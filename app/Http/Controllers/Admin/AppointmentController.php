<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('status', 'date', 'search', 'when');
        $today = now(config('khalil.timezone'))->toDateString();

        $appointments = Appointment::query()
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($filters['date'] ?? null, fn ($q, $d) => $q->whereDate('preferred_date', $d))
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$s}%")
                ->orWhere('phone', 'like', "%{$s}%")
                ->orWhere('reference', 'like', "%{$s}%")))
            ->when(($filters['when'] ?? 'upcoming') === 'upcoming',
                fn ($q) => $q->whereDate('preferred_date', '>=', $today)->orderBy('preferred_date')->orderBy('preferred_time'),
                fn ($q) => $q->orderByDesc('preferred_date')->orderByDesc('preferred_time'))
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Appointments', [
            'appointments' => $appointments,
            'filters' => $filters + ['when' => 'upcoming'],
            'statuses' => Appointment::STATUSES,
        ]);
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $appointment->update($data);

        return back()->with('success', "Appointment {$appointment->reference} marked as {$data['status']}.");
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return back()->with('success', 'Appointment deleted.');
    }
}
