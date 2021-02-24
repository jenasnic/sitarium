<?php

namespace App\Domain\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use InvalidArgumentException;

class ExecuteProcessCommand
{
    protected string $type;

    protected ?array $parameters;

    protected ?array $options;

    public function __construct(string $type, ?array $parameters, ?array $options)
    {
        if (!ProcessTypeEnum::exists($type)) {
            throw new InvalidArgumentException(sprintf('Invalid type "%s"', $type));
        }

        $this->type = $type;
        $this->parameters = $parameters;
        $this->options = $options;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }
}
