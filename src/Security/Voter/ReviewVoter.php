<?php

namespace App\Security\Voter;

use App\Entity\Review;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    const CREATE = 'REVIEW_CREATE';
    const READ = 'REVIEW_READ';
    const EDIT = 'REVIEW_EDIT';
    const DELETE = 'REVIEW_DELETE';

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
            && $subject instanceof Review;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();
        $review = $subject;

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($review, $user);
            case self::READ:
                return $this->canRead();
            case self::EDIT:
                return $this->canEdit($review, $user);
            case self::DELETE:
                return $this->canDelete();
        }

        return false;
    }

    private function canCreate(Review $review, UserInterface $user) : bool {
        return $this->security->isGranted("ROLE_VALIDATED") && $review->getNote()->getAuthor() !== $user;
    }

    private function canRead() : bool {
        return $this->security->isGranted("ROLE_VALIDATED");
    }

    private function canEdit(Review $review, UserInterface $user) : bool {
        return $user === $review->getAuthor();
    }

    private function canDelete() : bool {
        return $this->security->isGranted("ROLE_ADMIN");
    }
}
