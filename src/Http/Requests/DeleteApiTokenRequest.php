<?php

namespace Cratespace\Preflight\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Cratespace\Citadel\Http\Requests\Concerns\AuthorizesRequests;

class DeleteApiTokenRequest extends FormRequest
{
    use AuthorizesRequests;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->isAuthenticated() &&
            $this->user()
                ->tokens()
                ->where('id', $this->token)
                ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
