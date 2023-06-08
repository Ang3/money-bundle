<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistry;
use Ang3\Bundle\MoneyBundle\Exchange\Exchanger;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set(CurrencyRegistry::class, CurrencyRegistry::class)
        ->factory([CurrencyRegistry::class, 'create'])
        ->args([
            param('ang3_money.config'),
        ])
        ->public()
        ->set(Exchanger::class, Exchanger::class)
        ->args([
            service(CurrencyRegistry::class),
        ])
        ->public()
    ;
};
