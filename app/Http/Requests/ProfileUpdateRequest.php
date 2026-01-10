<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $isStaff = $user?->hasAnyRole(['admin', 'teacher']) ?? false;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'date_of_birth' => [Rule::prohibitedIf($isStaff), 'nullable', 'date', 'before_or_equal:today'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:20'],
            'grade_level' => [Rule::prohibitedIf($isStaff), 'nullable', 'string', 'max:100'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'school_name' => [Rule::prohibitedIf($isStaff), 'nullable', 'string', 'max:255'],
            'nisn' => [Rule::prohibitedIf($isStaff), 'nullable', 'string', 'max:30'],
            'nip' => [Rule::prohibitedIf(! $isStaff), 'nullable', 'string', 'max:30'],
            'classes_taught' => [Rule::prohibitedIf(! $isStaff), 'nullable', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
