<?php

namespace Zsoltjanes\StatamicBardOpenai\Actions;

use Exception;
use GuzzleHttp\Client;

class OpenAiRequestAction
{
    public function run($method, $url, $headers, $data): bool|string
    {
        $client = new Client();

        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'body' => json_encode($data)
            ]);

            $body = json_decode($response->getBody()->getContents());

            $text = trim(preg_replace('/\s+/', ' ', $body->choices[0]->text));

            return json_encode([
                'text' => $text
            ]);

        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
}
