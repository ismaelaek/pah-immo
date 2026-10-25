<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => __('front.contact.first_name'),
            'last_name' => __('front.contact.last_name'),
            'email' => __('front.contact.email'),
            'phone' => __('front.contact.phone'),
            'message' => __('front.contact.message'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('front.contact.errors.first_name_required'),
            'last_name.required' => __('front.contact.errors.last_name_required'),
            'email.required' => __('front.contact.errors.email_required'),
            'email.email' => __('front.contact.errors.email_email'),
            'phone.required' => __('front.contact.errors.phone_required'),
            'message.required' => __('front.contact.errors.message_required'),
            'message.max' => __('front.contact.errors.message_max'),
        ];
    }
}
