<?php

namespace App\Http\Requests\Traits;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

trait HasCustomValidator
{
    /**
     * Validator after hook functions.
     *
     * @var \Closure
     */
    protected $afterValidationHook;

    /**
     * The custom key to be used for the view error bag.
     *
     * @var string
     */
    protected $customErrorBag;

    /**
     * Get request validator instance.
     *
     * @param \Illuminate\Contracts\Validation\Factory
     *
     * @return ValidatorContract
     */
    public function validator(Factory $factory): ValidatorContract
    {
        $validator = $this->makeValidator($factory);

        if (! is_null($this->afterValidationHook)) {
            $validator = $this->addAfterValidationHook($validator);
        }

        if (! is_null($this->customErrorBag)) {
            $validator = $this->addCustomErrorBag($validator);
        }

        return $validator;
    }

    /**
     * Make new instance of validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function makeValidator(Factory $factory): ValidatorContract
    {
        return $factory->make(
            $this->validationData(),
            $this->rules(),
            $this->messages(),
            $this->attributes()
        );
    }

    /**
     * Attach callbacks to be run after validation is completed.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function addAfterValidationHook(ValidatorContract $validator): ValidatorContract
    {
        $validator->after($this->afterValidationHook);

        return $validator;
    }

    /**
     * Set callbacks to be run after validation is completed.
     *
     * @param \Closure $callback
     *
     * @return \Illuminate\Foundation\Http\FormRequest
     */
    public function setAfterValidationHook(Closure $callback): FormRequest
    {
        $this->afterValidationHook = $callback;

        return $this;
    }

    /**
     * Add custom error message bag.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function addCustomErrorBag(ValidatorContract $validator): ValidatorContract
    {
        $validator->validateWithBag($this->customErrorBag);

        return $validator;
    }

    /**
     * Specify custom error message bag.
     *
     * @param string $callback
     *
     * @return \Illuminate\Foundation\Http\FormRequest
     */
    public function setCustomErrorBag(string $bag): FormRequest
    {
        $this->customErrorBag = $bag;

        return $this;
    }
}
