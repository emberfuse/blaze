<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Concerns\ValidatesInput;
use App\Http\Requests\Traits\HasCustomValidator;
use App\Http\Requests\Concerns\AuthorizesRequests;

class UpdatePasswordRequest extends FormRequest
{
    use AuthorizesRequests;
    use ValidatesInput;
    use HasCustomValidator;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->isAllowed('manage', $this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRulesFor('update_password');
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->setAfterValidationHook(function ($validator) {
            if (! Hash::check($this->current_password, $this->user()->password)) {
                $validator->errors()->add(
                    'current_password',
                    __('The provided password does not match your current password.')
                );
            }
        });

        $this->setCustomErrorBag('updatePassword');
    }
}
