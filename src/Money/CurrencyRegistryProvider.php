<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Symfony\Contracts\Service\Attribute\Required;

final class CurrencyRegistryProvider
{
    private static CurrencyRegistry $currencyRegistry;

    private function __construct()
    {
    }

    public static function getRegistry(): CurrencyRegistry
    {
        return self::$currencyRegistry;
    }

    #[Required]
    public static function setRegistry(CurrencyRegistry $globalCurrencyRegistry): void
    {
        self::$currencyRegistry = $globalCurrencyRegistry;
    }
}