<?php

namespace App\Model\Tmdb;

class Actor
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $biography;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $profilePath;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $tmdbId
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime|null $birthday
     */
    public function setBirthday(?\DateTime $birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string|null
     */
    public function getProfilePath(): ?string
    {
        return $this->profilePath;
    }

    /**
     * @param string|null $pictureUrl
     */
    public function setProfilePath(?string $profilePath)
    {
        $this->profilePath = $profilePath;
    }

    public function getDisplayName()
    {
        return $this->name;
    }
}
