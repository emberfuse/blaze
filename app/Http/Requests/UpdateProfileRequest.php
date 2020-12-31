<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Concerns\ValidatesInput;
use App\Http\Requests\Concerns\AuthorizesRequests;

class UpdateProfileRequest extends FormRequest
{
    use ValidatesInput;
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
        return $this->getRulesFor('update_profile', [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
        ]);
    }
}
