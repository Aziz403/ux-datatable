<?php

namespace Aziz403\UX\Datatable\Tests\Kernel;

use Aziz403\UX\Datatable\DatatableBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class DoctrineAppKernel extends Kernel
{
    use AppKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new DatatableBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // TODO: Implement registerContainerConfiguration() method.
    }
}