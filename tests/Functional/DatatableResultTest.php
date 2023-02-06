<?php

namespace Aziz403\UX\Datatable\Tests\Functional;

use Aziz403\UX\Datatable\Tests\Fixtures\Entity\Category;
use Aziz403\UX\Datatable\Tests\Fixtures\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class DatatableResultTest extends WebTestCase
{
    use ResetDatabase;

    public function testDatatableResponseWithoutData()
    {
        $client = static::createClient();
        $client->request('GET', '/test-category?draw=1&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&_=1675703289674');

        $response = $client->getResponse();
        $data = json_decode($response->getContent(),true);

        $this->assertSame(200,$response->getStatusCode());
        $this->assertSame(count($data['data']),0);
        $this->assertSame($data['recordsTotal'],"0");
    }

    public function testDatatableResponseWithData()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        $firstCat = new Category();
        $firstCat->setName('First Cat');
        $em->persist($firstCat);

        $em->flush();

        $client->request('GET', '/test-category?draw=1&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&_=1675703289674');

        $response = $client->getResponse();
        $data = json_decode($response->getContent(),true);

        $this->assertSame(200,$response->getStatusCode());
        $this->assertSame($data["recordsTotal"],"1");
    }

    public function testDatatableResponseWithSearch()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        $firstCat = new Category();
        $firstCat->setName('First Cat');
        $em->persist($firstCat);
        $secondCat = new Category();
        $secondCat->setName('Second Cat');
        $em->persist($secondCat);

        $em->flush();

        $client->request('GET', '/test-category?draw=7&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10&search%5Bvalue%5D=First&search%5Bregex%5D=false&_=1675703289680');

        $response = $client->getResponse();
        $data = json_decode($response->getContent(),true);

        $this->assertSame(200,$response->getStatusCode());
        $this->assertSame($data["recordsTotal"],"2");
        $this->assertSame($data["recordsFiltered"],"1");
        $this->assertSame($data["data"][0][0],"First Cat");
        $this->assertSame($data["data"][0][1],'<a href="/test-product/1">Products</a>');
    }

    public function testDatatableResponseWithCriteria()
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        $firstCat = new Category();
        $firstCat->setName('First Cat');
        $em->persist($firstCat);
        $prod1 = new Product();
        $prod1->setName('Product 1');
        $prod1->setDescription("");
        $prod1->setPrice(1);
        $prod1->setIsEnabled(true);
        $prod1->setCategory($firstCat);
        $em->persist($prod1);
        $prod2 = new Product();
        $prod2->setName('Product 2');
        $prod2->setDescription("");
        $prod2->setPrice(1);
        $prod2->setIsEnabled(true);
        $prod2->setCategory($firstCat);
        $em->persist($prod2);

        $secondCat = new Category();
        $secondCat->setName('Second Cat');
        $em->persist($secondCat);
        $prod3 = new Product();
        $prod3->setName('Product 3');
        $prod3->setDescription("");
        $prod3->setPrice(1);
        $prod3->setIsEnabled(true);
        $prod3->setCategory($secondCat);
        $em->persist($prod3);

        $em->flush();

        $client->request('GET', '/test-product/'.$firstCat->getId().'?draw=1&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=1&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=2&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=3&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=4&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=asc&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&_=1675714935866');

        $response = $client->getResponse();
        $data = json_decode($response->getContent(),true);

        $this->assertSame(200,$response->getStatusCode());
        $this->assertSame($data["recordsTotal"],"2");
        $this->assertSame($data["data"][0][0],"Product 1");
    }
}