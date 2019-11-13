<?php

namespace App\UserBundle\Service\User;

use App\UserBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginService
{
    /**
     * @var PasswordChecker
     */
    private $passwordChecker;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * LoginService constructor.
     * @param PasswordChecker $passwordChecker
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(PasswordChecker $passwordChecker, UserRepository $userRepository, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->passwordChecker = $passwordChecker;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function execute(LoginRequest $request): LoginResponse
    {
        /** @var ConstraintViolation[] $violations */
        $violations = $this->validator->validate($request);

        if ($violations->count()) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return LoginResponse::failure($errors);
        }

        $user = $this->userRepository->findOneBy(['email' => $request->email]);

        if (!$user) {
            return LoginResponse::failure(['email' => 'Invalid credentials']);
        }

        if ($this->passwordChecker->check($user, $request->password)) {
            return LoginResponse::failure(['email' => 'Invalid credentials']);
        }

        $token = Uuid::fromString($user->getEmail())->toString();

        $user->updateAuthToken($token);

        $this->em->flush();

        return LoginResponse::success($token);
    }
}
