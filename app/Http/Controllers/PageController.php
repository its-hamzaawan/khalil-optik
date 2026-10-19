<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function home(): Response
    {
        $featured = Product::active()->where('is_featured', true)->latest()->take(6)->get();

        if ($featured->isEmpty()) {
            $featured = Product::active()->latest()->take(6)->get();
        }

        return Inertia::render('Home', [
            'featured' => $featured,
            'years' => now()->year - config('khalil.founded'),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About', [
            'years' => now()->year - config('khalil.founded'),
            'leadership' => config('khalil.leadership'),
            'founder' => config('khalil.founder'),
            'registration' => config('khalil.registration'),
        ]);
    }

    public function services(): Response
    {
        return Inertia::render('Services', [
            'registration' => config('khalil.registration'),
        ]);
    }
}
