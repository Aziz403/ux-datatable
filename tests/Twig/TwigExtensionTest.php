<?php

namespace Aziz403\UX\Datatable\Tests\Twig;

use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Aziz403\UX\Datatable\Model\Datatable;
use Aziz403\UX\Datatable\Tests\Kernel\TwigAppKernel;
use Aziz403\UX\Datatable\Twig\DatatableExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class TwigExtensionTest extends TestCase
{
    public function testRenderDatatable()
    {
        $kernel = new TwigAppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer()->get('test.service_container');

        $twig = $container->get(Environment::class);

        $datatable = new Datatable();

        $rendered = $twig->render("datatable.html.twig",['datatable'=>$datatable]);

        $this->assertSame(
            '',
            ''//$rendered
        );
    }

    public function testRenderEntityDatatable()
    {
        $kernel = new TwigAppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer()->get('test.service_container');

        /** @var DatatableBuilderInterface $builder */
        $builder = $container->get('test.datatable.builder');
        /** @var DatatableExtension $extension */
        $extension = $container->get('test.datatable.twig_extension');

        $datatable = $builder->createDatatableFromEntity('TODO:');

        $rendered = $extension->renderDatatable(
            $container->get(Environment::class),
            $datatable,
            ['data-test'=>'attr']
        );

        $this->assertSame(
            '',
            ''//$rendered
        );
    }
}