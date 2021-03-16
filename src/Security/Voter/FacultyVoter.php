<?php

namespace App\Security\Voter;

use App\Entity\Faculty;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FacultyVoter extends Voter
{
    const CREATE = 'FACULTY_CREATE';
    const READ = 'FACULTY_READ';
    const EDIT = 'FACULTY_EDIT';
    const DELETE = 'FACULTY_DELETE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject) : bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::READ, self::EDIT, self::DELETE])
            && $subject instanceof Faculty;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();
        $faculty = $subject;

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate();
            case self::READ:
                return $this->canRead();
            case self::EDIT:
                return $this->canEdit();
            case self::DELETE:
                return $this->canDelete();
        }

        return false;
    }

    private function canCreate() : bool {
        return $this->security->isGranted("ROLE_ADMIN");
    }

    private function canRead() : bool {
        return $this->security->isGranted("ROLE_VALIDATED");
    }

    private function canEdit() : bool {
        return $this->security->isGranted("ROLE_ADMIN");
    }

    private function canDelete() : bool {
        return $this->canEdit();
    }
}
