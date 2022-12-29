<?php

namespace Aziz403\UX\Datatable\Tests\Kernel;

use Aziz403\UX\Datatable\DatatableBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class FrameworkAppKernel extends Kernel
{
    use AppKernelTrait;

    private $builder;

    public function __construct(ContainerBuilder $builder = null)
    {
        $this->builder = $builder;
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DatatableBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if (null === $this->builder) {
            $this->builder = new ContainerBuilder();
        }

        $builder = $this->builder;

        $loader->load(function (ContainerBuilder $container) use($builder) {
            $container->merge($builder);
            $container->loadFromExtension('framework', ['secret' => '$ecret', 'test' => true]);
        });
    }
}