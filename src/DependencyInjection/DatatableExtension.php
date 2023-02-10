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

use Aziz403\UX\Datatable\Twig\DatatableExtension as TwigDatatableExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Aziz403\UX\Datatable\Builder\DatatableBuilder;
use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Environment;
use Twig\Extension\StringLoaderExtension;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('datatable.config', $config);

        if(class_exists(Environment::class)) {
            $container
                ->setDefinition('datatable.builder', new Definition(DatatableBuilder::class))
                ->addArgument(new Reference('request_stack'))
                ->addArgument(new Reference('doctrine.orm.default_entity_manager'))
                ->addArgument(new Reference('twig'))
                ->addArgument(new Reference('translator.default'))
                ->addArgument($config);
            $container
                ->setAlias(DatatableBuilderInterface::class, 'datatable.builder');
        }

        if (class_exists(Environment::class) && class_exists(StimulusTwigExtension::class)) {
            $container
                ->setDefinition('datatable.twig_extension', new Definition(TwigDatatableExtension::class))
                ->addArgument(new Reference('webpack_encore.twig_stimulus_extension'))
                ->addTag('twig.extension');
        }
    }
}
