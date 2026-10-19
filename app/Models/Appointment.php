<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Appointment extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'completed', 'cancelled'];

    protected $fillable = [
        'reference', 'name', 'phone', 'email', 'service',
        'preferred_date', 'preferred_time', 'notes', 'status', 'admin_note',
    ];

    protected $appends = ['service_label', 'time_label'];

    protected function casts(): array
    {
        return ['preferred_date' => 'date:Y-m-d'];
    }

    protected static function booted(): void
    {
        static::creating(function (Appointment $a) {
            $a->reference ??= static::newReference();
            $a->status ??= 'pending';
        });
    }

    public static function newReference(): string
    {
        do {
            $ref = 'KO-' . strtoupper(Str::random(6));
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }

    /** Bookings that still occupy a slot. */
    public function scopeHoldingSlot(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('preferred_date', '>=', now(config('khalil.timezone'))->toDateString());
    }

    public function getServiceLabelAttribute(): string
    {
        return config("khalil.services.{$this->service}", Str::headline($this->service));
    }

    public function getTimeLabelAttribute(): string
    {
        return Carbon::createFromFormat('H:i', $this->preferred_time)->format('g:i A');
    }
}
