<?php

namespace App\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private const AUTH_STATUS_PENDING = 0;
    private const AUTH_STATUS_ACTIVE = 1;
    private const AUTH_STATUS_BANNED = 2;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string|null
     */
    private $authToken;

    /**
     * @var string|null
     */
    private $authRefreshToken;

    /**
     * @var int
     */
    private $authStatus;

    /**
     * @var bool
     */
    private $isEmailConfirmed;

    /**
     * Constructor.
     */
    private function __construct()
    {
    }

    public static function create(
        string $id,
        string $email,
        string $password
    ) : self {
        $self = new self();

        $self->id = $id;
        $self->email = $email;
        $self->password = $password;

        $self->authStatus = self::AUTH_STATUS_PENDING;
        $self->isEmailConfirmed = false;

        return $self;
    }

    public function updateEmail(string $email)
    {
        $this->email = $email;
    }

    public function updatePassword(string $password)
    {
        $this->password = $password;
    }

    public function updateAuthToken(string $authToken)
    {
        $this->authToken = $authToken;
    }

    public function clearAuthTokens()
    {
        $this->authToken = null;
        $this->authRefreshToken = null;
    }

    public function makeActive()
    {
        $this->authStatus = self::AUTH_STATUS_ACTIVE;
    }

    public function makePending()
    {
        $this->authStatus = self::AUTH_STATUS_PENDING;
    }

    public function makeBanned()
    {
        $this->authStatus = self::AUTH_STATUS_BANNED;
    }

    public function makeEmailConfirmed()
    {
        $this->isEmailConfirmed = true;
    }

    public function makeNotEmailConfirmed()
    {
        $this->isEmailConfirmed = false;
    }

    public function isActive(): bool
    {
        return $this->authStatus === self::AUTH_STATUS_ACTIVE;
    }

    public function isBanned(): bool
    {
        return $this->authStatus === self::AUTH_STATUS_BANNED;
    }

    public function isPending(): bool
    {
        return $this->authStatus === self::AUTH_STATUS_PENDING;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @return string|null
     */
    public function getAuthRefreshToken(): ?string
    {
        return $this->authRefreshToken;
    }

    /**
     * @return bool
     */
    public function isEmailConfirmed(): bool
    {
        return $this->isEmailConfirmed;
    }

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }
}
