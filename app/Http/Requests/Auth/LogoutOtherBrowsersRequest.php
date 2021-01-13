<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\HasCustomValidator;
use App\Http\Requests\Concerns\AuthorizesRequests;
use App\Http\Requests\Concerns\InputValidationRules;

class LogoutOtherBrowsersRequest extends FormRequest
{
    use AuthorizesRequests;
    use HasCustomValidator;
    use InputValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->isAuthenticated();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->passwordRules();
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->afterValidation($this->validatePassword());

        $this->setErrorBag('logoutOtherBrowsers');
    }
}
