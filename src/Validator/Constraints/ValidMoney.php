<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ValidMoney extends Constraint
{
    public string $invalidISOCurrencyMessage = 'You must set a valid ISO currency.';
    public string $invalidCurrencyMessage = 'The currency "{{ value }}" is not supported (Possible value: {{ values }}).';

    public function __construct(
        public array $amountConstraints = [],
        public array $currencyConstraints = [],
        public bool $isoCurrency = true,
        public bool $required = false,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
