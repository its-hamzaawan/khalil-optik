<?php

namespace App\Http\Middleware;

use App\Models\Appointment;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'site' => fn () => [
                'name' => config('khalil.name'),
                'tagline' => config('khalil.tagline'),
                'founded' => config('khalil.founded'),
                'contact' => config('khalil.contact'),
                'hours' => config('khalil.hours'),
                'doctor' => config('khalil.doctor'),
            ],

            'auth' => [
                'user' => fn () => $request->user()?->only('id', 'name', 'email'),
            ],

            // Small badge counts for the admin sidebar.
            'adminCounts' => fn () => $request->user() ? [
                'pendingAppointments' => Appointment::where('status', 'pending')->count(),
                'unreadMessages' => ContactMessage::unread()->count(),
            ] : null,

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'booking' => fn () => $request->session()->get('booking'),
            ],
        ];
    }
}