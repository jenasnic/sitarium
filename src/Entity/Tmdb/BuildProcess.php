<?php

namespace App\Entity\Tmdb;

use App\Enum\Tmdb\ProcessStatusEnum;
use App\Enum\Tmdb\ProcessTypeEnum;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Table(name="tmdb_build_process")
 * @ORM\Entity(repositoryClass="App\Repository\Tmdb\BuildProcessRepository")
 */
class BuildProcess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="type", type="string", length=25)
     */
    private string $type;

    /**
     * @ORM\Column(name="status", type="string", length=25)
     */
    private string $status;

    /**
     * @ORM\Column(name="options", type="text", nullable=true)
     */
    private ?string $options;

    /**
     * @ORM\Column(name="startedAt", type="datetime")
     */
    private ?DateTime $startedAt = null;

    /**
     * @ORM\Column(name="endedAt", type="datetime", nullable=true)
     */
    private ?DateTime $endedAt = null;

    /**
     * @ORM\Column(name="count", type="integer")
     */
    private int $count;

    /**
     * @ORM\Column(name="total", type="integer")
     */
    private int $total;

    public function __construct(string $type, string $status, int $total, ?string $options = null)
    {
        $this->setType($type);
        $this->setStatus($status);
        $this->setOptions($options);
        $this->startedAt = new DateTime();
        $this->count = 0;
        $this->total = $total;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (!ProcessTypeEnum::exists($type)) {
            throw new InvalidArgumentException(sprintf('Invalid type "%s"', $type));
        }

        $this->type = $type;
    }

    public function getStatus(): string
    {
        return $this->type;
    }

    public function setStatus(string $status): void
    {
        if (!ProcessStatusEnum::exists($status)) {
            throw new InvalidArgumentException(sprintf('Invalid status "%s"', $status));
        }

        $this->status = $status;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): void
    {
        $this->options = $options;
    }

    public function getStartedAt(): ?DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(DateTime $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }

    public function setEndedAt(?DateTime $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
