<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/list", name="api_list")
 */
class TaskListController extends AbstractController
{
    private $entityManager;
    private $taskRepository;
    private $taskListRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository,
        TaskListRepository $taskListRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @Route("/create", name="api_task_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent());
//
//        $form = $this->createForm(TaskType::class);
//        $form->submit((array)$content);
//
//        if (!$form->isValid()) {
//            $errors = [];
//            foreach ($form->getErrors(true, true) as $error) {
//                $propertyName = $error->getOrigin()->getName();
//                $errors[$propertyName] = $error->getMessage();
//            }
//            return $this->json([
//                'message' => ['text' => join("\n", $errors), 'level' => 'error'],
//            ]);
//
//        }
//
//        $todo = new Task();
//
//        $todo->setTask($content->task);
//        $todo->setDescription($content->description);
//
//        try {
//            $this->entityManager->persist($todo);
//            $this->entityManager->flush();
//        } catch (UniqueConstraintViolationException $exception) {
//            return $this->json([
//                'message' => ['text' => 'Task has to be unique!', 'level' => 'error']
//            ]);
//
//        }
//        return $this->json([
//            'todo'    => $todo->toArray(),
//            'message' => ['text' => 'To-Do has been created!', 'level' => 'success']
//        ]);
    }
}
