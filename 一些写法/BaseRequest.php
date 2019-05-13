<?php

namespace App\Http\Requests;

use App\Api\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    use ApiResponse;
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
     * 自定义接口讯息返回
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $this->frobidden($validator->getMessageBag()->first(), 4003);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
//    public function rules()
//    {
//        if( !$id = $this->route('permission') ) {
//            return [
//                'name' => ['required', 'unique:permissions', new IsRouterExist()],
//                'display_name' => 'required|unique:permissions',
//                'icon_id' => 'required|integer',
//                'parent_id' => 'integer',
//                'sort' => 'integer'
//            ];
//        }else{
//            return [
//                'name' => ['required', 'unique:permissions,name,'. $id, new IsRouterExist()],
//                'display_name' => 'required|unique:permissions,display_name,' . $id,
//                'icon_id' => 'required|integer',
//                'parent_id' => 'integer',
//                'sort' => 'integer'
//            ];
//        }
//
//    }
}
