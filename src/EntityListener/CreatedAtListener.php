<?php


namespace App\EntityListener;


use DateTime;
use Symfony\Component\Security\Core\Security;

class CreatedAtListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist($e)
    {
        $e->setUpdatedAt(new DateTime("NOW"));
        $e->setCreatedAt(new DateTime("NOW"));
    }

    public function preUpdate($e)
    {
        $e->setCreatedAt(new DateTime("NOW"));
    }
}
