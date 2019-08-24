<?php

namespace App\Command\Maze;

use App\Enum\Maze\ProcessTypeEnum;
use App\Repository\Maze\BuildProcessRepository;
use App\Service\Maze\MovieCastingBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCastingCommand extends Command
{
    /**
     * @var MovieCastingBuilder
     */
    protected $castingBuilder;

    /**
     * @var BuildProcessRepository
     */
    protected $buildProcessRepository;

    /**
     * @param MovieCastingBuilder $castingBuilder
     * @param BuildProcessRepository $buildProcessRepository
     */
    public function __construct(
        MovieCastingBuilder $castingBuilder,
        BuildProcessRepository $buildProcessRepository
    ) {
        $this->castingBuilder = $castingBuilder;
        $this->buildProcessRepository = $buildProcessRepository;

        parent::__construct();
    }

    /**
     * Command settings.
     */
    protected function configure()
    {
        $this
            ->setName('maze:build:casting')
            ->setDescription('Build casting for referenced movies.')
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

        $output->writeln('Build casting');
        try {
            $this->castingBuilder->build();
        } catch (\Exception $e) {
            $this->buildProcessRepository->stopPendingProcess(ProcessTypeEnum::CASTING);
        }
        $output->writeln('OK');
    }
}
