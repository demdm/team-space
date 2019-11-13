<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Service\User\CreateRequest;
use App\UserBundle\Service\User\CreateService;
use App\UserBundle\Service\User\LoginRequest;
use App\UserBundle\Service\User\LoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class AuthController
{
    public function register(Request $request, CreateService $createUserService): JsonResponse
    {
        $createUserRequest = new CreateRequest(
            $request->request->get('email'),
            $request->request->get('password')
        );

        $createUserResponse = $createUserService->execute($createUserRequest);

        return new JsonResponse($createUserResponse);
    }

    public function login(Request $request, LoginService $loginUserService): JsonResponse
    {
        $loginUserRequest = new LoginRequest(
            $request->request->get('email'),
            $request->request->get('password')
        );

        $loginUserResponse = $loginUserService->execute($loginUserRequest);

        return new JsonResponse($loginUserResponse);
    }

    public function logout(): JsonResponse
    {
        return new JsonResponse(['action' => 'logout']);
    }

    public function user(Security $security): JsonResponse
    {
        return new JsonResponse(['user' => 'ssds']);
    }
}
