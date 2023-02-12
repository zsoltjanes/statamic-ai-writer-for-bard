<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | To access the OpenAI API, a unique API key is required. This key can be
    | retrieved by visiting the API Keys section of your account. To create an
    | API key, simply visit the following link:
    | https://platform.openai.com/account/api-keys
    |
    */

    'api_key' => env('OPENAI_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    |
    | If you are a member of multiple organizations, you have the option to
    | specify the organization that you want to use for an API request by
    | passing a header. The API request will then be counted towards the
    | subscription quota of the specified organization.
    |
    */

    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Default parameters
    |--------------------------------------------------------------------------
    |
    | The default settings can be found on the:
    | https://platform.openai.com/docs/api-reference/completions/create
    |
    */

    'defaults' => [
        'model' => 'text-davinci-003',
        'max_tokens' => 2000,
        'temperature' => 0,
        'top_p' => 1.0,
        'frequency_penalty' => 0.0,
        'presence_penalty' => 0.0,
    ],

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

];
