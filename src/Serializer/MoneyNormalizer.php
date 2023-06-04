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
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MoneyNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public const LOCALE_KEY = 'locale';

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Money;
    }

    /**
     * @param Money $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): ?array
    {
        $locale = $context[self::LOCALE_KEY] ?? $this->translator->getLocale();

        return [
            'amount' => (string) $object->getAmount(),
            'minorAmount' => $object->getMinorAmount()->toInt(),
            'literal' => $object->formatTo($locale),
            'currency' => $this->normalizer->normalize($object->getCurrency()),
        ];
    }
}
