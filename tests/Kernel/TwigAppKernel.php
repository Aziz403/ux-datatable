<?php

namespace Aziz403\UX\Datatable\Tests\Kernel;

use Aziz403\UX\Datatable\DatatableBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TwigAppKernel extends Kernel
{
    use AppKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new DatatableBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', ['secret' => '$ecret', 'test' => true]);
            $container->loadFromExtension('webpack_encore', ['output_path' => '%kernel.project_dir%/public/build']);
            $container->loadFromExtension('twig', [
                'default_path' => __DIR__.'/templates',
                'strict_variables' => true,
                'exception_controller' => null,
                'debug' => '%kernel.debug%'
            ]);

            $container->setAlias('test.datatable.builder', 'datatable.builder')->setPublic(true);
            $container->setAlias('test.datatable.twig_extension', 'datatable.twig_extension')->setPublic(true);
        });
    }
}