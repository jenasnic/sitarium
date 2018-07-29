<?php

namespace App\Model\Maze;

class MazeGraphItem
{
    /**
     * @var mixed
     */
    private $item;

    /**
     * @var array
     */
    private $linkedItems;

    /**
     * @var int
     */
    private $bestPathPosition = 0;

    /**
     * @var int
     */
    private $bestPathSize = 0;

    /**
     * @param mixed $item
     */
    public function __construct($item)
    {
        $this->item = $item;
        $this->linkedItems = [];
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }

    /**
     * @return array
     */
    public function getLinkedItems(): array
    {
        return $this->linkedItems;
    }

    /**
     * @param MazeGraphItem $linkedItem
     *
     * @return MazeGraphItem
     */
    public function addLinkedItem(MazeGraphItem $linkedItem): self
    {
        $this->linkedItems[] = $linkedItem;

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
