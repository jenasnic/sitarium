<?php

namespace App\Model\Tmdb;

use DateTime;

class Actor
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $character = null;

    private ?float $popularity = null;

    private ?string $biography = null;

    private ?DateTime $birthday = null;

    private ?string $profilePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCharacter(): ?string
    {
        return $this->character;
    }

    public function setCharacter(?string $character): void
    {
        $this->character = $character;
    }

    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    public function setPopularity(?float $popularity): void
    {
        $this->popularity = $popularity;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): void
    {
        $this->biography = $biography;
    }

    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getProfilePath(): ?string
    {
        return $this->profilePath;
    }

    public function setProfilePath(?string $profilePath): void
    {
        $this->profilePath = $profilePath;
    }
}
