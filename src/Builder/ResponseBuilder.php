<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Datatable\Builder;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Datatable\Repository\DatatableRepository;
use Symfony\UX\Datatable\Service\DataProcess;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class ResponseBuilder implements ResponseBuilderInterface
{

    public function __construct(private EntityManagerInterface $manager,private DataProcess $dataProcess) {}

    /**
     * @param Request $request
     * @param string $class
     * @return Response
     * @throws \Exception
     */
    public function datatable(Request $request, string $class,string $actionsTemplate = null): Response
    {
        $repository = $this->manager->getRepository($class);
        if(!$repository){ return new Response(null,404); }
        if(!$repository instanceof DatatableRepository)
        {
            throw new \Exception("ategad exception 3ela 9ebel kola error");
        }

        $data = $repository->getDataWithRequest($request);

        if(!$request->get('withoutActionField')){
            $data = $this->dataProcess->addActionField($data,$class,$actionsTemplate);
        }

        return new JsonResponse([
            "recordsTotal" => $repository->getCountAll(),
            "recordsFiltered" => $repository->getCountWithRequest($request),
            "draw" => $request->get('draw',1),
            "data" => $data,
        ]);
    }

    /**
     * @param string $class
     * @param string $displayName
     * @return Response
     * @throws \Exception
     */
    public function choices(string $class, string $displayName): Response
    {
        $repository = $this->manager->getRepository($class);
        if(!$repository){ return new Response(null,404); }
        if(!$repository instanceof DatatableRepository)
        {
            throw new \Exception("ategad exception 3ela 9ebel kola error");
        }

        return new JsonResponse([
            'data' => $repository->getChoices($displayName),
        ]);
    }
}