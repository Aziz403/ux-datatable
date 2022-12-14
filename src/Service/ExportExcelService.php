<?php

namespace Aziz403\UX\Datatable\Service;

use Aziz403\UX\Datatable\Entity\DataTableInterface;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ExportExcelService{

    private DataProcess $dataProcess;

    public function __construct(DataProcess $dataProcess)
    {
        $this->dataProcess = $dataProcess;
    }

    public function generateInfo(Worksheet $sheet,array $data,int &$y = 1,int &$x = 1):Worksheet
    {
        foreach ($data as $title=>$info){
            $this->headerCellStyle($sheet,$x,$y)->setValue($title);
            $this->tableCellStyle($sheet,$x+1,$y,120)->setValue($info ?? ' ');
            $y++;
        }
        $x++;
        return $sheet;
    }

    public function generateTable(Worksheet $sheet,string $entityClass,array $dataTable,array $visibleRows,int &$y = 1,int &$x = 1):Worksheet
    {
        //$entityClass = $this->dataProcess->getEntityNameFromLower($entityClass);
        $initX = $x;
        $this->generateTableHeader($sheet,$visibleRows,$y,$x);
        $x = $initX;// nereje3o x l init dyalha bax tebda men lwl 3awtani
        $this->generateTableRows($sheet,$entityClass,$dataTable,$visibleRows,$y,$x);
        return $sheet;
    }

    private function generateTableHeader(Worksheet &$sheet,array $cells,int &$y,int &$x)
    {
        foreach ($cells as $name=>$val) {
            if($val==="true" && $name!="action") {
                //add cell value with header Style
                $this->headerCellStyle($sheet,$x,$y)->setValue($name);
                //increment by x
                $x++;
            }
        }
        $y++;
    }

    private function generateTableRows(Worksheet &$sheet,string $entityClass,array $data,array $cells,int &$y,int &$x)
    {
        $initX = $x;
        foreach ($data as $row) {
            $keys = array_keys($row);
            foreach ($keys as $key) {
                if($cells[$key]=="true"){
                    $column = call_user_func_array(array($entityClass, 'getDataTableColumn'), array($key));
                    if(isset($column['format']))
                    {//hena ila kant 3endo xi format aneteb9oha
                        switch ($column['format'])
                        {
                            case DataTableInterface::COLUMN_FORMAT_MONEY:
                                $this->tableCellStyle($sheet,$x,$y)
                                    ->setValue(($row[$key] ?? ' '))
                                    ->getStyle()->getNumberFormat()->setFormatCode('#,##0.00_-"DH"');
                                break;
                            case DataTableInterface::COLUMN_FORMAT_DATE:
                                $this->tableCellStyle($sheet,$x,$y)->setValue($row[$key] ? $row[$key]->format('Y-m-d'):' ');
                                break;
                            case DataTableInterface::COLUMN_FORMAT_BUDGE:
                                $this->tableCellStyle($sheet,$x,$y)->setValue($row[$key] ? 'A':'D');
                                break;
                        }
                    }
                    else{
                        //add cell value with Style
                        $this->tableCellStyle($sheet,$x,$y)->setValue($row[$key] ?? ' ');
                    }
                    //increment by x
                    $x++;
                }
            }
            $x = $initX;
            $y++;
        }
    }

    private function headerCellStyle(Worksheet &$sheet,int $x,int $y,string $bgColor = 'FFEDEDED',int $width = 80):Cell
    {
        $alfa = Coordinate::stringFromColumnIndex($x);
        $cell = $sheet->getStyle($alfa.$y);
        $cell->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
        $cell->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER_CONTINUOUS);
        $cell->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $cell->getBorders()->getAllBorders()->setBorderStyle(true);
        $cell->getFont()->setBold(true);

        $sheet->getColumnDimension($alfa)->setWidth($width, 'pt');
        return $sheet->getCell($alfa.$y);
    }

    private function tableCellStyle(Worksheet &$sheet,int $x,int $y,int $width = 80,string $bgColor = 'FFFFFFFF'):Cell
    {
        $alfa = Coordinate::stringFromColumnIndex($x);
        $cell = $sheet->getStyle($alfa.$y);
        $cell->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bgColor);
        $cell->getBorders()->getAllBorders()->setBorderStyle(true);

        $sheet->getColumnDimension($alfa)->setWidth($width, 'pt');
        return $sheet->getCell($alfa.$y);
    }


}