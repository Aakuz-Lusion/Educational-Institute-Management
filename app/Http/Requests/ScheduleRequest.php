<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'grade' => 'required|string',
            'period_id' => 'required|integer|min:1|max:10',
        ];
    }
}