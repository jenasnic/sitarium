<?php

namespace App\Model\Maze;

use App\Entity\Maze\Actor;

class ActorGraphItem
{
    /**
     * @var Actor
     */
    private $actor;

    /**
     * @var array
     */
    private $linkedActors;

    /**
     * @var int
     */
    private $bestPathPosition = 0;

    /**
     * @var int
     */
    private $bestPathSize = 0;

    /**
     * @param Actor $actor
     */
    public function __construct(Actor $actor)
    {
        $this->actor = $actor;
        $this->linkedActors = [];
    }

    /**
     * @return Actor
     */
    public function getActor(): Actor
    {
        return $this->actor;
    }

    /**
     * @param Actor $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @return array
     */
    public function getLinkedActors(): array
    {
        return $this->linkedActors;
    }

    /**
     * @param ActorGraphItem $linkedActor
     *
     * @return self
     */
    public function addLinkedActor(ActorGraphItem $linkedActor): self
    {
        $this->linkedActors[] = $linkedActor;

        return $this;
    }

    /**
     * @return int
     */
    public function getBestPathPosition(): int
    {
        return $this->bestPathPosition;
    }

    /**
     * @param int $bestPathPosition
     */
    public function setBestPathPosition(int $bestPathPosition)
    {
        $this->bestPathPosition = $bestPathPosition;
    }

    /**
     * @return int
     */
    public function getBestPathSize(): int
    {
        return $this->bestPathSize;
    }

    /**
     * @param int $bestPathSize
     */
    public function setBestPathSize($bestPathSize)
    {
        $this->bestPathSize = $bestPathSize;
    }
}
