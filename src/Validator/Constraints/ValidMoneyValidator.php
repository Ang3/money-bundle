<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Validator\Constraints;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Brick\Money\Money;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidMoneyValidator extends ConstraintValidator
{
    public function __construct(private readonly MoneyConfig $moneyConfig)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidMoney) {
            return;
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!($value instanceof EmbeddedMoney || $value instanceof Money)) {
            throw new UnexpectedTypeException($value, sprintf('%s|%s', EmbeddedMoney::class, Money::class));
        }

        $notBlankConstraint = new NotBlank();
        $amount = $value instanceof EmbeddedMoney ? $value->getAmount() : $value->getMinorAmount()->toInt();

        if (null !== $amount) {
            $amountViolations = $this->context->getValidator()->validate($amount, $constraint->amountConstraints);

            foreach ($amountViolations as $violation) {
                $this->buildViolationAtPath($violation, 'amount');
            }
        } else {
            if ($constraint->required) {
                $this->context
                    ->buildViolation($notBlankConstraint->message)
                    ->atPath('amount')
                    ->addViolation()
                ;

                return;
            }
        }

        $currency = $value instanceof EmbeddedMoney ? $value->getCurrency() : $value->getCurrency()->getCurrencyCode();

        if ($currency) {
            if ($constraint->isoCurrency && !Currencies::exists($currency)) {
                $this->context
                    ->buildViolation($constraint->invalidISOCurrencyMessage)
                    ->atPath('currency')
                    ->addViolation()
                ;

                return;
            }

            $allowedCurrencies = $this->moneyConfig->getISOCurrencies();

            if ($allowedCurrencies && !\in_array($currency, $allowedCurrencies, true)) {
                $this->context
                    ->buildViolation($constraint->invalidCurrencyMessage)
                    ->atPath('currency')
                    ->setParameter('{{ value }}', $currency)
                    ->setParameter('{{ values }}', sprintf('"%s"', implode('", "', $allowedCurrencies)))
                    ->addViolation()
                ;

                return;
            }

            $currencyViolations = $this->context->getValidator()->validate($currency, $constraint->currencyConstraints);

            foreach ($currencyViolations as $violation) {
                $this->buildViolationAtPath($violation, 'currency');
            }
        } else {
            if ($constraint->required) {
                $this->context
                    ->buildViolation($notBlankConstraint->message)
                    ->atPath('currency')
                    ->addViolation()
                ;
            }
        }
    }

    /**
     * @internal
     */
    private function buildViolationAtPath(ConstraintViolationInterface $violation, string $path): void
    {
        $plural = $violation->getPlural();

        $violationBuilder = $this->context
            ->buildViolation((string) $violation->getMessage())
            ->atPath($path)
            ->setParameters($violation->getParameters())
            ->setCause($violation->getCause())
            ->setCode($violation->getCode())
        ;

        if ($plural) {
            $violationBuilder->setPlural($plural);
        }

        $violationBuilder->addViolation();
    }
}