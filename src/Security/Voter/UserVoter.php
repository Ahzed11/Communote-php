<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const CREATE = 'USER_CREATE';
    const READ = 'USER_READ';
    const README = 'USER_README';
    const EDIT = 'USER_EDIT';
    const DELETE = 'USER_DELETE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject) : bool
    {
        return in_array($attribute, [self::CREATE, self::READ, self::README, self::EDIT, self::DELETE])
            && ($subject instanceof User || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();

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
            case self::README:
                return $this->canReadMe($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    private function canCreate() : bool {
        return true;
    }

    private function canRead() : bool {
        return $this->security->isGranted("ROLE_USER");
    }

    private function canReadMe(User $subject, UserInterface $user) : bool {
        return $subject === $user;
    }

    private function canEdit(User $subject, UserInterface $user) : bool {
        return $subject->getUsername() === $user->getUsername() || $this->security->isGranted("ROLE_ADMIN");
    }

    private function canDelete(User $subject, UserInterface $user) : bool {
        return $this->canEdit($subject, $user);
    }
}
