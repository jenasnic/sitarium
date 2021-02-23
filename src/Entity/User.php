<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="firstname", type="string", length=55)
     */
    private ?string $firstname = null;

    /**
     * @ORM\Column(name="lastname", type="string", length=55)
     */
    private ?string $lastname = null;

    /**
     * @ORM\Column(name="email", type="string", unique=true, length=255)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(name="username", type="string", unique=true, length=55)
     */
    private ?string $username = null;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private ?string $password = null;

    /**
     * @ORM\Column(name="roles", type="simple_array")
     */
    private array $roles;

    public function __construct()
    {
        $this->roles = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, function ($internalRole) use ($role) {
            return $internalRole === $role;
        });
    }

    public function getDisplayName(): string
    {
        return sprintf('%s %s', ucfirst($this->firstname), strtoupper($this->lastname));
    }
}
