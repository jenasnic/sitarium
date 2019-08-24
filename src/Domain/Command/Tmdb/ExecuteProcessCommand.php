<?php

namespace App\Domain\Command\Tmdb;

use App\Enum\Tmdb\ProcessTypeEnum;

class ExecuteProcessCommand
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array|null
     */
    protected $parameters;

    /**
     * @var array|null
     */
    protected $options;

    /**
     * @param string $type
     * @param array|null $parameters
     * @param array|null $options
     */
    public function __construct(string $type, ?array $parameters, ?array $options)
    {
        if (!ProcessTypeEnum::exists($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid type "%s"', $type));
        }

        $this->type = $type;
        $this->parameters = $parameters;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }
}
