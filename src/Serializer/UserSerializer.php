<?php

namespace App\Serializer;

use App\Entity\User;

final class UserSerializer implements SerializerInterface
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): array
    {
        return [
            'id' => $this->user->getId(),
            'username' => $this->user->getUsername(),
            'roles' => $this->user->getRoles(),
        ];
    }
}
