<?php

namespace Aziz403\UX\Datatable\Tests\Unit;

use Aziz403\UX\Datatable\DatatableBundle;
use PHPUnit\Framework\TestCase;

class DataTableBundleTest extends TestCase
{
    public function testBundle(): void
    {
        $bundle = new DatatableBundle();
        $this->assertSame('DatatableBundle', $bundle->getName());
    }
}
