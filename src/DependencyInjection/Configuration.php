<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('datatable');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('language_from_cdn')
                    ->info('Load i18n data from DataTables CDN or locally')
                    ->defaultTrue()
                    ->isRequired()
                ->end()
                ->scalarNode('language')
                    ->info('Language of the Datatables (if language_from_cdn true)')
                    ->defaultValue('English')
                ->end()
                ->arrayNode('options')
                    ->info('Default options to load into DataTables')
                    ->useAttributeAsKey('option')
                    ->prototype('variable')->end()
                ->end()
                ->arrayNode('template_parameters')
                    ->info('Default parameters to be passed to the template')
                    ->addDefaultsIfNotSet()
                    ->ignoreExtraKeys()
                    ->children()
                        ->enumNode('style')
                            ->values(['none','bootstrap','bootstrap4','foundation','material','uikit','semanticui','jqueryui'])
                            ->info('Default Datatables style')
                            ->defaultValue('bootstrap5')
                        ->end()
                        ->scalarNode('className')
                            ->info('Default class attribute to apply to the root table elements (change it to be compatible with the style)')
                            ->defaultValue('table table-bordered')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}