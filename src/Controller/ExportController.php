<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\ExportType;
use App\Repository\TaskRepository;
use App\Service\CsvFileGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class ExportController extends AbstractController
{
    /**
     * @Route("/export", name="export", methods={"POST"})
     * @param Request $requests
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

//            dump($encoded);
            return $this->getExportFileFormat($type, $data);
        }


       // return $this->redirectToRoute('list_view');
    }

    /**
     * @param string $type
     * @param Task[] $data
     */
    private function getExportFileFormat(string $type, array $data): Response
    {
        switch ($type) {
            case 'csv':
                $csv = new CsvFileGenerator($data);
                return $csv->getFile();
        }
    }
}
