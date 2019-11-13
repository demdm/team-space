<?php

namespace App\UserBundle\Security;

use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    const HEADER_TOKEN_NAME = 'X-AUTH-TOKEN';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TokenAuthenticator constructor.
     * @param UserRepository $userRepository
     * @param SessionInterface $session
     */
    public function __construct(
        UserRepository $userRepository,
        SessionInterface $session
    ) {
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     *
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->headers->has(self::HEADER_TOKEN_NAME);
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     *
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->headers->get(self::HEADER_TOKEN_NAME),
        ];
    }

    /**
     * @param array $credentials
     * @param UserProviderInterface $userProvider
     * @return User|void
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $authToken = $credentials['token'];

        if (null === $authToken) {
            return;
        }

        $user = $this->userRepository->findOneBy(compact('authToken'));

        if ($user) {
            return $user;
        }

        return;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => 'Authentication failed'
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}