<?php

namespace App\Command\Maze;

use App\Enum\Maze\ProcessTypeEnum;
use App\Repository\Maze\BuildProcessRepository;
use App\Service\Maze\ActorFilmographyBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildFilmographyCommand extends Command
{
    /**
     * @var ActorFilmographyBuilder
     */
    protected $filmographyBuilder;

    /**
     * @var BuildProcessRepository
     */
    protected $buildProcessRepository;

    /**
     * @param ActorFilmographyBuilder $filmographyBuilder
     * @param BuildProcessRepository $buildProcessRepository
     */
    public function __construct(
        ActorFilmographyBuilder $filmographyBuilder,
        BuildProcessRepository $buildProcessRepository
    ) {
        $this->filmographyBuilder = $filmographyBuilder;
        $this->buildProcessRepository = $buildProcessRepository;

        parent::__construct();
    }

    /**
     * Command settings.
     */
    protected function configure()
    {
        $this
            ->setName('maze:build:filmography')
            ->setDescription('Build filmography for referenced actors.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->buildProcessRepository->isProcessPending()) {
            $output->writeln('Process already pending...');

            return;
        }

        $output->writeln('Build filmography');
        try {
            $this->filmographyBuilder->build();
        } catch (\Exception $e) {
            $this->buildProcessRepository->stopPendingProcess(ProcessTypeEnum::FILMOGRAPHY);
        }
        $output->writeln('OK');
    }
}
