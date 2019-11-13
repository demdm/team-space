<?php

namespace App\UserBundle\Service\User;

use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreateService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var EntityManagerInterface;
     */
    private $em;

    /**
     * @var EventDispatcherInterface;
     */
    private $eventDispatcher;

    /**
     * CreateService constructor.
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param ValidatorInterface $validator
     * @param NormalizerInterface $normalizer
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcherInterface
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcherInterface
    ) {
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->validator = $validator;
        $this->normalizer = $normalizer;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcherInterface;
    }

    /**
     * @param CreateRequest $request
     * @return CreateResponse
     * @throws Exception
     */
    public function execute(CreateRequest $request): CreateResponse
    {
        /** @var ConstraintViolation[] $violations */
        $violations = $this->validator->validate($request);

        if ($violations->count()) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return CreateResponse::failure($errors);
        }

        $userId = Uuid::uuid4()->toString();

        $user = User::create(
            $userId,
            $request->email,
            $request->password
        );

        $user->updatePassword($this->userPasswordEncoder->encodePassword($user, $user->getPassword()));

        $this->userRepository->add($user);

        $this->em->flush();

        return CreateResponse::success();
    }
}
