<?php

namespace App\Security;

use App\Entity\Scene;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class SceneVoter
 * @package App\Security
 */
class SceneVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const CREATE = 'create';

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject)
    {
        return ($subject instanceof Scene) and in_array($attribute,
                [self::VIEW, self::EDIT, self::DELETE, self::CREATE]);
    }

    /**
     * @param Scene $subject
     *
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $parent = $subject->getParent();
        switch ($attribute) {
            case self::VIEW:
                return true;
            case self::CREATE:
                return ($parent and $parent->getOwner()->isTheSameOf($token->getUser()));
            default:
                if ($parent and $parent->getId() === 1) {
                    return false;
                }

                return $subject->getOwner() and $subject->getOwner()->isTheSameOf($token->getUser());
        }
    }
}
