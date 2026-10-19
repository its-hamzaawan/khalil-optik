<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Carbon;

/**
 * Works out which appointment times can still be booked on a given day,
 * based on the rules in config/khalil.php.
 */
class AppointmentSlots
{
    private array $rules;
    private string $tz;

    public function __construct()
    {
        $this->rules = config('khalil.appointments');
        $this->tz = config('khalil.timezone');
    }

    public function now(): Carbon
    {
        return Carbon::now($this->tz);
    }

    public function firstBookableDate(): string
    {
        return $this->now()->toDateString();
    }

    public function lastBookableDate(): string
    {
        return $this->now()->addDays($this->rules['max_days_ahead'])->toDateString();
    }

    public function isClosedDay(Carbon $date): bool
    {
        return in_array($date->dayOfWeek, $this->rules['closed_days'], true);
    }

    /**
     * @return array<int, array{time: string, label: string, available: bool}>
     */
    public function forDate(string $date): array
    {
        $day = Carbon::createFromFormat('Y-m-d', $date, $this->tz)->startOfDay();
        $today = $this->now()->startOfDay();

        if ($day->lt($today) || $day->gt(Carbon::parse($this->lastBookableDate(), $this->tz)) || $this->isClosedDay($day)) {
            return [];
        }

        $booked = Appointment::query()
            ->holdingSlot()
            ->whereDate('preferred_date', $day->toDateString())
            ->selectRaw('preferred_time, COUNT(*) as total')
            ->groupBy('preferred_time')
            ->pluck('total', 'preferred_time');

        $earliest = $this->now()->addMinutes($this->rules['lead_minutes']);
        $cursor = $day->copy()->setTimeFromTimeString($this->rules['opens_at']);
        $close = $day->copy()->setTimeFromTimeString($this->rules['closes_at']);

        $slots = [];
        while ($cursor->lt($close)) {
            $time = $cursor->format('H:i');
            $slots[] = [
                'time' => $time,
                'label' => $cursor->format('g:i A'),
                'available' => $cursor->gte($earliest) && ($booked[$time] ?? 0) < $this->rules['per_slot'],
            ];
            $cursor->addMinutes($this->rules['slot_minutes']);
        }

        return $slots;
    }

    public function isAvailable(string $date, string $time): bool
    {
        foreach ($this->forDate($date) as $slot) {
            if ($slot['time'] === $time) {
                return $slot['available'];
            }
        }

        return false;
    }
}
