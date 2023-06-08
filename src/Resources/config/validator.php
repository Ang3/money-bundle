<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ang3\Bundle\MoneyBundle\Validator\Constraints\ValidCurrencyValidator;
use Ang3\Bundle\MoneyBundle\Validator\Constraints\ValidMoneyValidator;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
        ->set('ang3_money.validator.valid_currency', ValidCurrencyValidator::class)
        ->tag('validator.constraint_validator', ['alias' => ValidCurrencyValidator::class])
        ->set('ang3_money.validator.valid_money', ValidMoneyValidator::class)
        ->tag('validator.constraint_validator', ['alias' => ValidMoneyValidator::class])
    ;
};
