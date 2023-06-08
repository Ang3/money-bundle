<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Entity;

use Ang3\Bundle\MoneyBundle\Contracts\MoneyInterface;
use Ang3\Bundle\MoneyBundle\Currency\CurrencyRegistryProvider;
use Ang3\Bundle\MoneyBundle\Decorator\AbstractMoneyDecorator;
use Ang3\Bundle\MoneyBundle\Decorator\EmbeddedMoneyModifier;
use Ang3\Bundle\MoneyBundle\Validator\Constraints\ValidCurrency;
use Brick\Math\BigNumber;
use Brick\Money\Currency;
use Brick\Money\Money;
use Brick\Money\RationalMoney;
use Doctrine\ORM\Mapping as ORM;

/**
 * @method static self AFA(int $amount)
 * @method static self AFN(int $amount)
 * @method static self XAG(int $amount)
 * @method static self MGA(int $amount)
 * @method static self THB(int $amount)
 * @method static self PAB(int $amount)
 * @method static self ETB(int $amount)
 * @method static self VEB(int $amount)
 * @method static self VEF(int $amount)
 * @method static self VES(int $amount)
 * @method static self BOB(int $amount)
 * @method static self GHS(int $amount)
 * @method static self CRC(int $amount)
 * @method static self SVC(int $amount)
 * @method static self NIC(int $amount)
 * @method static self NIO(int $amount)
 * @method static self DKK(int $amount)
 * @method static self EEK(int $amount)
 * @method static self ISK(int $amount)
 * @method static self NOK(int $amount)
 * @method static self SKK(int $amount)
 * @method static self SEK(int $amount)
 * @method static self CZK(int $amount)
 * @method static self CSK(int $amount)
 * @method static self GMD(int $amount)
 * @method static self MKD(int $amount)
 * @method static self DZD(int $amount)
 * @method static self BHD(int $amount)
 * @method static self IQD(int $amount)
 * @method static self JOD(int $amount)
 * @method static self KWD(int $amount)
 * @method static self LYD(int $amount)
 * @method static self RSD(int $amount)
 * @method static self CSD(int $amount)
 * @method static self SDD(int $amount)
 * @method static self TND(int $amount)
 * @method static self YUF(int $amount)
 * @method static self YUD(int $amount)
 * @method static self YUN(int $amount)
 * @method static self YUR(int $amount)
 * @method static self YUO(int $amount)
 * @method static self YUG(int $amount)
 * @method static self YUM(int $amount)
 * @method static self AED(int $amount)
 * @method static self MAD(int $amount)
 * @method static self STD(int $amount)
 * @method static self STN(int $amount)
 * @method static self AUD(int $amount)
 * @method static self BSD(int $amount)
 * @method static self BZD(int $amount)
 * @method static self BMD(int $amount)
 * @method static self BND(int $amount)
 * @method static self KYD(int $amount)
 * @method static self CAD(int $amount)
 * @method static self XCD(int $amount)
 * @method static self FJD(int $amount)
 * @method static self GYD(int $amount)
 * @method static self HKD(int $amount)
 * @method static self SBD(int $amount)
 * @method static self JMD(int $amount)
 * @method static self BBD(int $amount)
 * @method static self LRD(int $amount)
 * @method static self NAD(int $amount)
 * @method static self NZD(int $amount)
 * @method static self SGD(int $amount)
 * @method static self SRD(int $amount)
 * @method static self TWD(int $amount)
 * @method static self TTD(int $amount)
 * @method static self USD(int $amount)
 * @method static self USS(int $amount)
 * @method static self USN(int $amount)
 * @method static self ZWD(int $amount)
 * @method static self ZWR(int $amount)
 * @method static self ZWL(int $amount)
 * @method static self VND(int $amount)
 * @method static self GRD(int $amount)
 * @method static self AMD(int $amount)
 * @method static self XDR(int $amount)
 * @method static self CVE(int $amount)
 * @method static self MZE(int $amount)
 * @method static self PTE(int $amount)
 * @method static self TPE(int $amount)
 * @method static self EUR(int $amount)
 * @method static self CHE(int $amount)
 * @method static self ANG(int $amount)
 * @method static self AWG(int $amount)
 * @method static self NLG(int $amount)
 * @method static self HUF(int $amount)
 * @method static self ADF(int $amount)
 * @method static self BEF(int $amount)
 * @method static self BIF(int $amount)
 * @method static self NHF(int $amount)
 * @method static self KMF(int $amount)
 * @method static self CDF(int $amount)
 * @method static self DJF(int $amount)
 * @method static self FRF(int $amount)
 * @method static self GNF(int $amount)
 * @method static self LUF(int $amount)
 * @method static self MGF(int $amount)
 * @method static self RWF(int $amount)
 * @method static self CHF(int $amount)
 * @method static self XOF(int $amount)
 * @method static self XAF(int $amount)
 * @method static self XPF(int $amount)
 * @method static self XFO(int $amount)
 * @method static self XFU(int $amount)
 * @method static self CHW(int $amount)
 * @method static self HTG(int $amount)
 * @method static self PYG(int $amount)
 * @method static self UAH(int $amount)
 * @method static self PGK(int $amount)
 * @method static self LAK(int $amount)
 * @method static self HRK(int $amount)
 * @method static self MWK(int $amount)
 * @method static self ZMK(int $amount)
 * @method static self ZMW(int $amount)
 * @method static self AOA(int $amount)
 * @method static self AOK(int $amount)
 * @method static self AON(int $amount)
 * @method static self AOR(int $amount)
 * @method static self MMK(int $amount)
 * @method static self GEL(int $amount)
 * @method static self LVL(int $amount)
 * @method static self ALL(int $amount)
 * @method static self HNL(int $amount)
 * @method static self SLL(int $amount)
 * @method static self MDL(int $amount)
 * @method static self ROL(int $amount)
 * @method static self RON(int $amount)
 * @method static self BGJ(int $amount)
 * @method static self BGK(int $amount)
 * @method static self BGL(int $amount)
 * @method static self BGN(int $amount)
 * @method static self SZL(int $amount)
 * @method static self MTL(int $amount)
 * @method static self ITL(int $amount)
 * @method static self SML(int $amount)
 * @method static self VAL(int $amount)
 * @method static self LTL(int $amount)
 * @method static self CYP(int $amount)
 * @method static self EGP(int $amount)
 * @method static self FKP(int $amount)
 * @method static self GIP(int $amount)
 * @method static self IEP(int $amount)
 * @method static self LBP(int $amount)
 * @method static self SHP(int $amount)
 * @method static self SDP(int $amount)
 * @method static self SDG(int $amount)
 * @method static self SSP(int $amount)
 * @method static self SYP(int $amount)
 * @method static self TRL(int $amount)
 * @method static self TRY(int $amount)
 * @method static self GBP(int $amount)
 * @method static self LSL(int $amount)
 * @method static self AZM(int $amount)
 * @method static self AZN(int $amount)
 * @method static self TMM(int $amount)
 * @method static self TMT(int $amount)
 * @method static self DEM(int $amount)
 * @method static self BAM(int $amount)
 * @method static self FIM(int $amount)
 * @method static self MZM(int $amount)
 * @method static self MZN(int $amount)
 * @method static self BOV(int $amount)
 * @method static self ERN(int $amount)
 * @method static self NGN(int $amount)
 * @method static self BTN(int $amount)
 * @method static self XAU(int $amount)
 * @method static self MRO(int $amount)
 * @method static self MRU(int $amount)
 * @method static self TOP(int $amount)
 * @method static self XPD(int $amount)
 * @method static self MOP(int $amount)
 * @method static self ADP(int $amount)
 * @method static self ESP(int $amount)
 * @method static self ARP(int $amount)
 * @method static self ARS(int $amount)
 * @method static self BOP(int $amount)
 * @method static self CLP(int $amount)
 * @method static self COP(int $amount)
 * @method static self CUP(int $amount)
 * @method static self CUC(int $amount)
 * @method static self GWP(int $amount)
 * @method static self MXN(int $amount)
 * @method static self PHP(int $amount)
 * @method static self DOP(int $amount)
 * @method static self UYI(int $amount)
 * @method static self UYU(int $amount)
 * @method static self XPT(int $amount)
 * @method static self BWP(int $amount)
 * @method static self GTQ(int $amount)
 * @method static self ZAR(int $amount)
 * @method static self BRR(int $amount)
 * @method static self BRL(int $amount)
 * @method static self IRR(int $amount)
 * @method static self OMR(int $amount)
 * @method static self QAR(int $amount)
 * @method static self KHR(int $amount)
 * @method static self MYR(int $amount)
 * @method static self SAR(int $amount)
 * @method static self YER(int $amount)
 * @method static self LVR(int $amount)
 * @method static self BYB(int $amount)
 * @method static self BYR(int $amount)
 * @method static self BYN(int $amount)
 * @method static self SUR(int $amount)
 * @method static self SUB(int $amount)
 * @method static self RUB(int $amount)
 * @method static self MUR(int $amount)
 * @method static self INR(int $amount)
 * @method static self IDR(int $amount)
 * @method static self NPR(int $amount)
 * @method static self PKR(int $amount)
 * @method static self SCR(int $amount)
 * @method static self LKR(int $amount)
 * @method static self MVR(int $amount)
 * @method static self ATS(int $amount)
 * @method static self KES(int $amount)
 * @method static self UGX(int $amount)
 * @method static self SOS(int $amount)
 * @method static self TZS(int $amount)
 * @method static self ILS(int $amount)
 * @method static self PES(int $amount)
 * @method static self PEN(int $amount)
 * @method static self KGS(int $amount)
 * @method static self TJS(int $amount)
 * @method static self ECS(int $amount)
 * @method static self XSU(int $amount)
 * @method static self UZS(int $amount)
 * @method static self BDT(int $amount)
 * @method static self WST(int $amount)
 * @method static self KZT(int $amount)
 * @method static self SIT(int $amount)
 * @method static self MNT(int $amount)
 * @method static self CLF(int $amount)
 * @method static self XBC(int $amount)
 * @method static self XBD(int $amount)
 * @method static self XEU(int $amount)
 * @method static self XUA(int $amount)
 * @method static self MXV(int $amount)
 * @method static self ECV(int $amount)
 * @method static self COU(int $amount)
 * @method static self XBA(int $amount)
 * @method static self XBB(int $amount)
 * @method static self UYW(int $amount)
 * @method static self VUV(int $amount)
 * @method static self KPW(int $amount)
 * @method static self KRW(int $amount)
 * @method static self JPY(int $amount)
 * @method static self CNY(int $amount)
 * @method static self PLZ(int $amount)
 * @method static self PLN(int $amount)
 */
#[ORM\Embeddable]
class EmbeddedMoney extends AbstractMoneyDecorator
{
    #[ORM\Column(length: 100)]
    private string $amount;

    #[ValidCurrency]
    #[ORM\Column(length: 10)]
    private string $currency;

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $defaultRoundingMode
     */
    public function __construct(Money|MoneyInterface|RationalMoney $money = null, int $defaultRoundingMode = null)
    {
        parent::__construct($money ?: Money::zero(CurrencyRegistryProvider::getRegistry()->getDefaultCurrency()), $defaultRoundingMode);
    }

    public function __toString(): string
    {
        return (string) $this->getMoney();
    }

    public static function __callStatic(string $method, array $arguments = []): self
    {
        $amount = $arguments[0] ?? 0;

        if (null !== $amount && !$amount instanceof BigNumber) {
            if (!\is_int($amount) && !\is_float($amount) && !\is_string($amount)) {
                throw new \InvalidArgumentException(sprintf('The first argument #0 must be of type "%s|int|float|string|null" (currency: "%s"), got "%s".', BigNumber::class, $method, get_debug_type($amount)));
            }
        }

        return self::of($amount, CurrencyRegistryProvider::getRegistry()->get($method));
    }

    /**
     * @param 0|1|2|3|4|5|6|7|8|9|null $defaultRoundingMode
     */
    public static function create(Money|MoneyInterface|RationalMoney $decorated, int $defaultRoundingMode = null): self
    {
        return new self($decorated, $defaultRoundingMode);
    }

    public static function of(
        BigNumber|int|float|string|null $amount = null,
        Currency $currency = null,
        ?bool $isMinor = true
    ): self {
        $currency = $currency ?: CurrencyRegistryProvider::getRegistry()->getDefaultCurrency();

        if (null !== $amount) {
            $money = $isMinor ? Money::ofMinor($amount, $currency) : Money::of($amount, $currency);
        } else {
            $money = Money::zero($currency);
        }

        return new self($money);
    }

    public static function embed(Money $money): self
    {
        return new self($money);
    }

    public static function zero(Currency $currency): self
    {
        $embeddedMoney = new self();
        $embeddedMoney->setMoney(Money::zero($currency));

        return $embeddedMoney;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(BigNumber|int|float|string|null $amount): static
    {
        if (!$amount instanceof BigNumber) {
            $amount = BigNumber::of(null !== $amount ? $amount : 0);
        }

        $this->amount = (string) $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(Currency|string|null $currency = null): static
    {
        if (null !== $currency) {
            $this->currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;
        } else {
            $this->currency = CurrencyRegistryProvider::getRegistry()->getDefaultCurrency()->getCurrencyCode();
        }

        return $this;
    }

    public function getMoney(): Money
    {
        return Money::of($this->amount, CurrencyRegistryProvider::getRegistry()->get($this->currency));
    }

    public function setMoney(Money|MoneyInterface|RationalMoney $money): static
    {
        $this->amount = (string) $money->getAmount();
        $this->currency = (string) $money->getCurrency();

        return $this;
    }

    public function getDecorated(): Money|RationalMoney
    {
        return $this->getMoney();
    }

    public function setDecorated(Money|MoneyInterface|RationalMoney $decorated): static
    {
        $this->setMoney($decorated);

        return $this;
    }

    protected function newInstance(Money|MoneyInterface|RationalMoney $money): EmbeddedMoneyModifier
    {
        $modifier = new EmbeddedMoneyModifier($this);
        $modifier->setDecorated($money);

        return $modifier;
    }
}
