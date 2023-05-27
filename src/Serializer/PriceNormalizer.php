<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Serializer;

use Ang3\Bundle\MoneyBundle\Entity\Price;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PriceNormalizer implements NormalizerInterface
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Price;
    }

    /**
     * @param Price $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        return !$object->isEmpty() ? [
            'amount' => $object->getAmount(),
            'currency' => $object->getCurrency(),
            'literal' => $object->monetize()->formatTo($this->translator->getLocale()),
        ] : null;
    }
}
