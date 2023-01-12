<?php

namespace Aziz403\UX\Datatable\Tests\Unit;

use Aziz403\UX\Datatable\DatatableBundle;
use Aziz403\UX\Datatable\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DependencyInjectionTest extends TestCase
{
    public function testConfiguration(): void
    {
        $config = new Configuration();
        $tree = $config->getConfigTreeBuilder()->buildTree();

        $this->assertInstanceOf(ArrayNode::class, $tree);
    }

    public function testExtension(): void
    {
        $bundle = new DatatableBundle();
        $extension = $bundle->getContainerExtension();
        $this->assertSame('datatable', $extension->getAlias());

        $container = new ContainerBuilder();
        $extension->load([
            'datatable' => [
                'language_from_cdn' => false,
                'language' => 'English',
                'template_parameters' => [
                    'style' => 'bootstrap5',
                    'className' => 'table table-bordered'
                ]
            ]
        ], $container);

        // Verify default config, options should be empty
        $config = $container->getParameter('datatable.config');
        $this->assertFalse($config['language_from_cdn']);
        $this->assertEmpty($config['options']);
        $this->assertSame('bootstrap5',$config['template_parameters']['style']);
    }
}