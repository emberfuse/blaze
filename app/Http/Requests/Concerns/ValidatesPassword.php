<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

trait ValidatesPassword
{
    /**
     * Create new input validator instance.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createValidator(): ValidatorContract
    {
        return Validator::make($this->all(), $this->rules());
    }

    /**
     * Create new instance of validator specifically to validate given password input.
     *
     * @param string $passwordField
     * @param string $bag
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createPasswordValidator(string $passwordField = 'password', string $bag = 'default'): ValidatorContract
    {
        $validator = $this->createValidator();

        $validator->after(function ($validator) use ($passwordField) {
            $this->validate($validator, $passwordField);
        })->validateWithBag($bag);

        return $validator;
    }

    /**
     * Validate given password.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param string                                     $passwordField
     *
     * @return void
     */
    protected function validate(ValidatorContract $validator, string $passwordField = 'password'): void
    {
        if (!Hash::check($this->input($passwordField), $this->user()->password)) {
            $validator->errors()->add(
                $passwordField,
                __('The provided password does not match your current password.')
            );
        }
    }
}
