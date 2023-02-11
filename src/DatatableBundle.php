<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable;

use Aziz403\UX\Datatable\Event\Events;
use Aziz403\UX\Datatable\Event\RenderDataEvent;
use Aziz403\UX\Datatable\Event\RenderQueryEvent;
use Aziz403\UX\Datatable\Event\RenderSearchQueryEvent;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 */
class DatatableBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new AddEventAliasesPass([
            RenderQueryEvent::class => Events::PRE_QUERY,
            RenderSearchQueryEvent::class => Events::SEARCH_QUERY,
            RenderDataEvent::class => Events::PRE_DATA,
        ]));
    }
}
