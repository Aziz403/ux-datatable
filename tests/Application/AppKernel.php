<?php

namespace Aziz403\UX\Datatable\Tests\Application;

use Aziz403\UX\Datatable\DatatableBundle;
use Aziz403\UX\Datatable\Tests\Application\TestApp\AppBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

class AppKernel extends Kernel
{

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new WebpackEncoreBundle(),
            new DoctrineBundle(),
            new DatatableBundle(),
            new AppBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
    }

    public function getRootDir(): string
    {
        return __DIR__ . '/../../tmp';
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}