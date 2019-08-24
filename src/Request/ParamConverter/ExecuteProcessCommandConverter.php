<?php

namespace App\Request\ParamConverter;

use App\Domain\Command\Tmdb\ExecuteProcessCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Allows to create ExecuteProcessCommand from request with :
 *  - type => type of process to launch
 *  - parameters => parameters to send to Symfony command (optionnal)
 *  - options => options to send to Symfony command as array key/value where key is the name of option (optionnal).
 */
class ExecuteProcessCommandConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $type = $request->request->get('type');
        $parameters = $request->request->get('parameters', null);
        $options = $request->request->get('options', null);

        if (null !== $parameters) {
            $parameters = json_decode($parameters, true);
        }

        if (null !== $options) {
            $options = json_decode($options, true);
        }

        $request->attributes->set(
            $configuration->getName(),
            new ExecuteProcessCommand($type, $parameters, $options)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return ExecuteProcessCommand::class === $configuration->getClass();
    }
}
