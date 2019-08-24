<?php

namespace App\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use App\Repository\Tmdb\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractBuildProcessCommand extends Command
{
    /**
     * @var BuildProcessRepository
     */
    protected $buildProcessRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $processType;

    /**
     * @param BuildProcessRepository $buildProcessRepository
     * @param EntityManagerInterface $entityManager
     * @param string $processType
     */
    public function __construct(
        BuildProcessRepository $buildProcessRepository,
        EntityManagerInterface $entityManager,
        string $processType
    ) {
        if (!ProcessTypeEnum::exists($processType)) {
            throw new \InvalidArgumentException(sprintf('Invalid type "%s"', $processType));
        }
        $this->buildProcessRepository = $buildProcessRepository;
        $this->entityManager = $entityManager;
        $this->processType = $processType;

        parent::__construct();
    }

    abstract protected function executeProcess(InputInterface $input, OutputInterface $output);

    /**
     * Command settings.
     */
    protected function configure()
    {
        $this
            ->setName(sprintf('tmdb:build:%s', $this->processType))
            ->setDescription(sprintf('Build process for %s.', $this->processType))
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

        $output->writeln(sprintf('Build process %s', $this->processType));
        try {
            $this->executeProcess($input, $output);
        } catch (\Exception $e) {
            $this->stopPendingProcess();
        }
        $output->writeln('OK');
    }

    private function stopPendingProcess()
    {
        $pendingProcess = $this->buildProcessRepository->findPendingProcessByType($this->processType);

        if (null !== $pendingProcess) {
            $pendingProcess->setEndedAt(new \DateTime());
            $this->entityManager->flush();
        }
    }
}
