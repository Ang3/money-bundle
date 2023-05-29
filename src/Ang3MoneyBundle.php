<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle;

use Ang3\Bundle\MoneyBundle\Money\CurrencyRegistryProvider;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Ang3MoneyBundle extends Bundle
{
    public const DEFAULT_CURRENCY = 'EUR';

    public function boot(): void
    {
        parent::boot();

        // We provide the container to the currency provider registry to store the registry statically.
        CurrencyRegistryProvider::setContainer($this->container);
    }
}
