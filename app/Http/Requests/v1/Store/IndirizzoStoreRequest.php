<?php

namespace App\Http\Requests\v1\Store;

use Illuminate\Foundation\Http\FormRequest;

class IndirizzoStoreRequest extends FormRequest
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
            "idTipologiaIndirizzo" => "required|integer",
            "idContatto" => "required|integer",
            "nazione" => "required|string",
            "cittadinanza" => "required|string",
            "provincia" => "required|string",
            "citta" => "required|string",
            "cap" => "required|integer",
            "indirizzo" => "required|string|max:255",
            "civico" => "required|integer",
            "altro" => "string",
        ];
    }
}
