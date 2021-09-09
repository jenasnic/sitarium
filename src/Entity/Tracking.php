<?php

namespace App\Entity;

use App\Repository\TrackingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrackingRepository::class)
 */
class Tracking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $ip = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $trace = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $date = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $userAgent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getTrace(): ?string
    {
        return $this->trace;
    }

    public function setTrace(?string $trace): void
    {
        $this->trace = $trace;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }
}
