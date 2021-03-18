<?php


namespace App\EntityListener;

use App\Utils\StringUtils;

class SlugListener
{
    public function prePersist($e)
    {
        $e->setSlug(StringUtils::slugify($e));
    }
}
