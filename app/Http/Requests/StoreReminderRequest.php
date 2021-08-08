<?php

namespace App\Http\Requests;

use App\Enums\ReminderUnit;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
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
            'unit' => ['required', 'in:' . implode(",", ReminderUnit::getValues())],
            'duration' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'unit.in' => 'The selected unit must be on of ' . implode(",", ReminderUnit::getValues())
        ];
    }
}
