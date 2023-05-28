<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Intl\Currencies;

class Ang3MoneyExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->validateConfiguration($config);
        $container->setParameter('ang3_money.config', $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            $container->prependExtensionConfig('doctrine', [
                'orm' => [
                    'mappings' => [
                        'Ang3MoneyBundle' => [
                            'is_bundle' => false,
                            'dir' => '%kernel.project_dir%/vendor/ang3/money-bundle/src/Entity',
                            'prefix' => 'Ang3\Bundle\MoneyBundle\Entity',
                            'alias' => 'Ang3MoneyBundle',
                        ],
                    ],
                ],
            ]);
        }
    }

    private function validateConfiguration(array $config): void
    {
        $isoCurrencyCodes = ($config['iso_currencies']['enabled'] ?? false) ? (($config['iso_currencies']['codes'] ?? []) ?: Currencies::getCurrencyCodes()) : [];
        $customCurrencyCodes = array_reduce($config['custom_currencies'] ?? [], function ($result, $parameters) {
            $result[] = $parameters['code'];

            return $result;
        }, []);

        $allCurrencyCodes = array_merge($isoCurrencyCodes, $customCurrencyCodes);

        if (!\in_array($config['default_currency'], $allCurrencyCodes, true)) {
            throw new InvalidConfigurationException(sprintf('The default currency "%s" is not valid - In case of ISO currency, make sure ISO currencies are enabled and the currency is not filtered, otherwise do not forget to register the custom currency.', $config['default_currency']));
        }
    }
}
