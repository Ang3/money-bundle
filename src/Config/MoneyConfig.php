<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Config;

use Ang3\Bundle\MoneyBundle\Entity\Price;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class MoneyConfig
{
    public function __construct(#[Autowire('%ang3_money.config%')] private readonly array $parameters)
    {
        // We get money parameters, so we hydrate price default currency right now, it's great.
        Price::setDefaultCurrency($this->getDefaultCurrency());
    }

    public function getCurrencies(): array
    {
        return $this->parameters['currencies'];
    }

    public function getDefaultCurrency(): string
    {
        return $this->parameters['default_currency'];
    }
}
