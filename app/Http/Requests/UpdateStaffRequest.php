<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slug' => 'required|sometimes|integer|exists:staff,id',
            'name' => 'required|sometimes|string|max:255',
            'no_telpon' => 'required|sometimes|string|max:255',
            'alamat' => 'required|sometimes|string',
            'jabatan'=> 'required|sometimes|in:Kepala Bagian,Manager OPS,Staff',
            'departement_id'=> 'required|exists:departements,id',
        ];
    }
}
