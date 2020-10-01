<?php

namespace App\Service\Tmdb\Synchronizer;

use App\Model\Tmdb\Search\DisplayableInterface;
use App\Service\Tmdb\TmdbApiService;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractSynchronizer implements SynchronizerInterface
{
    /**
     * @var TmdbApiService
     */
    protected $tmdbService;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param TmdbApiService $tmdbService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TmdbApiService $tmdbService,
        EntityManagerInterface $entityManager
    ) {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
    }

    public function synchronize(): int
    {
        $datas = $this->getAllData();

        $count = 0;
        foreach ($datas as $data) {
            $tmdbData = $this->tmdbService->getEntity($this->getEntityClass(), $data->getTmdbId());

            if ($this->synchronizeData($data, $tmdbData)) {
                $this->entityManager->persist($data);
                $count++;
            }

            // WARNING : wait between each TMDB request to not override request rate limit (4 per seconde)
            usleep(250001);
        }

        $this->entityManager->flush();

        return $count;
    }

    public function support($type): bool
    {
        return $this->getEntityClass() === $type;
    }

    /**
     * @return string
     */
    abstract protected function getEntityClass();

    /**
     * @return DisplayableInterface[]|array
     */
    abstract protected function getAllData();

    /**
     * @param DisplayableInterface $localData
     * @param DisplayableInterface $tmdbData
     * 
     * @return bool TRUE if local data is updated, FALSE either
     */
    abstract protected function synchronizeData($localData, $tmdbData): bool;
}
