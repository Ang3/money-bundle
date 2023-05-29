<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\Form\Type;

use Ang3\Bundle\MoneyBundle\Entity\EmbeddedMoney;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form type to represent embedded money objects.
 */
class EmbeddedMoneyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var EmbeddedMoney $embeddedMoney */
        $embeddedMoney = $options['data'];
        $currency = $embeddedMoney->getCurrency()?->getCurrencyCode();
        $isCurrencyFieldEnabled = null === $currency && false !== $options['currency_field'];

        if ($currency) {
            $builder
                ->add('amount', MoneyType::class, array_merge((array) $options['amount_options'], [
                    'required' => $options['required'],
                    'currency' => $currency,
                    'divisor' => 10 ** Currencies::getFractionDigits($currency),
                ]))
            ;
        } else {
            $builder
                ->add('amount', NumberType::class, array_merge((array) $options['amount_options'], [
                    'required' => $options['required'],
                    'block_prefix' => 'embedded_money_amount',
                ]))
            ;
        }

        if ($isCurrencyFieldEnabled) {
            $builder->add('currency', CurrencyType::class, [
                'label' => 'Currency',
                'required' => $options['required'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'class' => EmbeddedMoney::class,
                'required' => false,
                'currency_field' => true,
                'amount_options' => [],
                'currency_options' => [],
            ])
            ->setAllowedTypes('required', 'bool')
            ->setAllowedTypes('currency', 'string')
            ->setAllowedTypes('currency_field', 'bool')
            ->setAllowedTypes('amount_options', 'array')
            ->setAllowedTypes('currency_options', 'array')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'embedded_money';
    }
}
