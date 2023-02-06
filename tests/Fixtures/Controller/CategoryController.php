<?php

namespace Aziz403\UX\Datatable\Tests\Fixtures\Controller;

use Aziz403\UX\Datatable\Builder\DatatableBuilderInterface;
use Aziz403\UX\Datatable\Column\TextColumn;
use Aziz403\UX\Datatable\Column\TwigColumn;
use Aziz403\UX\Datatable\Tests\Fixtures\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    public function __invoke(Request $request,DatatableBuilderInterface $builder): Response
    {
        $datatable = $builder->createDatatableFromEntity(Category::class);
        $datatable
            ->addColumn(new TextColumn('name'))
            ->addColumn(new TwigColumn('','_actions.html.twig'))
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