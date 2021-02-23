<?php

namespace App\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Maze\ActorFilmographyBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildFilmographyCommand extends AbstractBuildProcessCommand
{
    protected ActorFilmographyBuilder $filmographyBuilder;

    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        ActorFilmographyBuilder $filmographyBuilder
    ) {
        $this->filmographyBuilder = $filmographyBuilder;

        parent::__construct($buildProcessRepository, $entityManager, ProcessTypeEnum::FILMOGRAPHY);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Build filmography for referenced actors.');
    }

    /**
     * {@inheritdoc}
     */
    protected function executeProcess(InputInterface $input, OutputInterface $output): void
    {
        $this->filmographyBuilder->build();
    }
}
