<?php

namespace App\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
use App\Service\Quiz\TmdbLinkBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;

class BuildTmdbLinkCommand extends AbstractBuildProcessCommand
{
    /**
     * @var TmdbLinkBuilder
     */
    protected $tmdbLinkBuilder;

    /**
     * @param BuildProcessRepository $buildProcessRepository
     * @param EntityManagerInterface $entityManager
     * @param TmdbLinkBuilder $castingBuilder
     */
    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        TmdbLinkBuilder $tmdbLinkBuilder
    ) {
        $this->tmdbLinkBuilder = $tmdbLinkBuilder;

        parent::__construct($buildProcessRepository, $entityManager, ProcessTypeEnum::QUIZ_LINK);
    }

    /**
     * Command settings.
     */
    protected function configure()
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
    protected function executeProcess(InputInterface $input, OutputInterface $output)
    {
        $quizId = $input->getArgument('quizId');
        $this->tmdbLinkBuilder->build($quizId);
    }
}
