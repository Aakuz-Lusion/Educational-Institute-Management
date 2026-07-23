<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('teacher') ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'password' => 'required|min:6',
            'subject' => 'required|string',
            'grades' => 'required|array|min:1|max:5',
            'grades.*' => 'string',
            'priority' => 'required|in:high,medium,low',
            'days' => 'required|array|min:3|max:6',
            'periods' => 'required|array|min:3|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Teacher name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'grades.required' => 'Please select at least one grade.',
            'grades.max' => 'Maximum 5 grades allowed.',
            'days.required' => 'Please select at least 3 days.',
            'periods.required' => 'Please select at least 3 periods.',
        ];
    }
}