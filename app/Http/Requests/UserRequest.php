<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['nullable']
        ];
        if ($this->routeIs('signup.post')) {
            $rules['is_admin_user'] = ['in:0']; 
        }
        if ($this->routeIs('backend.system-user.store')) {
            $rules['is_admin_user'] = ['required','in:1']; 
            $rules['email'] = ['required', 'email', 'unique:users,email'];
        }
        if ($this->routeIs('backend.system-user.update')) {
            $systemUser = $this->route('system_user');
            $userId = $systemUser instanceof \App\Models\User ? $systemUser->id : $systemUser;
            $rules['email'] = ['required', 'email', 'unique:users,email,' . $userId];
            $rules['password'] = ['nullable', 'string', 'min:4'];
        }
        // dd($rules);
        return $rules;
    }
}
