<?php

namespace Zsoltjanes\StatamicBardOpenai\Controllers;

use Illuminate\Http\JsonResponse;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Zsoltjanes\StatamicBardOpenai\Actions\SendAction;
use Zsoltjanes\StatamicBardOpenai\Requests\OpenAIRequest;

class StatamicBardController
{

    private SendAction $sendAction;

    public function __construct(
        SendAction $sendAction,
    )
    {
        $this->sendAction = $sendAction;
    }

    public function send(OpenAIRequest $request)
    {
        return $this->sendAction->run($request);
    }

    public function presets(): JsonResponse
    {
        $globals = GlobalSet::findByHandle('statamic_bard_openai');
        $variables = $globals ? $globals->in(Site::default()->handle()) : null;

        $presets = $variables?->get('presets');

        if (is_array($presets)) {
            $options = [];

            foreach ($presets as $preset) {
                if (! is_array($preset)) {
                    continue;
                }

                $type = $preset['handle'] ?? null;

                if (! is_string($type) || $type === '') {
                    continue;
                }

                $label = $preset['label'] ?? null;

                if (! is_string($label) || $label === '') {
                    $label = ucwords(str_replace(['_', '-'], ' ', $type));
                }

                $mode = $preset['mode'] ?? 'replace';

                if (! is_string($mode) || ! in_array($mode, ['replace', 'append', 'prepend'], true)) {
                    $mode = 'replace';
                }

                $options[$type] = [
                    'name' => $label,
                    'mode' => $mode,
                ];
            }

            return response()->json([
                'options' => $options,
            ]);
        }

        $promptPrefixes = $variables?->get('prompt_prefixes');
        $promptLabels = $variables?->get('prompt_labels');
        $promptModes = $variables?->get('prompt_modes');

        $types = is_array($promptPrefixes) ? array_keys($promptPrefixes) : [];

        $options = [];

        foreach ($types as $type) {
            $label = null;

            if (is_array($promptLabels)) {
                $label = $promptLabels[$type] ?? null;
            }

            if (! is_string($label) || $label === '') {
                $label = ucwords(str_replace(['_', '-'], ' ', (string) $type));
            }

            $mode = 'replace';

            if (is_array($promptModes)) {
                $candidateMode = $promptModes[$type] ?? null;

                if (is_string($candidateMode) && in_array($candidateMode, ['replace', 'append', 'prepend'], true)) {
                    $mode = $candidateMode;
                }
            }

            $options[$type] = [
                'name' => $label,
                'mode' => $mode,
            ];
        }

        return response()->json([
            'options' => $options,
        ]);
    }
}
