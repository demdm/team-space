<?php

namespace App\UserBundle\Repository;

use App\UserBundle\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * @method User|null find($id)
 * @method User[] findAll()
 * @method User|null findOneBy(array $criteria)
 * @method User|null findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 * @method User[]|Collection matching(Criteria $criteria)
 */
interface UserRepository extends ObjectRepository, Selectable
{
    public function add(User $user);
}
