<?php

namespace App\UserBundle\Repository;

use App\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    /**
     * @param User $user
     * @throws ORMException
     */
    public function add(User $user)
    {
        $this->getEntityManager()->persist($user);
    }
}
