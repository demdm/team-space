<?php

namespace App\UserBundle\Service\User;

class CreateResponse
{
    /**
     * @var bool
     */
    public $isSuccess;

    /**
     * @var array
     */
    public $errorList;

    public static function success(): self
    {
        $self = new self();

        $self->isSuccess = true;

        return $self;
    }

    public static function failure(array $errorList): self
    {
        $self = new self();

        $self->isSuccess = false;
        $self->errorList = $errorList;

        return $self;
    }
}
