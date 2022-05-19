<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\{Authentication\Token\TokenInterface,
    Authorization\Voter\Voter,
    User\UserInterface
};
use App\Entity\Comment;

class CommentVoter extends Voter
{
    public const CAN_EDIT = 'CAN_EDIT';
    public const CAN_DELETE = 'CAN_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::CAN_DELETE, self::CAN_EDIT])
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /**
         * @var Comment $comment
         * @var User $user
         */
        $comment = $subject;
        return match ($attribute) {
            self::CAN_EDIT => $this->canEdit($comment, $user),
            self::CAN_DELETE => $this->canDelete($comment, $user),
            default => false,
        };

    }

    private function canDelete(Comment $comment, User $user): bool
    {
        if ($user->getId() !== $comment->getAuthor()->getId() && $user->hasRole('ROLE_ADMIN') === false) {
            return false;
        }
        return true;
    }

    private function canEdit(Comment $comment, User $user): bool
    {
        if ($user->getId() !== $comment->getAuthor()->getId() && $user->hasRole('ROLE_ADMIN') === false) {
            return false;
        }
        return true;
    }
}
