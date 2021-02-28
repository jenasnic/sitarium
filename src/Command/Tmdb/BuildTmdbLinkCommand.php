<?php

namespace App\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Quiz\TmdbLinkBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildTmdbLinkCommand extends AbstractBuildProcessCommand
{
    protected TmdbLinkBuilder $tmdbLinkBuilder;

    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        TmdbLinkBuilder $tmdbLinkBuilder
    ) {
        $this->tmdbLinkBuilder = $tmdbLinkBuilder;

        parent::__construct($buildProcessRepository, $entityManager, ProcessTypeEnum::QUIZ_LINK);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Build TMDB link for responses of quiz.')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('quizId', InputArgument::REQUIRED, 'Identifier of quiz we want to link responses with TDMB.'),
                ])
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function executeProcess(InputInterface $input, OutputInterface $output): void
    {
        $quizId = intval($input->getArgument('quizId'));
        $this->tmdbLinkBuilder->build($quizId);
    }
}
