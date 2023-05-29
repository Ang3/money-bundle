<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Money;

use Ang3\Bundle\MoneyBundle\Enum\RoundingMode;
use Brick\Math\BigNumber;
use Brick\Money\AbstractMoney;
use Brick\Money\Context;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

class RationalMoneyBuilder implements MoneyOperationInterface
{
    protected RationalMoney $rationalMoney;
    protected RoundingMode $roundingMode = RoundingMode::Down;
    protected Context $context;

    public function __construct(private readonly Money $money, ?RoundingMode $roundingMode = null)
    {
        $this->initialize();

        if ($roundingMode) {
            $this->setRoundingMode($roundingMode);
        }
    }

    public static function create(Money $money, ?RoundingMode $roundingMode = null): self
    {
        return new self($money, $roundingMode);
    }

    public function initialize(): void
    {
        $this->rationalMoney = $this->money->toRational();
        $this->context = $this->money->getContext();
    }

    public function getRoundingMode(): RoundingMode
    {
        return $this->roundingMode;
    }

    public function setRoundingMode(RoundingMode $roundingMode): self
    {
        $this->roundingMode = $roundingMode;

        return $this;
    }

    public function plus(mixed $that, ?RoundingMode $roundingMode = null): static
    {
        $this->rationalMoney = $this->rationalMoney->plus($this->getThat($that));

        return $this;
    }

    public function minus(mixed $that, ?RoundingMode $roundingMode = null): static
    {
        $this->rationalMoney = $this->rationalMoney->minus($this->getThat($that));

        return $this;
    }

    public function multipliedBy(mixed $that, ?RoundingMode $roundingMode = null): static
    {
        $this->rationalMoney = $this->rationalMoney->multipliedBy($this->getValue($that));

        return $this;
    }

    public function dividedBy(mixed $that, ?RoundingMode $roundingMode = null): static
    {
        $this->rationalMoney = $this->rationalMoney->dividedBy($this->getValue($that));

        return $this;
    }

    public function build(?RoundingMode $roundingMode = null): Money
    {
        return $this->rationalMoney->to($this->context, ($roundingMode ?: $this->roundingMode)->value);
    }

    /**
     * @internal
     */
    private function getThat(mixed $that): AbstractMoney|BigNumber|int|float|string
    {
        $that = $that instanceof MoneyAwareInterface ? $that->getMoney() : $that;

        if (!($that instanceof AbstractMoney)
            && !($that instanceof BigNumber)
            && !\is_int($that)
            && !\is_float($that)
            && !\is_string($that)
        ) {
            throw new \InvalidArgumentException(sprintf('Expected subject of type "%s|%s|%s|int|float|string", got "%s".', MoneyAwareInterface::class, BigNumber::class, AbstractMoney::class, get_debug_type($that)));
        }

        return $that;
    }

    /**
     * @internal
     */
    private function getValue(mixed $that): BigNumber|int|float|string
    {
        $value = $that instanceof MoneyAwareInterface ? $that->getMoney() : $that;
        $value = $value instanceof AbstractMoney ? $value->getAmount() : $value;

        if (!($value instanceof BigNumber)
            && !\is_int($value)
            && !\is_float($value)
            && !\is_string($value)
        ) {
            throw new \InvalidArgumentException(sprintf('Expected subject of type "%s|%s|%s|int|float|string", got "%s".', MoneyAwareInterface::class, BigNumber::class, AbstractMoney::class, get_debug_type($value)));
        }

        return $value;
    }
}
