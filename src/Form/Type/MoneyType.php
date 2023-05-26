<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\Type;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Form\DataTransformer\MoneyToIntegerTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as BaseMoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form type to represent Money based on Money/Money value objects.
 */
class MoneyType extends AbstractType
{
    public function __construct(private readonly MoneyConfig $moneyConfig)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var non-empty-string $currency */
        $currency = $options['currency'];
        $builder->addModelTransformer(new MoneyToIntegerTransformer($currency));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $defaultCurrency = $this->moneyConfig->getDefaultCurrency();

        $resolver
            ->setDefaults([
                'divisor' => 10 ** Currencies::getFractionDigits($defaultCurrency),
                'currency' => $defaultCurrency,
                'attr' => [
                    'inputmode' => 'numeric',
                ],
            ])
            ->setAllowedTypes('currency', 'string')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'brick_money';
    }

    public function getParent(): string
    {
        return BaseMoneyType::class;
    }
}
