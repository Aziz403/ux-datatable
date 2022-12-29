<?php

namespace Aziz403\UX\Datatable\Tests\IntegrationTests;

use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Aziz403\UX\Datatable\Tests\Kernel\FrameworkAppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DatatableBundleAutowireTest extends TestCase
{
    public function testVerifyEmailBundleInterfaceIsAutowiredByContainer(): void
    {
        $builder = new ContainerBuilder();
        $builder->autowire(DatatableBuilderAutowireTest::class)
            ->setPublic(true)
        ;

        $kernel = new FrameworkAppKernel($builder);
        $kernel->boot();

        $container = $kernel->getContainer();
        $container->get(DatatableBuilderAutowireTest::class);

        $this->expectNotToPerformAssertions();
    }
}

class DatatableBuilderAutowireTest
{
    public function __construct(DatatableBuilderInterface $builder)
    {
    }
}