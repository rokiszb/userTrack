<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\ExportType;
use App\Repository\TaskRepository;
use App\Service\CsvFileGenerator;
use App\Service\ExcelFileGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    /**
     * @Route("/export", name="export", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\TaskRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportController(Request $request, TaskRepository $repository)
    {
        $form = $this->createForm(ExportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $dateFrom = $data['dateFrom'];
            $dateTo = $data['dateTo'];
            $type = $data['type'];

            $data = $repository->getByDate($dateFrom, $dateTo, $this->getUser());

            return $this->getExportFileFormat($type, $data);
        }

        return $this->redirectToRoute('list_view');
    }

    /**
     * @param string $type
     * @param Task[] $data
     * @return Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function getExportFileFormat(string $type, array $data)
    {
        switch ($type) {
            case 'csv':
                $csv = new CsvFileGenerator($data);
                return $csv->getFile();
            case 'xlsx':
                $xls = new ExcelFileGenerator($data);
                return $xls->getFile();
        }
    }
}
