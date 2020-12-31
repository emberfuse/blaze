<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\AuthorizesRequests;
use App\Http\Requests\Concerns\ValidatesInput;
use Illuminate\Foundation\Http\FormRequest;

class DeleteProfilePhotoRequest extends FormRequest
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
        return [];
    }
}
