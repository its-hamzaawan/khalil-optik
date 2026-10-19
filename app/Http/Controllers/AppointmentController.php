<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentSlots;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentSlots $slots) {}

    public function create(Request $request): Response
    {
        return Inertia::render('Appointment', [
            'services' => config('khalil.services'),
            'minDate' => $this->slots->firstBookableDate(),
            'maxDate' => $this->slots->lastBookableDate(),
            'closedDays' => config('khalil.appointments.closed_days'),
            'preselect' => $request->query('service'),
        ]);
    }

    /** JSON list of slots for the date picker. */
    public function slots(Request $request): JsonResponse
    {
        $data = $request->validate(['date' => ['required', 'date_format:Y-m-d']]);

        return response()->json(['slots' => $this->slots->forDate($data['date'])]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Re-check inside a transaction so two people can't grab the same slot at once.
        $appointment = DB::transaction(function () use ($data) {
            $taken = Appointment::holdingSlot()
                ->whereDate('preferred_date', $data['preferred_date'])
                ->where('preferred_time', $data['preferred_time'])
                ->lockForUpdate()
                ->count();

            if ($taken >= config('khalil.appointments.per_slot')) {
                throw ValidationException::withMessages([
                    'preferred_time' => 'Someone just booked this slot. Please pick another time.',
                ]);
            }

            return Appointment::create($data);
        });

        return redirect()->route('appointment.create')->with('booking', [
            'reference' => $appointment->reference,
            'name' => $appointment->name,
            'date' => $appointment->preferred_date->format('l, j F Y'),
            'time' => $appointment->time_label,
            'service' => $appointment->service_label,
        ]);
    }
}
