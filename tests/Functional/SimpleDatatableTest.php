<?php

namespace Aziz403\UX\Datatable\Tests\Functional;

use Aziz403\UX\Datatable\DatatableBundle;
use Aziz403\UX\Datatable\Tests\Application\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SimpleDatatableTest extends KernelTestCase
{
    public static function getKernelClass() :string
    {
        return AppKernel::class;
    }

    public function datatableHtmlContentTest() {
        self::bootKernel();
        $container = self::$kernel->getContainer();

    }

    public function datatableDataResultTest() {

    }
}