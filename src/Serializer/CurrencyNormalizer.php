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
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CurrencyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(private readonly TranslatorInterface $translator)
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
            'name' => $this->translator->trans($object->getName()),
        ];
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return Currency::class === $type;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): Currency
    {
        if (\is_int($data) || \is_string($data)) {
            return Currency::of($data);
        }

        if (\is_array($data)) {
            return new Currency((string) $data['code'], (int) $data['numericCode'], (string) $data['name'], (int) $data['scale']);
        }

        throw new InvalidArgumentException(sprintf('Expected data of type int|string|array, got "%s".', get_debug_type($data)));
    }
}
