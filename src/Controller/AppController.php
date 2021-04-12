<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $taskListRepository;
    private $entityManager;

    public function __construct(
        TaskListRepository $taskListRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/list", name="list_view")
     */
    public function index(Request $request): Response
    {
        $list = $this->taskListRepository->findOneBy(['user' => $this->getUser()]);
        $task = new Task();
        dump($list->getTasks());
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTaskList($this->getUser()->getTaskList());
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'Task created');
            $this->redirectToRoute('list_view');
        }

        return $this->render('app/list/index.html.twig', [
            'taskForm' => $form->createView(),
            'userList' => $list
        ]);
    }
}
