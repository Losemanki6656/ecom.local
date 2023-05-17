<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymoCallbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ip = request()->ip();
        
        return in_array($ip, ['185.8.212.47', '185.8.212.487', '127.0.0.1']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_id' => 'required',
            'transaction_id' => 'required',
            'transaction_time' => 'required',
            'amount' => 'required',
            'sign' => 'required'
        ];
    }
}
