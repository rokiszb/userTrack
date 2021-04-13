<?php


namespace App\Service;

use App\Entity\TaskList;

class ListService
{
    public function getTotalTime(?TaskList $list): int
    {
        $total = 0;

        if (empty($list)) {
            return 0;
        }

        foreach ($list->getTasks() as $task) {
            $total += $task->getTimeSpent();
        }

        return $total;
    }
}