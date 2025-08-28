<?php

namespace Modules\Iuser\Http\Requests;

use Imagina\Icore\Http\Request\CoreFormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateRoleRequest extends CoreFormRequest
{
    public function rules(): array
    {
        return [
            'system_name' => 'required|unique:iuser__roles,system_name'
        ];
    }

    public function translationRules(): array
    {
        return [
            'title' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [];
    }

    public function translationMessages(): array
    {
        return [];
    }

    public function getValidator(): Validator
    {
        return $this->getValidatorInstance();
    }
}
