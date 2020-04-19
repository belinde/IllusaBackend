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

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, $subject)
    {
        return ($subject instanceof Scene) and in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
    }

    /**
     * @param Scene $subject
     *
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        if ($attribute === self::VIEW) {
            return true;
        }
        $parent = $subject->getParent();
        if ($parent and $parent->getId()===1) {
            return false;
        }

        return $subject->getOwner() and $subject->getOwner()->isTheSameOf($token->getUser());
    }
}
