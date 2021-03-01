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

    public function prePersist($e)
    {
        if (!$e->getAuthor()) {
            $e->setAuthor($this->security->getUser());
        }
    }
}