<?php

namespace Aziz403\UX\Datatable\Tests\Fixtures\Controller;

use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Aziz403\UX\Datatable\Column\BooleanColumn;
use Aziz403\UX\Datatable\Column\EntityColumn;
use Aziz403\UX\Datatable\Column\TextColumn;
use Aziz403\UX\Datatable\Event\RenderSearchQueryEvent;
use Aziz403\UX\Datatable\Tests\Fixtures\Entity\Product;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Product2Controller extends AbstractController
{
    public function __invoke(Request $request,DatatableBuilderInterface $builder): Response
    {
        $datatable = $builder->createDatatableFromEntity(Product::class);
        $datatable
            ->addColumn(new TextColumn('name'))
            ->addColumn(new TextColumn('description','description',false))
            ->addColumn(new TextColumn('price'))
            ->addColumn(new BooleanColumn('isEnabled'))
            ->addColumn(new EntityColumn('category','name'))
            ->addFilter(function(RenderSearchQueryEvent $event){
                $q = $event->getQuery();
                $q->andWhere('entity.name = :name')
                    ->setParameter('name',"Product 3");
            })
        ;

        $datatable->handleRequest($request);

        if($datatable->isSubmitted()){
            return $datatable->getResponse();
        }

        return $this->render('simple_datatable.html.twig', [
            'datatable' => $datatable
        ]);
    }
}