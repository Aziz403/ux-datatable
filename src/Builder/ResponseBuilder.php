<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aziz403\UX\Datatable\Builder;

use Aziz403\UX\Datatable\Service\ExportExcelService;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Aziz403\UX\Datatable\Repository\DatatableRepository;
use Aziz403\UX\Datatable\Service\DataProcess;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class ResponseBuilder implements ResponseBuilderInterface
{
    private EntityManagerInterface $manager;
    private DataProcess $dataProcess;
    private ExportExcelService $exportExcelService;

    public function __construct(EntityManagerInterface $manager,DataProcess $dataProcess,ExportExcelService $exportExcelService)
    {
        $this->manager = $manager;
        $this->dataProcess = $dataProcess;
        $this->exportExcelService = $exportExcelService;
    }

    /**
     * @param Request $request
     * @param string $class
     * @return Response
     * @throws \Exception
     */
    public function datatable(Request $request, string $class,?string $actionsTemplate = self::DEFAULT_TEMPLATE_PATH): Response
    {
        $repository = $this->manager->getRepository($class);
        if(!$repository){ return new Response(null,404); }
        if(!$repository instanceof DatatableRepository)
        {
            throw new \Exception("ategad exception 3ela 9ebel kola error");
        }

        $data = $repository->getDataWithRequest($request);

        if($actionsTemplate){
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

    public function generateExcel(Request $request, string $class,string $fileName = "excel.xlsx"): Response
    {
        $repository = $this->manager->getRepository($class);
        if(!$repository){ return new Response(null,404); }
        if(!$repository instanceof DatatableRepository)
        {
            throw new \Exception("ategad exception 3ela 9ebel kola error");
        }

        //get entity data by dataTable request columns & search
        $data = $repository->getTableResultWithRequest(
            $request->get('columns'),
            $request->get('order')
        );
        //visible rows
        $visibility = $request->get('visibility');

        $spreadsheet = new Spreadsheet();

        $this->exportExcelService->generateTable($spreadsheet->getSheet(0),$class,$data,$visibility);

        //generate xlsx file and attachment
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);
        // Return the excel file as an attachment
        $response = new BinaryFileResponse($temp_file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $fileName);
        return $response;
    }
}