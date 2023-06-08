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
use Brick\Money\Context;
use Brick\Money\Money;
use Brick\Money\RationalMoney;

/**
 * This class is an abstract money decorator but the result of each operation is kept in memory
 * instead of returned into a new decorator instance.
 * Moreover, because of calculations aim, this decorator does only its job on rational money.
 */
class MoneyModifier extends MoneyDecorator
{
    protected ?Context $context = null;

    public function setDecorated(Money|RationalMoney|MoneyInterface $decorated): static
    {
        parent::setDecorated($decorated);
        $decorated = $this->getDecorated();

        if (!$decorated instanceof RationalMoney) {
            if (!$decorated instanceof Money) {
                $decorated = Money::of($decorated->getAmount(), $decorated->getCurrency());
            }

            parent::setDecorated($decorated->toRational());
        }

        return $this;
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $roundingMode
     */
    public function getResult(int $roundingMode = null): Money
    {
        /** @var RationalMoney $rationalMoney */
        $rationalMoney = $this->getDecorated();

        return $rationalMoney->to($this->context ?: new Context\DefaultContext(), $this->resolveRoundingMode($roundingMode));
    }

    protected function newInstance(Money|RationalMoney|MoneyInterface $money): static
    {
        $this->setDecorated($money);

        return $this;
    }
}
