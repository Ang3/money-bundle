<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ang3\Bundle\MoneyBundle\Serializer\CurrencyNormalizer;
use Ang3\Bundle\MoneyBundle\Serializer\EmbeddedMoneyNormalizer;
use Ang3\Bundle\MoneyBundle\Serializer\MoneyNormalizer;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set('ang3_money.serializer.currency_normalizer', CurrencyNormalizer::class)
        ->args([
            service('translator')->nullOnInvalid(),
        ])
        ->tag('serializer.normalizer')
        ->set('ang3_money.serializer.embedded_money_normalizer', EmbeddedMoneyNormalizer::class)
        ->tag('serializer.normalizer')
        ->set('ang3_money.serializer.money_normalizer', MoneyNormalizer::class)
        ->args([
            service('translator')->nullOnInvalid(),
        ])
        ->tag('serializer.normalizer')
    ;
};
