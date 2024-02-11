<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\UserService;

class UserRequest extends FormRequest
{
    protected $userService;
    public function __construct(UserService $userService){
        $this->userService =$userService;
    }
    /**
     * Determine if the user is authorized
     * to make this request.
     *
     * @return boolean
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
        return $this->userService->rules($this->all());
    }
}



?>