<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProposalRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'category'    => 'required|string',
            'owner'       => 'required|string',
            'meta_data'   => 'nullable',
            'txHash'      => 'required|string|unique:proposals,txHash',
            'proposal_id'=> 'required|string|unique:proposals,proposal_id',
        ];
    }
}
