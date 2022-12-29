<?php

namespace Aziz403\UX\Datatable\Tests;

use Aziz403\UX\Datatable\Tests\Kernel\EmptyAppKernel;
use Aziz403\UX\Datatable\Tests\Kernel\FrameworkAppKernel;
use Aziz403\UX\Datatable\Tests\Kernel\TwigAppKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Kernel;

class DatatableBundleTest extends TestCase
{
    public function provideKernels()
    {
        yield 'empty' => [new EmptyAppKernel('test', true)];
        yield 'framework' => [new FrameworkAppKernel()];
        yield 'twig' => [new TwigAppKernel('test', true)];
    }

    /**
     * @dataProvider provideKernels
     */
    public function testBootKernel(Kernel $kernel)
    {
        $kernel->boot();
        $this->assertArrayHasKey('DatatableBundle', $kernel->getBundles());
    }
}