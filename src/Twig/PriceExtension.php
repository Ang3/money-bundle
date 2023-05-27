<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Twig;

use Ang3\Bundle\MoneyBundle\Entity\Price;
use Brick\Money\Money;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PriceExtension extends AbstractExtension
{
    public function __construct(private readonly MoneyExtension $moneyExtension)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('price', [$this, 'createPrice']),
            new TwigFunction('moneyToPrice', [$this, 'convertMoneyToPrice']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function createPrice(int $amount, string $currency = null): Price
    {
        return Price::create($amount, $currency);
    }

    public function convertMoneyToPrice(Money $money): Price
    {
        return Price::wrapMoney($money);
    }

    public function formatPrice(Price $money, string $locale = null): string
    {
        return $this->moneyExtension->formatMoney($money->getMoney(), $locale);
    }

    public function getName(): string
    {
        return 'ang3_money.twig.price_extension';
    }
}
