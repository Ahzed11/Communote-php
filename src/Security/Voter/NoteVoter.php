<?php

namespace App\Security\Voter;

use App\Entity\Note;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class NoteVoter extends Voter
{
    const CREATE = 'NOTE_CREATE';
    const READ = 'NOTE_READ';
    const EDIT = 'NOTE_EDIT';
    const DELETE = 'NOTE_DELETE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject) : bool
    {
        return in_array($attribute, [self::CREATE, self::READ, self::EDIT, self::DELETE])
            && ($subject instanceof Note || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();
        $note = $subject;

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
                return $this->canEdit($note, $user);
            case self::DELETE:
                return $this->canDelete($note, $user);
        }

        return false;
    }

    private function canCreate() : bool {
        return $this->security->isGranted("ROLE_VALIDATED");
    }

    private function canRead() : bool {
        return $this->security->isGranted("ROLE_VALIDATED");
    }

    private function canEdit(Note $note, UserInterface $user) : bool {
        return $user === $note->getAuthor() || $this->security->isGranted("ROLE_ADMIN");
    }

    private function canDelete(Note $note, UserInterface $user) : bool {
        return $this->canEdit($note, $user);
    }
}
