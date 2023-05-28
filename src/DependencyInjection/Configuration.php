<?php

declare(strict_types=1);

/*
 * This file is part of package ang3/money-bundle
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ang3\Bundle\MoneyBundle\DependencyInjection;

use Ang3\Bundle\MoneyBundle\Ang3MoneyBundle;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Intl\Currencies;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ang3_money');

        $treeBuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
            ->fixXmlConfig('custom_currency', 'custom_currencies')
            ->children()
            ->scalarNode('default_currency')->cannotBeEmpty()->defaultValue(Ang3MoneyBundle::DEFAULT_CURRENCY)->end()
            ->append($this->addIsoCurrenciesNode())
            ->append($this->addCustomCurrenciesNode())
            ->end()
            ->validate()
            ->ifTrue(function ($v): void {
            })
            ->thenInvalid()
            ->end()
        ;

        return $treeBuilder;
    }

    public function addIsoCurrenciesNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('iso_currencies');

        return $treeBuilder
            ->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('enabled')->defaultValue(true)->end()
            ->arrayNode('codes')
            ->scalarPrototype()
            ->validate()->ifNotInArray(Currencies::getCurrencyCodes())->thenInvalid('Invalid currency "%s".')->end()
            ->end()
            ->end()
            ->end()
        ;
    }

    public function addCustomCurrenciesNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('custom_currencies');

        return $treeBuilder
            ->getRootNode()
            ->useAttributeAsKey('code')
            ->arrayPrototype()
            ->children()
            ->scalarNode('code')->isRequired()->cannotBeEmpty()->end()
            ->integerNode('scale')->isRequired()->min(0)->end()
            ->scalarNode('name')->info('Currency name - If NULL, the code is used as name.')->end()
            ->end()
            ->end()
        ;
    }
}
