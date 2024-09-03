<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Carbon\Carbon;

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
        $min_age = 16;
        // dd($input['birth_date']);

        // Converter a data de nascimento para o formato 'Y-m-d'
        $birthDate = Carbon::parse($input['birth_date'])->format('Y-m-d');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', function ($attribute, $value, $fail) {
                if (strpos($value, '@converger.com.br') === false) {
                    $fail('O domínio de e-mail não é válido.');
                }
            }],
            'position' => ['required', 'integer', 'digits_between:1,2'],
            'birth_date' => ['required', 'date', 'before:' . now()->subYears($min_age)->format('Y-m-d')],
            'password' => ['required', $this->passwordRules()],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'birth_date.before' => 'É necessário ter ao menos ' . $min_age . ' anos para efetuar o cadastro.',
        ])->validate();
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'position_id' => $input['position'],
            'birth_date' => $birthDate, // Usar a data formatada
            'status' => 0,
            'password' => Hash::make($input['password']),
        ]);
    }
}
