<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['required', 'string', 'max:20', 'regex:/^0[0-9]{9,10}$/', Rule::unique(User::class, 'phone_number')],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => trim($input['first_name'] . ' ' . $input['last_name']),
            'email' => $input['email'],
            'phone_number' => $input['phone'],
            'password' => $input['password'],
        ]);
    }
}
