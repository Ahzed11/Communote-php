<?php


namespace App\EntityListener;


use Symfony\Component\Security\Core\Security;

class AuthorListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist($e): void
    {
        /* When using the fixtures
        if ($e->getAuthor() === null){
            $e->setAuthor($this->security->getUser());
        }
        */

        $e->setAuthor($this->security->getUser());
    }
}
