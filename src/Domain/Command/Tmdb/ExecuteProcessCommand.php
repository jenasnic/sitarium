<?php

namespace App\Domain\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;
use InvalidArgumentException;

class ExecuteProcessCommand
{
    protected string $type;

    /**
     * @var array<mixed>|null
     */
    protected ?array $parameters;

    /**
     * @var array<mixed>|null
     */
    protected ?array $options;

    /**
     * @param array<mixed>|null $parameters
     * @param array<mixed>|null $options
     */
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

    /**
     * @return array<mixed>|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @return array<mixed>|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }
}
