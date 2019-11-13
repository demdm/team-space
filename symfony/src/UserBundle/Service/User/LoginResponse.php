<?php

namespace App\UserBundle\Service\User;

class LoginResponse
{
    /**
     * @var bool
     */
    public $isSuccess;

    /**
     * @var array
     */
    public $errorList;

    /**
     * @var string
     */
    public $token;

    public static function success(string $token): self
    {
        $self = new self();

        $self->isSuccess = true;
        $self->token = $token;

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
