<?php

namespace App\Http\Requests\Newsletter;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriberRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:newsletter_subscribers,email,NULL,id,deleted_at,NULL',
            ],
            'name' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already subscribed',
        ];
    }
}
