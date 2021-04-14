<?php

namespace App\Service;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;

class CsvFileGenerator
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getFile(): Response
    {
        $fp = fopen('php://temp', 'w');
        $header = $this->getHeader($this->data[0]);
        fputcsv($fp, $header);
        foreach ($this->data as $fields) {
            $fields = $this->normalizeFields($fields);
            fputcsv($fp, $fields);
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="List.csv"');

        return $response;
    }

    private function normalizeFields($fields): array
    {
        if ($fields instanceof Task) {
            return [$fields->getId(), $fields->getTitle(), $fields->getComment(), $fields->getDate()->format('Y-m-d'), $fields->getTimeSpent()];
        }

        return $fields;
    }

    private function getHeader($fields): array
    {
        if ($fields instanceof Task) {
            return ['id', 'Title', 'Comment', 'Date', 'TimeSpent'];
        }
    }
}