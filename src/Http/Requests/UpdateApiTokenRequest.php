<?php

namespace Emberfuse\Blaze\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Emberfuse\Scorch\Http\Requests\Concerns\AuthorizesRequests;

class UpdateApiTokenRequest extends FormRequest
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
        return [
            'permissions' => 'array',
            'permissions.*' => 'string',
        ];
    }
}
