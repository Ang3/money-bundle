<?php

namespace Ang3\Bundle\MoneyBundle\Validator\Constraints;

use Ang3\Bundle\MoneyBundle\Entity\Price;
use Symfony\Component\Validator\Constraint;
use Ang3\Bundle\MoneyBundle\Validator\Constraints\Price as PriceConstraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PriceValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PriceConstraint) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof Price) {
            throw new UnexpectedTypeException($value, Price::class);
        }

        $amountViolations = $this->context->getValidator()->validate($value->getAmount(), $constraint->amountConstraints);

        if ($amountViolations->count() > 0) {
            $this->context
                ->buildViolation($constraint->invalidAmountMessage)
                ->addViolation();
        }

        foreach ($amountViolations as $violation) {
            $this->buildViolationAtPath($violation, 'amount');
        }

        $currencyViolations = $this->context->getValidator()->validate($value->getCurrency(), $constraint->currencyConstraints);

        if ($currencyViolations->count() > 0) {
            $this->context
                ->buildViolation($constraint->invalidCurrencyMessage)
                ->addViolation();
        }

        foreach ($currencyViolations as $violation) {
            $this->buildViolationAtPath($violation, 'currency');
        }
    }

    /**
     * @internal
     */
    private function buildViolationAtPath(ConstraintViolationInterface $violation, string $path): void
    {
        $this->context
            ->buildViolation($violation->getMessage())
            ->atPath($path)
            ->setParameters($violation->getParameters())
            ->setPlural($violation->getPlural())
            ->setCause($violation->getCause())
            ->setCode($violation->getCode())
            ->addViolation();
    }
}
