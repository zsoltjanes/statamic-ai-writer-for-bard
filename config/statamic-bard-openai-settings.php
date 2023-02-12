<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default prompt prefixes
    |--------------------------------------------------------------------------
    |
    | The default prompt prefixes are the prefixed text that appear before the
    | prompt or the input provided by the user.
    |
    */

    'prompt-prefixes' => [
        'grammar' => 'Correct my grammar and give back the same HTML format:',
        'continue' => 'Continue the following and give back the same HTML format:',
        'summarize' => 'Summarize the following:',
        'article' => 'Generate an article about the following:',
        'advertisement' => 'Generate an advertisement about the following with emojis:',
    ],

    /*
    |--------------------------------------------------------------------------
    | OpenAI API URL
    |--------------------------------------------------------------------------
    |
    | The default URL for the OpenAI completions endpoint.
    |
    */

    'api_url' => 'https://api.openai.com/v1/completions',

];
