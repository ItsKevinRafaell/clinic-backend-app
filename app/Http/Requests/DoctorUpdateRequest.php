<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,'.$this->id],
            'role' => ['required', 'string'],
            'clinic_id' => ['required', 'integer'],
            'specialist_id' => ['required', 'integer'],
        ];
    }
}
