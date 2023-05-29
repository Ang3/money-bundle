<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class CurrencyRegistryProvider
{
    private static ?CurrencyRegistry $currencyRegistry = null;

    public static function getRegistry(): CurrencyRegistry
    {
        if (!self::$currencyRegistry) {
            throw new \RuntimeException('No currency registry has been registered to the container.');
        }

        return self::$currencyRegistry;
    }

    public static function setContainer(ContainerInterface $container): void
    {
        /** @var CurrencyRegistry $currencyRegistry */
        $currencyRegistry = $container->get(CurrencyRegistry::class);
        self::$currencyRegistry = $currencyRegistry;
    }
}
