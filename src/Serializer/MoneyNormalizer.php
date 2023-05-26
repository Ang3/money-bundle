<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Serializer;

use Brick\Money\Money;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MoneyNormalizer implements NormalizerInterface
{
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Money;
    }

    /**
     * @param Money $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        return [
            'amount' => $object->getMinorAmount()->toInt(),
            'currency' => $object->getCurrency()->getCurrencyCode(),
        ];
    }
}
