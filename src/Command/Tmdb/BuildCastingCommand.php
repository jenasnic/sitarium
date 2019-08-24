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
    /**
     * @var MovieCastingBuilder
     */
    protected $castingBuilder;

    /**
     * @param BuildProcessRepository $buildProcessRepository
     * @param EntityManagerInterface $entityManager
     * @param MovieCastingBuilder $castingBuilder
     */
    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        MovieCastingBuilder $castingBuilder
    ) {
        $this->castingBuilder = $castingBuilder;
        $this->buildProcessRepository = $buildProcessRepository;

        parent::__construct($buildProcessRepository, $entityManager, ProcessTypeEnum::CASTING);
    }

    /**
     * Command settings.
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Build casting for referenced movies.');
    }

    /**
     * {@inheritdoc}
     */
    protected function executeProcess(InputInterface $input, OutputInterface $output)
    {
        $this->castingBuilder->build();
    }
}
