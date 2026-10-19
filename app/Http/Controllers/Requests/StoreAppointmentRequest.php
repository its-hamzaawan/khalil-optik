<?php

namespace App\Http\Requests;

use App\Services\AppointmentSlots;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => preg_replace('/[^\d+]/', '', (string) $this->phone),
            'name' => trim((string) $this->name),
        ]);
    }

    public function rules(): array
    {
        $slots = app(AppointmentSlots::class);

        return [
            'name' => ['required', 'string', 'min:3', 'max:80'],
            // Pakistani mobile/landline: 03XXXXXXXXX, +923XXXXXXXXX, or 0XX-XXXXXXX
            'phone' => ['required', 'regex:/^(\+92|0092|0)\d{9,10}$/'],
            'email' => ['nullable', 'email', 'max:120'],
            'service' => ['required', Rule::in(array_keys(config('khalil.services')))],
            'preferred_date' => [
                'required', 'date_format:Y-m-d',
                'after_or_equal:' . $slots->firstBookableDate(),
                'before_or_equal:' . $slots->lastBookableDate(),
            ],
            'preferred_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->hasAny(['preferred_date', 'preferred_time'])) {
                    return;
                }
                if (! app(AppointmentSlots::class)->isAvailable($this->preferred_date, $this->preferred_time)) {
                    $validator->errors()->add('preferred_time', 'Sorry, this time is no longer available. Please choose another slot.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid Pakistani phone number, e.g. 03XX XXXXXXX.',
            'preferred_date.after_or_equal' => 'Please choose today or a later date.',
            'preferred_date.before_or_equal' => 'Bookings can only be made up to ' . config('khalil.appointments.max_days_ahead') . ' days ahead.',
        ];
    }
}
