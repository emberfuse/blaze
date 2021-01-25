<?php

namespace Cratespace\Preflight\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Cratespace\Citadel\Http\Requests\Concerns\AuthorizesRequests;

class LogoutOtherBrowserSessionsRequest extends FormRequest
{
    use AuthorizesRequests;

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
        return ['password' => ['password', 'string', 'required']];
    }
}
