<?php

namespace Modules\Iuser\Http\Requests;

use Imagina\Icore\Http\Request\CoreFormRequest;
use Imagina\Icore\Http\Rules\UniqueRule;
use Illuminate\Contracts\Validation\Validator;

class CreateUserRequest extends CoreFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', new UniqueRule('iuser__users', null, null, trans('iuser::users.messages.unavailableUserName'))],
            'password' => 'required|confirmed|min:8',
            'roles' => 'required|array'
        ];
    }

    public function translationRules(): array
    {
        return [];
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
