<?php

namespace Aziz403\UX\Datatable\Tests\Functional;

use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Aziz403\UX\Datatable\Column\TextColumn;
use Aziz403\UX\Datatable\Tests\Fixtures\Entity\Category;
use Aziz403\UX\Datatable\Tests\Fixtures\Kernel;
use Aziz403\UX\Datatable\Twig\DatatableExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class DatatableExtensionTest extends TestCase
{
    public function testRenderDatatable()
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer()->get('test.service_container');

        /** @var DatatableBuilderInterface $builder */
        $builder = $container->get('public.datatable.builder');
        /** @var DatatableExtension $twigExtension */
        $twigExtension = $container->get('public.datatable.twig_extension');

        $datatable = $builder->createDatatableFromEntity(Category::class);
        $datatable
            ->addColumn(new TextColumn("name"))
            ->addAttributes(['id'=>'t1'])
            ->setPath('/test-category')
        ;

        $rendered = $twigExtension->renderDatatable(
            $container->get(Environment::class),
            $datatable
        );

        $this->assertSame(
            "<table data-controller=\"aziz403--ux-datatable--styling-bootstrap5 aziz403--ux-datatable--datatable\" data-aziz403--ux-datatable--datatable-view-value=\"&#x7B;&quot;path&quot;&#x3A;&quot;&#x5C;&#x2F;test-category&quot;,&quot;options&quot;&#x3A;&#x7B;&quot;processing&quot;&#x3A;true,&quot;serverSide&quot;&#x3A;true,&quot;columnDefs&quot;&#x3A;&#x5B;&#x7B;&quot;targets&quot;&#x3A;0,&quot;visible&quot;&#x3A;true,&quot;orderable&quot;&#x3A;true&#x7D;&#x5D;,&quot;language&quot;&#x3A;&#x7B;&quot;url&quot;&#x3A;&quot;&#x5C;&#x2F;&#x5C;&#x2F;cdn.datatables.net&#x5C;&#x2F;plug-ins&#x5C;&#x2F;1.10.15&#x5C;&#x2F;i18n&#x5C;&#x2F;English.json&quot;&#x7D;&#x7D;&#x7D;\"id=\"t1\" data-styling-choicer=\"bootstrap5\" class=\" table table-bordered\" ><thead><tr><th data-column-name='name'>name</th></tr></thead></table>",
            $rendered
        );
    }
}