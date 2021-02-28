<?php

namespace App\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Maze\MovieCastingBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCastingCommand extends AbstractBuildProcessCommand
{
    protected MovieCastingBuilder $castingBuilder;

    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        MovieCastingBuilder $castingBuilder
    ) {
        $this->castingBuilder = $castingBuilder;

        parent::__construct($buildProcessRepository, $entityManager, ProcessTypeEnum::CASTING);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Build casting for referenced movies.');
    }

    /**
     * {@inheritdoc}
     */
    protected function executeProcess(InputInterface $input, OutputInterface $output): void
    {
        $this->castingBuilder->build();
    }
}
