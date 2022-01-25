<?php

namespace App\Http\Requests;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $baseRules = [];
        $baseRules['last_name'] = 'required|string|min:2|max:45';
        $baseRules['first_name'] = 'required|string|min:2|max:45';

        if($this->has('middle_name') && $this->get('middle_name'))
            $baseRules['middle_name'] = 'nullable|string|min:2|max:45';

        if($this->getMethod() === 'POST')
        {
            $baseRules['email'] = [
                'required', 'string', 'email', 'max:100', Rule::unique('users')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];

            if($this->has('password') && $this->get('password'))
                $baseRules['password'] = 'required|string|min:8|max:100|confirmed';
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['email'] = [
                'required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];

            if($this->has('old_password') && $this->get('old_password'))
            {
                $baseRules['old_password'] = [
                    'required', 'string', 'min:8', 'max:100', function($attribute, $value, $fail) {
                        if(!$this->user->checkPassword($value, $this->segment(2)))
                            $fail('The :attribute does not match.');
                    }
                ];

                $baseRules['new_password'] = 'required_with:old_password|string|min:8|max:100|confirmed';
            }

            if($this->has('new_password') && $this->get('new_password'))
            {
                $baseRules['new_password'] = 'required|string|min:8|max:100|confirmed';

                $baseRules['old_password'] = [
                    'required_with:new_password', 'string', 'min:8', 'max:100', function($attribute, $value, $fail) {
                        if(!$this->user->checkPassword($value, $this->segment(2)))
                            $fail('The :attribute does not match.');
                    }
                ];
            }
        }

        if($this->hasFile('image'))
            $baseRules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        $baseRules['user_type'] = 'required|integer|in:1,2';
        $baseRules['status'] = 'required|integer|in:0,1';

        return $baseRules;
    }
}
