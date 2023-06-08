<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Decorator;

use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistryProvider;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

/**
 * This class is a decorator or objects of type Brick\Money\AbstractMoney.
 */
class MoneyDecorator extends AbstractMoneyDecorator
{
    private Money|RationalMoney $decorated;

    public function __toString(): string
    {
        return $this->decorated->__toString();
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $defaultRoundingMode
     */
    public static function create(Money|RationalMoney|MoneyInterface $decorated, int $defaultRoundingMode = null): self
    {
        return new self($decorated, $defaultRoundingMode);
    }

    public function getDecorated(): Money|RationalMoney
    {
        return $this->decorated;
    }

    public function setDecorated(Money|RationalMoney|MoneyInterface $decorated): static
    {
        if ($decorated instanceof MoneyInterface) {
            $decorated = Money::of($decorated->getAmount(), CurrencyRegistryProvider::getRegistry()->get($decorated->getCurrency()));
        }

        $this->decorated = $decorated;

        return $this;
    }
}
