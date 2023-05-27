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
class Price extends Constraint
{
    public string $invalidAmountMessage = 'The amount is not valid.';
    public string $invalidCurrencyMessage = 'The currency is not valid.';

    public function __construct(
        public array $amountConstraints = [],
        public array $currencyConstraints = [],
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
