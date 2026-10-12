<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class CreateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id'   => ['required', 'integer', 'exists:subjects,id'],
            'schedule_day' => ['required', 'string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'],
            'start_time'   => ['required', 'string'],
            'end_time'     => ['required', 'string', 'after:start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required'    => 'Please select a subject.',
            'subject_id.exists'      => 'The selected subject is invalid.',
            'schedule_day.required'  => 'Please select a day.',
            'schedule_day.in'        => 'The selected day is invalid.',
            'start_time.required'    => 'Please enter a start time.',
            'end_time.required'      => 'Please enter an end time.',
            'end_time.after'         => 'End time must be after the start time.',
        ];
    }
}
