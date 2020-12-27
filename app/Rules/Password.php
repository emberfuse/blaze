<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{
    /**
     * The minimum length of the password.
     *
     * @var int
     */
    protected int $length = 8;

    /**
     * Indicates if the password must contain one uppercase character.
     *
     * @var bool
     */
    protected bool $requireUppercase = false;

    /**
     * Indicates if the password must contain one numeric digit.
     *
     * @var bool
     */
    protected bool $requireNumeric = false;

    /**
     * Indicates if the password must contain one special character.
     *
     * @var bool
     */
    protected bool $requireSpecialCharacter = false;

    /**
     * The message that should be used when validation fails.
     *
     * @var string
     */
    protected $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->requireUppercase && Str::lower($value) === $value) {
            return false;
        }

        if ($this->requireNumeric && ! preg_match('/[0-9]/', $value)) {
            return false;
        }

        if ($this->requireSpecialCharacter && ! preg_match('/[\W_]/', $value)) {
            return false;
        }

        return Str::length($value) >= $this->length;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->message) {
            return $this->message;
        }

        switch (true) {
            case $this->requireUppercase
                && ! $this->requireNumeric
                && ! $this->requireSpecialCharacter:
                return __('The :attribute must be at least :length characters and contain at least one uppercase character.', [
                    'length' => $this->length,
                ]);

            case $this->requireNumeric
                && ! $this->requireUppercase
                && ! $this->requireSpecialCharacter:
                return __('The :attribute must be at least :length characters and contain at least one number.', [
                    'length' => $this->length,
                ]);

            case $this->requireSpecialCharacter
                && ! $this->requireUppercase
                && ! $this->requireNumeric:
                return __('The :attribute must be at least :length characters and contain at least one special character.', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
                && $this->requireNumeric
                && ! $this->requireSpecialCharacter:
                return __('The :attribute must be at least :length characters and contain at least one uppercase character and one number.', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
                && $this->requireSpecialCharacter
                && ! $this->requireNumeric:
                return __('The :attribute must be at least :length characters and contain at least one uppercase character and one special character.', [
                    'length' => $this->length,
                ]);

            case $this->requireUppercase
                && $this->requireNumeric
                && $this->requireSpecialCharacter:
                return __('The :attribute must be at least :length characters and contain at least one uppercase character, one number, and one special character.', [
                    'length' => $this->length,
                ]);

            default:
                return __('The :attribute must be at least :length characters.', [
                    'length' => $this->length,
                ]);
        }
    }

    /**
     * Set the minimum length of the password.
     *
     * @param int $length
     *
     * @return \App\Rules\Password
     */
    public function length(int $length): Password
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Indicate that at least one uppercase character is required.
     *
     * @return \App\Rules\Password
     */
    public function requireUppercase(): Password
    {
        $this->requireUppercase = true;

        return $this;
    }

    /**
     * Indicate that at least one numeric digit is required.
     *
     * @return \App\Rules\Password
     */
    public function requireNumeric(): Password
    {
        $this->requireNumeric = true;

        return $this;
    }

    /**
     * Indicate that at least one special character is required.
     *
     * @return \App\Rules\Password
     */
    public function requireSpecialCharacter(): Password
    {
        $this->requireSpecialCharacter = true;

        return $this;
    }

    /**
     * Set the message that should be used when the rule fails.
     *
     * @param string $message
     *
     * @return \App\Rules\Password
     */
    public function withMessage(string $message): Password
    {
        $this->message = $message;

        return $this;
    }
}
