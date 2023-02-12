<?php

namespace Zsoltjanes\StatamicBardOpenai\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OpenAIRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'prompt' => ['required', 'array'],
            'prompt.type' => ['required', Rule::in(['doc'])],
            'prompt.content' => ['required', 'array'],
            'type' => ['required', Rule::in(array_keys(config('statamic-bard-openai')['prompt-prefixes']))],
        ];
    }
}
