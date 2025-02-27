<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'requester'      => 'required|string|max:255',
            'destination'    => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date'    => 'required|date|after_or_equal:departure_date',
        ];
    }
}
