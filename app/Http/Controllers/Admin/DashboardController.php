<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ContactMessage;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $today = now(config('khalil.timezone'))->toDateString();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'products' => Product::count(),
                'activeProducts' => Product::active()->count(),
                'lowStock' => Product::active()->where('stock', '<=', 2)->count(),
                'todayAppointments' => Appointment::holdingSlot()->whereDate('preferred_date', $today)->count(),
                'pendingAppointments' => Appointment::where('status', 'pending')->count(),
                'unreadMessages' => ContactMessage::unread()->count(),
            ],
            'upcoming' => Appointment::holdingSlot()
                ->upcoming()
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('preferred_date')
                ->orderBy('preferred_time')
                ->take(8)
                ->get(),
            'messages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}
