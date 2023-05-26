<?php

namespace Ang3\Bundle\MoneyBundle\Form\Type;

use Ang3\Bundle\MoneyBundle\Config\MoneyConfig;
use Ang3\Bundle\MoneyBundle\Entity\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form type to represent Price objects.
 */
class PriceFormType extends AbstractType
{
    public function __construct(private readonly MoneyConfig $moneyConfig)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var non-empty-string $currency */
        $currency = $options['currency'];

        $builder
            ->add('amount', MoneyType::class, array_merge($options['amount_options'], [
                'currency' => $currency
            ]));

        if (true === $options['currency_field']) {
            $builder->add('currency', CurrencyType::class, (array) $options['currency_options']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $defaultCurrency = $this->moneyConfig->getDefaultCurrency();

        $resolver
            ->setDefaults([
                'class' => Price::class,
                'currency' => $defaultCurrency,
                'currency_field' => true,
                'amount_options' => [],
                'currency_options' => [],
            ])
            ->setAllowedTypes('currency', 'string')
            ->setAllowedTypes('currency_field', 'bool')
            ->setAllowedTypes('currency_options', 'array')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'price';
    }
}