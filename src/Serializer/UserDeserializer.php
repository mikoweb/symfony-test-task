<?php

namespace App\Serializer;

use App\Entity\User;

final class UserDeserializer implements DeserializerInterface
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $data): void
    {
        $this->user->setUsername($data['username'] ?? null);
        $this->user->setPlainPassword($data['password'] ?? null);
        $this->user->setRoles($data['roles'] ?? []);
    }

    public function update(array $data): void
    {
        $this->user->setUsername($data['username'] ?? $this->user->getUsername());
        $this->user->setPlainPassword($data['password'] ?? $this->user->getPassword());
        $this->user->setRoles($data['roles'] ?? $this->user->getRoles());
    }
}
