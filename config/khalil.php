<?php

/*
|--------------------------------------------------------------------------
| Khalil Optik — business settings
|--------------------------------------------------------------------------
| Everything the website shows about the shop lives here, so the owners can
| change a phone number or opening hours in one place.
|
| IMPORTANT: The opening hours and appointment slots below are placeholders.
| Confirm the real timings with the shop before going live.
*/

return [

    'name' => 'Khalil Optik',
    'tagline' => 'Trusted for Your Vision Since 1960',
    'founded' => 1960,
    'founder' => 'Mr. Khalil',
    'leadership' => ['Mr. Shoukat Ali', 'Muhammad Asif', 'Muhammad Ashraf'],

    'doctor' => [
        'name' => 'Dr. Khurram Rehman',
        'title' => 'Eye Specialist',
    ],

    'registration' => 'Registered with the Punjab Healthcare Commission (PHC)',

    'contact' => [
        'address' => 'Main Bazar, Sarwar Shaheed Chowk, Jauharabad, District Khushab, Punjab, Pakistan',
        'phone' => '0454-720745',
        'phone_href' => 'tel:+92454720745',
        'whatsapp' => '+92 303 285 0000',
        'whatsapp_href' => 'https://wa.me/923032850000',
        'map_query' => 'Sarwar Shaheed Chowk, Jauharabad, Khushab, Punjab, Pakistan',
        'email' => env('MAIL_FROM_ADDRESS'),
    ],

    'timezone' => 'Asia/Karachi',

    // Opening hours shown on the site (placeholders — confirm with the shop).
    'hours' => [
        ['days' => 'Sat - Thu', 'time' => '10:00 AM – 8:00 PM'],
        ['days' => 'Friday', 'time' => 'Closed'],
    ],

    // Appointment booking rules.
    'appointments' => [
        'opens_at' => '10:00',
        'closes_at' => '19:00',          // last slot starts before this time
        'slot_minutes' => 30,
        'per_slot' => 1,                 // how many bookings one slot can hold
        'closed_days' => [5],            // 0 = Sunday … 6 = Saturday (Carbon dayOfWeek)
        'max_days_ahead' => 30,
        'lead_minutes' => 60,            // same-day bookings need this much notice
    ],

    'services' => [
        'eye_test' => 'Eye Examination',
        'contact_lens' => 'Contact Lens Consultation',
        'frame_fitting' => 'Spectacle Frame & Lens Fitting',
        'follow_up' => 'Follow-up Visit',
    ],

    'product_categories' => [
        'spectacles' => 'Spectacles',
        'sunglasses' => 'Sunglasses',
        'contact-lenses' => 'Contact Lenses',
        'accessories' => 'Accessories',
    ],

];
