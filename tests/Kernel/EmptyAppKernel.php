<?php

namespace Aziz403\UX\Datatable\Tests\Kernel;

use Aziz403\UX\Datatable\DatatableBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class EmptyAppKernel extends Kernel
{

    public function registerBundles(): iterable
    {
        return [
            new DatatableBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {

        });
    }
}