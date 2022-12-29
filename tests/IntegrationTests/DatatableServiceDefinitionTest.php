<?php

namespace Aziz403\UX\Datatable\Tests\IntegrationTests;

use Aziz403\UX\Datatable\Tests\Kernel\FrameworkAppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DatatableServiceDefinitionTest extends TestCase
{
    public function bundleServiceDefinitionDataProvider(): \Generator
    {
        $prefix = 'datatable.';

        yield [$prefix.'builder'];
        yield [$prefix.'twig_extension'];
    }

    /**
     * @dataProvider bundleServiceDefinitionDataProvider
     */
    public function testBundleServiceDefinitions(string $definition): void
    {
        $pass = new DefinitionPublicCompilerPass();
        $pass->definition = $definition;

        $kernel = new DatatableDefinitionTestKernel();
        $kernel->compilerPass = $pass;
        $kernel->boot();

        $container = $kernel->getContainer();
        $container->get($definition);

        $this->expectNotToPerformAssertions();
    }
}

final class DefinitionPublicCompilerPass implements CompilerPassInterface
{
    public $definition;

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition($this->definition)
            ->setPublic(true)
        ;
    }
}

final class DatatableDefinitionTestKernel extends FrameworkAppKernel
{
    public $compilerPass;

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass($this->compilerPass);
    }
}