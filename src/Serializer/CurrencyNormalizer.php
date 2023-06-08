<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Serializer;

use Brick\Money\Currency;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CurrencyNormalizer implements NormalizerInterface
{
    public function __construct(private readonly ?TranslatorInterface $translator = null)
    {
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Currency;
    }

    /**
     * @param Currency $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        return [
            'code' => $object->getCurrencyCode(),
            'numericCode' => $object->getNumericCode(),
            'scale' => $object->getDefaultFractionDigits(),
            'name' => $this->translator ? $this->translator->trans($object->getName()) : $object->getName(),
        ];
    }
}
