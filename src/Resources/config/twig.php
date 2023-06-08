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
use Ang3\Bundle\MoneyBundle\Twig\MoneyExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('ang3_money.twig_extension', MoneyExtension::class)
        ->args([
            service(CurrencyRegistry::class),
            param('ang3_money.default_locale'),
            service('translator')->nullOnInvalid(),
        ])
        ->tag('twig.extension')
    ;
};
