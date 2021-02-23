<?php

namespace App\Service\Handler\Tmdb;

use App\Domain\Command\Tmdb\ExecuteProcessCommand;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Allows to execute Symfony command for TMDB process.
 */
class ExecuteProcessHandler
{
    protected string $rootDir;

    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function handle(ExecuteProcessCommand $command): void
    {
        $symfonyCommand = sprintf('tmdb:build:%s', $command->getType());

        if (null !== $command->getParameters()) {
            $symfonyCommand = sprintf('%s %s', $symfonyCommand, implode(' ', $command->getParameters()));
        }

        if (null !== $command->getOptions()) {
            $options = array_map(function ($option, $value) {
                return empty($value) ? sprintf('--%s', $option) : sprintf('--%s=%s', $option, $value);
            }, array_keys($command->getOptions()), $command->getOptions());

            $symfonyCommand = sprintf('%s %s', $symfonyCommand, implode(' ', $options));
        }

        $phpBinaryFinder = new PhpExecutableFinder();
        $phpBinaryPath = $phpBinaryFinder->find();

        $process = Process::fromShellCommandline(sprintf('nohup %s bin/console %s &', $phpBinaryPath, $symfonyCommand), $this->rootDir);
        $process->start();
    }
}
