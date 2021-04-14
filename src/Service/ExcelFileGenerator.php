<?php

namespace App\Service;

use App\Entity\Task;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelFileGenerator
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getFile()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $this->setHeader($sheet);
        // Increase row cursor after header write
        $sheet->fromArray($this->getPreparedData(), null, 'A2', true);

        $writer = new Xlsx($spreadsheet);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="list.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }

    private function setHeader($sheet)
    {
        $sheet->setTitle('List');
        $sheet->getCell('A1')->setValue('id');
        $sheet->getCell('B1')->setValue('Title');
        $sheet->getCell('C1')->setValue('Comment');
        $sheet->getCell('D1')->setValue('Date');
        $sheet->getCell('E1')->setValue('TimeSpent');
    }

    private function getPreparedData(): array
    {
        $list = [];
        /** @var Task $task */
        foreach ($this->data as $task) {
            $list[] = [
                $task->getId(),
                $task->getTitle(),
                $task->getComment(),
                $task->getDate()->format('Y-m-d'),
                $task->getTimeSpent()
            ];
        }

        return $list;
    }
}