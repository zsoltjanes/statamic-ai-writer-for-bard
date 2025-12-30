<?php

namespace Zsoltjanes\StatamicBardOpenai\Actions;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenAiRequestAction
{
    public function run($method, $url, $headers, $data): bool|string
    {
        $client = new Client();

        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $body = json_decode($response->getBody()->getContents());

//            Log::debug('OpenAI raw response', [
//                'response' => $body,
//            ]);

            $text = $this->extractText($body);

            $text = $text ? trim(preg_replace('/\s+/', ' ', $text)) : null;

//            Log::debug('OpenAI extracted text', [
//                'text' => $text,
//            ]);

            return json_encode([
                'text' => $text,
            ]);
        } catch (Exception $e) {

            Log::error('OpenAI request failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $e->getMessage();
        }
    }

    private function extractText(object $body): ?string
    {
        // 1) Top-level shortcut
        if (isset($body->output_text) && is_string($body->output_text) && $body->output_text !== '') {
            return $body->output_text;
        }

        // 2) Responses API parsing
        if (isset($body->output) && is_array($body->output)) {
            $chunks = [];

            foreach ($body->output as $item) {
                if (!is_object($item) || ($item->type ?? null) !== 'message') {
                    continue;
                }

                $content = $item->content ?? null;
                if (!is_array($content)) {
                    continue;
                }

                foreach ($content as $part) {
                    if (!is_object($part)) {
                        continue;
                    }

                    if (($part->type ?? null) === 'output_text' && isset($part->text)) {
                        $chunks[] = $part->text;
                        continue;
                    }

                    if (isset($part->text)) {
                        $chunks[] = $part->text;
                    }
                }
            }

            $joined = trim(implode("\n", array_filter($chunks)));
            if ($joined !== '') {
                return $joined;
            }
        }

        // 3) Legacy fallback
        if (isset($body->choices[0]->text) && is_string($body->choices[0]->text)) {
            return $body->choices[0]->text;
        }

        return null;
    }
}
