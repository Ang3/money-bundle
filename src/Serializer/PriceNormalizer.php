<?php

namespace Ang3\Bundle\MoneyBundle\Serializer;

use Ang3\Bundle\MoneyBundle\Entity\Price;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PriceNormalizer implements NormalizerInterface
{
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Price;
    }

    /**
     * @param Price $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        return [
            'amount' => $object->getAmount(),
            'currency' => $object->getCurrency(),
        ];
    }
}