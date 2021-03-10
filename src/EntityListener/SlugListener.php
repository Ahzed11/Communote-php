<?php


namespace App\EntityListener;

use App\Entity\Note;
use App\Utils\Slugger;
use Symfony\Component\Security\Core\Security;

class SlugListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist($e)
    {
        $e->setSlug(Slugger::slug($e));
    }
}
