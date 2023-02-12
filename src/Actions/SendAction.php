<?php

namespace Zsoltjanes\StatamicBardOpenai\Actions;

use Exception;
use Zsoltjanes\StatamicBardOpenai\Requests\OpenAIRequest;

class SendAction
{

    private TiptapContentToHtmlAction $contentToHtmlAction;
    private PostCompletionsAction $postCompletionsAction;
    private array $openAiConfig;
    private array $settingsConfig;

    public function __construct(
        TiptapContentToHtmlAction $contentToHtmlAction,
        PostCompletionsAction     $postCompletionsAction
    )
    {
        $this->openAiConfig = config('statamic-bard-openai');
        $this->settingsConfig = config('statamic-bard-openai-settings');
        $this->contentToHtmlAction = $contentToHtmlAction;
        $this->postCompletionsAction = $postCompletionsAction;
    }

    public function run(OpenAIRequest $request)
    {
        $type = $request->get('type');
        $prompt = $request->get('prompt');
        $promptPrefix = $this->settingsConfig['prompt-prefixes'][$type];

        $html = $this->contentToHtmlAction->run($prompt);
        $headers = $this->setHeaders();

        $url = $this->settingsConfig['api_url'];
        $prompt = $promptPrefix . $html;

        $data = $this->setData($prompt);

        return $this->postCompletionsAction->run($url, $headers, $data);
    }

    private function setHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->openAiConfig['api_key'],
        ];

        $organization = $this->openAiConfig['organization'];

        if ($organization) {
            $headers['OpenAI-Organization'] = $organization;
        }

        return $headers;
    }

    private function setData($prompt): array
    {
        return [
            ...$this->openAiConfig['defaults'],
            'prompt' => $prompt,
        ];
    }

}
