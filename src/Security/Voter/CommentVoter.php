<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    const CREATE = 'COMMENT_CREATE';
    const READ = 'COMMENT_READ';
    const EDIT = 'COMMENT_EDIT';
    const DELETE = 'COMMENT_DELETE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject) : bool
    {
        return in_array($attribute, [self::CREATE, self::READ, self::EDIT, self::DELETE])
            && ($subject instanceof Comment || $subject === null);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $comment = $subject;

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
                return $this->canEdit($comment, $user);
            case self::DELETE:
                return $this->canDelete($comment, $user);
        }

        return false;
    }

    private function canCreate() : bool {
        return $this->security->isGranted("ROLE_VALIDATED");
    }

    private function canRead() : bool {
        return $this->security->isGranted("ROLE_VALIDATED");
    }

    private function canEdit(Comment $comment, UserInterface $user) : bool {
        return $user === $comment->getAuthor() || $this->security->isGranted("ROLE_ADMIN");
    }

    private function canDelete(Comment $comment, UserInterface $user) : bool {
        return $this->canEdit($comment, $user);
    }
}
