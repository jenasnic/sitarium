<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class MovieNameConverter implements NameConverterInterface
{
    public function denormalize(string $propertyName)
    {
        if ('release_date' === $propertyName) {
            return 'releaseDate';
        }

        return $propertyName;
    }

    public function normalize(string $propertyName)
    {
        return $propertyName;
    }
}
