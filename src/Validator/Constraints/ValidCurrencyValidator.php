<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Validator\Constraints;

use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidCurrencyValidator extends ConstraintValidator
{
    public function __construct(private readonly CurrencyRegistry $currencyRegistry)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidCurrency) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_scalar($value)) {
            throw new UnexpectedTypeException($value, 'scalar');
        }

        $value = (string) $value;

        if (!$this->currencyRegistry->has($value)) {
            $this->context
                ->buildViolation($constraint->invalidMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation()
            ;
        }
    }
}
