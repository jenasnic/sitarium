<?php

namespace App\Service\Handler\Tmdb;

use App\Domain\Command\Tmdb\ExecuteProcessCommand;
use Symfony\Component\Process\Process;

/**
 * Allows to execute Symfony command for TMDB process.
 */
class ExecuteProcessHandler
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @param string $rootDir
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @param ExecuteProcessCommand $command
     */
    public function handle(ExecuteProcessCommand $command)
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

        $process = Process::fromShellCommandline(sprintf('nohup bin/console %s &', $symfonyCommand), $this->rootDir);
        $process->start();
    }
}
