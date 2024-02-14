<?php

namespace App\BotBuddy\Workflow\Events;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScriptComplete extends FormRequest
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
            'script_id' => [
                'required',
                'integer',
                Rule::exists('user_scripts', 'id')
                ->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
        ];
    }
}
