<?php

namespace Zsoltjanes\StatamicBardOpenai\Actions;

use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Zsoltjanes\StatamicBardOpenai\Constants\OpenAiApi;
use Zsoltjanes\StatamicBardOpenai\Requests\OpenAIRequest;

class SendAction
{

    private TiptapContentToHtmlAction $contentToHtmlAction;
    private OpenAiRequestAction $postCompletionsAction;

    public function __construct(
        TiptapContentToHtmlAction $contentToHtmlAction,
        OpenAiRequestAction $postCompletionsAction
    )
    {
        $this->contentToHtmlAction = $contentToHtmlAction;
        $this->postCompletionsAction = $postCompletionsAction;
    }

    public function run(OpenAIRequest $request)
    {
        $type = $request->get('type');
        $prompt = $request->get('prompt');

        $method = 'POST';

        $url = OpenAiApi::BASE_URL . OpenAiApi::URL_SUFFIX_RESPONSES;

        $headers = $this->setHeaders();

        $html = $this->contentToHtmlAction->run($prompt);
        $settings = $this->getSettings();
        $promptPrefix = $this->getPromptPrefix($settings, (string) $type);

        $prompt = $promptPrefix . $html;
        $data = $this->setData($prompt);

        return $this->postCompletionsAction->run($method, $url, $headers, $data);
    }

    private function getPromptPrefix(array $settings, string $type): string
    {
        $presets = $settings['presets'] ?? null;

        if (is_array($presets)) {
            foreach ($presets as $preset) {
                if (is_array($preset) && ($preset['handle'] ?? null) === $type) {
                    $prefix = $preset['prefix'] ?? '';

                    return is_string($prefix) ? $prefix : '';
                }
            }
        }

        $promptPrefixes = $settings['prompt_prefixes'] ?? null;

        if (is_array($promptPrefixes)) {
            $prefix = $promptPrefixes[$type] ?? '';

            return is_string($prefix) ? $prefix : '';
        }

        return '';
    }

    private function setHeaders(): array
    {
        $settings = $this->getSettings();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($settings['api_key'] ?? ''),
        ];

        $organization = $settings['organization'] ?? null;

        if ($organization) {
            $headers['OpenAI-Organization'] = $organization;
        }

        return $headers;
    }

    private function setData($prompt): array
    {
        $settings = $this->getSettings();
        $defaults = $settings['defaults'] ?? [];

        $model = $settings['model'] ?? ($defaults['model'] ?? 'gpt-4o-mini');
        $temperature = $settings['temperature'] ?? ($defaults['temperature'] ?? null);
        $maxOutputTokens = $settings['max_output_tokens'] ?? ($defaults['max_output_tokens'] ?? null);

        $data = [
            'model' => $model,
            'input' => $prompt,
        ];

        // Only include temperature if the model supports it
        if (!str_starts_with($model, 'gpt-5')) {
            $data['temperature'] = $temperature ?? 0.7; // Default temperature if not set
        }

        if ($maxOutputTokens !== null) {
            $data['max_output_tokens'] = $maxOutputTokens;
        }

        return $data;
    }

    private function getSettings(): array
    {
        $globals = GlobalSet::findByHandle('statamic_bard_openai');

        if (! $globals) {
            return [];
        }

        $siteHandle = Site::default()->handle();
        $variables = $globals->in($siteHandle);

        return $variables ? ($variables->data()->all() ?? []) : [];
    }

}
