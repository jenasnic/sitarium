<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use App\Model\Tmdb\Movie;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;

class MovieDenormalizer extends ObjectNormalizer implements ContextAwareDenormalizerInterface
{
    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory,
        MovieNameConverter $nameConverter,
        PropertyAccessorInterface $propertyAccessor,
        PropertyTypeExtractorInterface $propertyTypeExtractor
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }
    
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return parent::denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return Movie::class === $type;
    }
}
