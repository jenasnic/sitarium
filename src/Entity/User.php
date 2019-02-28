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
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=55)
     *
     * @var string
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=55)
     *
     * @var string
     */
    private $lastname;

    /**
     * @ORM\Column(name="email", type="string", unique=true, length=255)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="username", type="string", unique=true, length=55)
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="roles", type="simple_array")
     *
     * @var array
     */
    private $roles;

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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string $role
     */
    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @param string $role
     */
    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, function ($internalRole) use ($role) {
            return $internalRole === $role;
        });
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return sprintf('%s %s', ucfirst($this->firstname), strtoupper($this->lastname));
    }
}
