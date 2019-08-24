<?php

namespace App\Entity\Tmdb;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\Tmdb\ProcessTypeEnum;

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
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=25)
     *
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(name="startedAt", type="datetime")
     *
     * @var \DateTime
     */
    private $startedAt;

    /**
     * @ORM\Column(name="endedAt", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $endedAt;

    /**
     * @ORM\Column(name="count", type="integer")
     *
     * @var int
     */
    private $count;

    /**
     * @ORM\Column(name="total", type="integer")
     *
     * @var int
     */
    private $total;

    public function __construct(string $type, int $total)
    {
        $this->setType($type);
        $this->startedAt = new \DateTime();
        $this->count = 0;
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        if (!ProcessTypeEnum::exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid type "%s"', $type));
        }

        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndedAt(): \DateTime
    {
        return $this->endedAt;
    }

    /**
     * @param \DateTime $endedAt
     */
    public function setEndedAt(\DateTime $endedAt)
    {
        $this->endedAt = $endedAt;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
    }
}
