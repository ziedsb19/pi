<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 13/04/2019
 * Time: 16:59
 */

namespace UserBundle\Security;


use ReCaptcha\RequestMethod\Post;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use UserBundle\Entity\Utilisateur;

class PostVoter extends Voter
{
    const VIEW = 'view';

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }

        return $subject instanceof Post;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateur) {
            $user = null;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $user);
            default:
                throw new \LogicException(sprintf('Unhandled attribute "%s"', $attribute));
        }
    }

    private function canView( Utilisateur $user = null)
    {
        if  (!$user || !$user->isPremium()) {
            $this->requestStack->getCurrentRequest()->attributes->set('requires_premium', true);

            return false;
        }

        return true;
    }
}