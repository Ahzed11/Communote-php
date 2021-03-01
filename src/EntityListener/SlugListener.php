<?php


namespace App\EntityListener;

use App\Entity\Note;
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
        $slug = strtolower(preg_replace(array( '/[^-a-zA-Z0-9\s]/', '/[\s]/' ), array( '', '-' ), $e->getTitle()));
        $slug .= uniqid("-");
        $e->setSlug($slug);
    }
}
