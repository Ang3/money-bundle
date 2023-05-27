<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Config;

final class MoneyConfig
{
    public function __construct(private readonly array $parameters)
    {
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
