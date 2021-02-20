<?php

namespace App\Command\Tmdb;

use App\Entity\Maze\Actor;
use App\Entity\Maze\CastingActor;
use App\Entity\Maze\FilmographyMovie;
use App\Entity\Maze\Movie;
use App\Entity\Tagline\Movie as TaglineMovie;
use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Tmdb\TmdbDataSynchronizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SynchronizeDataCommand extends AbstractBuildProcessCommand
{
    /**
     * @var TmdbDataSynchronizer
     */
    protected $tmdbDataSynchronizer;

    /**
     * @param BuildProcessRepository $buildProcessRepository
     * @param EntityManagerInterface $entityManager
     * @param TmdbDataSynchronizer $tmdbDataSynchronizer
     */
    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        TmdbDataSynchronizer $tmdbDataSynchronizer
    ) {
        $this->tmdbDataSynchronizer = $tmdbDataSynchronizer;

        parent::__construct($buildProcessRepository, $entityManager, ProcessTypeEnum::SYNCHRONIZATION);
    }
    
    /**
     * Command settings.
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Synchronize local data with TMDB data.');
    }

    /**
     * {@inheritdoc}
     */
    protected function executeProcess(InputInterface $input, OutputInterface $output)
    {
        $entities = [
            Actor::class,
            FilmographyMovie::class,
            Movie::class,
            CastingActor::class,
            TaglineMovie::class,
        ];

        foreach ($entities as $entity) {
            $this->tmdbDataSynchronizer->synchronize($entity);
        }
    }
}
