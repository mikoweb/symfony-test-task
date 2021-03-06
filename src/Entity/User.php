<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 *
 * @UniqueEntity(fields={"username"}, errorPath="username")
 * @UniqueEntity(fields={"apiKey"}, errorPath="apiKey")
 */
final class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", options={"default": "[]"})
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(groups={"registration", "api_create"})
     * @Assert\Length(min="6")
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", unique=true)
     *
     * @Assert\NotBlank(groups={"registration", "api_create"})
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="api_key", type="string", nullable=true, unique=true)
     */
    private $apiKey;

    public function __construct()
    {
        $this->roles = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return array_unique(array_merge($this->roles, ['ROLE_USER']));
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
}
