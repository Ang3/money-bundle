<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Brick\Money\Contracts\Monetizable;
use Brick\Money\Contracts\MoneyInterface;
use Brick\Money\Currency;
use Symfony\Component\Intl\Currencies;

class MoneyDecorator implements MoneyDecoratorInterface
{
    public const ROUNDING_MODES = [
        RoundingMode::UNNECESSARY,
        RoundingMode::UP,
        RoundingMode::DOWN,
        RoundingMode::CEILING,
        RoundingMode::FLOOR,
        RoundingMode::HALF_UP,
        RoundingMode::HALF_DOWN,
        RoundingMode::HALF_CEILING,
        RoundingMode::HALF_FLOOR,
        RoundingMode::HALF_EVEN,
    ];

    private Monetizable $decorated;

    public function __construct(Monetizable $decorated, private ?int $roundingMode = null)
    {
        $this->setDecorated($decorated);
    }

    public function plus(MoneyInterface|BigNumber|int|float|string $that, int $roundingMode = null): self
    {
        return $this->newInstance($this->getDecorated()->plus($this->getOperationValue($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function minus(MoneyInterface|BigNumber|int|float|string $that, int $roundingMode = null): self
    {
        return $this->newInstance($this->getDecorated()->minus($this->getOperationValue($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function multipliedBy(BigNumber|int|float|string $that, int $roundingMode = null): self
    {
        return $this->newInstance($this->getDecorated()->multipliedBy($this->getAmountOf($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function dividedBy(BigNumber|int|float|string $that, int $roundingMode = null): self
    {
        return $this->newInstance($this->getDecorated()->dividedBy($this->getAmountOf($that), $this->resolveRoundingMode($roundingMode)));
    }

    public function abs(): self
    {
        return $this->newInstance($this->getDecorated()->abs());
    }

    public function negated(): self
    {
        return $this->newInstance($this->getDecorated()->negated());
    }

    public function duplicate(BigNumber|int|float|string|null $amount = null): self
    {
        $duplicate = $this->multipliedBy(1);

        return $this->newInstance(null !== $amount ? $duplicate->setAmount($amount) : $duplicate);
    }

    public function toZero(): self
    {
        return $this->setAmount(0);
    }

    public function isZero(): bool
    {
        return $this->getDecorated()->isZero();
    }

    public function isPositive(): bool
    {
        return $this->getDecorated()->isPositive();
    }

    public function isPositiveOrZero(): bool
    {
        return $this->getDecorated()->isPositiveOrZero();
    }

    public function isNegative(): bool
    {
        return $this->getDecorated()->isNegative();
    }

    public function isNegativeOrZero(): bool
    {
        return $this->getDecorated()->isNegativeOrZero();
    }

    public function compareTo(MoneyInterface|BigNumber|int|float|string $that): int
    {
        return $this->getDecorated()->getAmount()->compareTo($this->getAmountOf($that));
    }

    public function isEqualTo(MoneyInterface|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isEqualTo($this->getOperationValue($that));
    }

    public function isLessThan(MoneyInterface|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isLessThan($this->getOperationValue($that));
    }

    public function isLessThanOrEqualTo(MoneyInterface|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isLessThanOrEqualTo($this->getOperationValue($that));
    }

    public function isGreaterThan(MoneyInterface|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isGreaterThan($this->getOperationValue($that));
    }

    public function isGreaterThanOrEqualTo(MoneyInterface|BigNumber|int|float|string $that): bool
    {
        return $this->getDecorated()->isGreaterThanOrEqualTo($this->getOperationValue($that));
    }

    public function isAmountAndCurrencyEqualTo(MoneyInterface $that): bool
    {
        return $this->getDecorated()->getAmount()->isEqualTo($that->getAmount()) && $this->getCurrency()->is($that->getCurrency());
    }

    public function getDecorated(): Monetizable
    {
        return $this->decorated;
    }

    public function setDecorated(Monetizable $decorated): self
    {
        $this->decorated = $decorated;

        return $this;
    }

    public function getRoundingMode(): ?int
    {
        return $this->roundingMode;
    }

    public function setRoundingMode(?int $roundingMode): self
    {
        $this->roundingMode = $roundingMode;

        return $this;
    }

    public function getAmount(): BigNumber
    {
        return $this->getDecorated()->getAmount();
    }

    public function setAmount(BigNumber|int|float|string $amount): self
    {
        return $this->newInstance($this->multipliedBy(0)->plus($amount));
    }

    public function getCurrency(): Currency
    {
        return $this->getDecorated()->getCurrency();
    }

    public function isISOCurrency(): bool
    {
        return Currencies::exists($this->getCurrency()->getCurrencyCode());
    }

    /**
     * @internal
     */
    protected function newInstance(Monetizable $money): self
    {
        return new self($money);
    }

    /**
     * @internal
     *
     * @throws \InvalidArgumentException when the rounding mode is not valid
     *
     * @psalm-return RoundingMode::*
     */
    protected function resolveRoundingMode(int $roundingMode = null): int
    {
        $roundingMode = ($roundingMode ?: $this->roundingMode) ?: RoundingMode::UNNECESSARY;

        if (!\in_array($roundingMode, self::ROUNDING_MODES, true)) {
            throw new \InvalidArgumentException(sprintf('The rounding mode "%s" is not valid.', $roundingMode));
        }

        /* @psalm-var RoundingMode::* $roundingMode */

        return $roundingMode;
    }

    /**
     * @internal
     */
    protected function getAmountOf(MoneyInterface|BigNumber|int|float|string $that): BigNumber|int|float|string
    {
        $that = $this->getOperationValue($that);

        return $that instanceof MoneyInterface ? $that->getAmount() : $that;
    }

    /**
     * @internal
     */
    protected function getOperationValue(MoneyInterface|BigNumber|int|float|string $that): MoneyInterface|BigNumber|int|float|string
    {
        if ($that instanceof MoneyInterface) {
            return $that->getAmount();
        }

        return $that;
    }
}
