<?php

namespace Zsoltjanes\StatamicBardOpenai\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;

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
        $allowedTypes = $this->allowedTypes();

        return [
            'prompt' => ['required', 'array'],
            'prompt.type' => ['required', Rule::in(['doc'])],
            'prompt.content' => ['required', 'array'],
            'type' => ['required', Rule::in($allowedTypes)],
        ];
    }

    private function allowedTypes(): array
    {
        $globals = GlobalSet::findByHandle('statamic_bard_openai');

        $presets = $globals
            ? $globals->in(Site::default()->handle())?->get('presets')
            : null;

        if (is_array($presets)) {
            $handles = [];

            foreach ($presets as $preset) {
                $handle = is_array($preset) ? ($preset['handle'] ?? null) : null;

                if (is_string($handle) && $handle !== '') {
                    $handles[] = $handle;
                }
            }

            $handles = array_values(array_unique($handles));

            if ($handles !== []) {
                return $handles;
            }
        }

        $promptPrefixes = $globals
            ? $globals->in(Site::default()->handle())?->get('prompt_prefixes')
            : null;

        if (is_array($promptPrefixes)) {
            $keys = array_values(array_filter(array_keys($promptPrefixes), fn ($k) => is_string($k) && $k !== ''));

            if ($keys !== []) {
                return $keys;
            }
        }

        return ['grammar', 'continue', 'summarize', 'article', 'advertisement'];
    }
}
