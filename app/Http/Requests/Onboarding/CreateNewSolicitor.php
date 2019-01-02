<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewSolicitor extends FormRequest
{
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
        return [
            'name' => 'required|unique:solicitors',
            'phone' => 'required',
            'email' => 'required|email|unique:solicitor_offices',
            'office_name' => 'sometimes',
            'postcode' => 'required',
            'building_name' => 'required_without:building_number',
            'building_number' => 'required_without:building_name',
            'address_line_1' => 'required',
            'town' => 'required',
            'county' => 'required',
            'capacity' => 'required|integer|min:1',
            'contract_signed' => 'required|boolean',
        ];
    }
}
