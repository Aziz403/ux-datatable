<?php

namespace Aziz403\UX\Datatable\Tests\Application\TestApp\Controller;

use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Aziz403\UX\Datatable\Tests\Application\TestApp\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SimpleController extends AbstractController
{
    public function __invoke(Request $request,DatatableBuilderInterface  $builder)
    {
        $datatable = $builder->createDatatableFromEntity(Person::class);
        $datatable->addColumns([

        ])
        ->handleRequest($request);

        if($datatable->isSubmitted()){
            return $datatable->getResponse();
        }

        return $this->render('@AppBundle/main.html.twig',['datatable'=>$datatable]);
    }
}