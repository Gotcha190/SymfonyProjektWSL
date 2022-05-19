<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Comment;

class CommentVoter extends Voter
{
    public const CAN_EDIT = 'CAN_EDIT';
//    public const VIEW = 'POST_VIEW';
    public const CAN_DELETE = 'CAN_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::CAN_DELETE, self::CAN_EDIT, /*self::VIEW*/])
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /**
         * @var Comment $comment
         */
        $comment = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CAN_EDIT:
                return $this->canEdit($comment, $user);
                break;
//            case self::VIEW:
//                // logic to determine if the user can VIEW
//                // return true or false
//                break;
            case self::CAN_DELETE:
                    return $this->canDelete($comment, $user);
                break;
        }

        return false;
    }

    private function canDelete(Comment $comment, User $user): bool
    {
        if($user->getId() !== $comment->getAuthor()->getId() && $user->hasRole('ROLE_ADMIN') === false) {
            return false;
        }
        return true;
    }
    private function canEdit(Comment $comment, User $user): bool
    {
        if($user->getId() !== $comment->getAuthor()->getId() && $user->hasRole('ROLE_ADMIN') === false) {
            return false;
        }
        return true;
    }
}
