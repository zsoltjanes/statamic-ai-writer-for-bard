<?php

namespace Zsoltjanes\StatamicBardOpenai\Actions;

use Zsoltjanes\StatamicBardOpenai\Constants\OpenAiApi;
use Zsoltjanes\StatamicBardOpenai\Requests\OpenAIRequest;

class SendAction
{

    private TiptapContentToHtmlAction $contentToHtmlAction;
    private OpenAiRequestAction $postCompletionsAction;
    private array $config;

    public function __construct(
        TiptapContentToHtmlAction $contentToHtmlAction,
        OpenAiRequestAction $postCompletionsAction
    )
    {
        $this->config = config('statamic-bard-openai');
        $this->contentToHtmlAction = $contentToHtmlAction;
        $this->postCompletionsAction = $postCompletionsAction;
    }

    public function run(OpenAIRequest $request)
    {
        $type = $request->get('type');
        $prompt = $request->get('prompt');

        $method = 'POST';

        $url = OpenAiApi::BASE_URL . OpenAiApi::URL_SUFFIX_COMPLETIONS;

        $headers = $this->setHeaders();

        $html = $this->contentToHtmlAction->run($prompt);
        $prompt = $this->config['prompt-prefixes'][$type] . $html;
        $data = $this->setData($prompt);

        return $this->postCompletionsAction->run($method, $url, $headers, $data);
    }

    private function setHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->config['api_key'],
        ];

        $organization = $this->config['organization'];

        if ($organization) {
            $headers['OpenAI-Organization'] = $organization;
        }

        return $headers;
    }

    private function setData($prompt): array
    {
        return [
            ...$this->config['defaults'],
            'prompt' => $prompt,
        ];
    }

}
