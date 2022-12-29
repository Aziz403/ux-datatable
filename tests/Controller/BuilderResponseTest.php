<?php

namespace Aziz403\UX\Datatable\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuilderResponseTest extends WebTestCase
{
        public function testDatatableResponse()
        {
            $client = static::createClient();
            $client->request('GET', '/TODO');

            $this->assertResponseIsSuccessful();
        }
}