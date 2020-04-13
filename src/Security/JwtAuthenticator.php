<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class JwtAuthenticator
 * @package App\Security
 */
class JwtAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var JwtManager
     */
    private $jwtManager;

    /**
     * JwtAuthenticator constructor.
     *
     * @param JwtManager $jwtManager
     */
    public function __construct(JwtManager $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('Authorization header required', 401);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
        return $request->query->has('access_token')
            or 0 === stripos($request->headers->get('authorization'), 'bearer');
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request): string
    {
        return $request->query->has('access_token')
            ? $request->query->getAlnum('access_token')
            : substr($request->headers->get('authorization'), 7);
    }

    /**
     * @param string $credentials
     *
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        return $this->jwtManager->user($credentials);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
