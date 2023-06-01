<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Serializer;

use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EmbeddedMoneyNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof EmbeddedMoney;
    }

    /**
     * @param EmbeddedMoney $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        return (array) $this->normalizer->normalize($object->getMoney(), $format, $context);
    }
}
