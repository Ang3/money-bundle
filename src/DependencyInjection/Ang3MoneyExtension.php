<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DependencyInjection;

use Ang3\Bundle\MoneyBundle\DBAL\Types\CurrencyType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Ang3MoneyExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $config['currencies'] ??= [];
        $defaultCurrency = (string) $config['default_currency'];

        if (!\in_array($defaultCurrency, $config['currencies'], true)) {
            $config['currencies'][] = $defaultCurrency;
        }

        $container->setParameter('ang3_money.config', $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            $container->prependExtensionConfig('doctrine', [
                'dbal' => [
                    'types' => [
                        'currency' => CurrencyType::class,
                    ],
                ],
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
}
