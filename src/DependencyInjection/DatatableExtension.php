<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Datatable\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\UX\Datatable\Builder\ResponseBuilder;
use Symfony\UX\Datatable\Builder\ResponseBuilderInterface;
use Symfony\UX\Datatable\Service\DataProcess;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Environment;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class DatatableExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $container
            ->setDefinition('datatable.data_process', new Definition(DataProcess::class))
            ->setPublic(false)
        ;

        $container
            ->setDefinition('datatable.builder', new Definition(ResponseBuilder::class))
            ->addArgument(new Reference('doctrine.orm.default_entity_manager'))
            ->addArgument(new Reference('datatable.data_process'))
            ->setAutowired(true)
            ->setPublic(false)
        ;

        $container
            ->setAlias(ResponseBuilderInterface::class, 'datatable.builder')
            ->setPublic(false)
        ;

        if (class_exists(Environment::class) && class_exists(StimulusTwigExtension::class)) {
            $container
                ->setDefinition('datatable.twig_extension', new Definition(\Symfony\UX\Datatable\Twig\DatatableExtension::class))
                ->addArgument(new Reference('webpack_encore.twig_stimulus_extension'))
                ->addTag('twig.extension')
                ->setPublic(false)
            ;
        }
    }
}
