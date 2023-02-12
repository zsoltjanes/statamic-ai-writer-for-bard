<?php

namespace Zsoltjanes\StatamicBardOpenai\Controllers;

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
}
