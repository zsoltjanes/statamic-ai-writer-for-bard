<?php

namespace Zsoltjanes\StatamicBardOpenai\Actions;

use Tiptap\Editor;

class TiptapContentToHtmlAction
{
    public function run($prompt): string
    {
        return (new Editor)
            ->setContent($prompt)
            ->getHTML();
    }
}
