<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\DataTransformer;

use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistryProvider;
use Brick\Money\Currency;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transforms between Currency objects and number value.
 */
class CurrencyToStringTransformer implements DataTransformerInterface
{
    /**
     * Transforms a Currency object to a string (ISO currency code).
     *
     * @param Currency|null $value
     */
    public function transform($value): ?string
    {
        if (null === $value) {
            return null;
        }

        return $value->getCurrencyCode();
    }

    /**
     * Transforms a string value to a Currency object.
     *
     * @param string|null $value
     */
    public function reverseTransform($value): ?Currency
    {
        return null !== $value ? CurrencyRegistryProvider::getRegistry()->get($value) : null;
    }
}
