<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ang3\Bundle\MoneyBundle\Form\Type\EmbeddedMoneyFormType;
use Ang3\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set('ang3_money.form.type.currency', CurrencyType::class)
        ->tag('form.type')
        ->set('ang3_money.form.type.embedded_money', EmbeddedMoneyFormType::class)
        ->tag('form.type')
        ->set('ang3_money.form.type.money', MoneyType::class)
        ->tag('form.type')
    ;
};
