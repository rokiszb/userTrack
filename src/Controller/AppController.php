<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskListRepository;
use App\Service\ListService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $taskListRepository;
    private $entityManager;
    private $listService;

    public function __construct(
        TaskListRepository $taskListRepository,
        EntityManagerInterface $entityManager,
        ListService $listService
    )
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
        $this->listService = $listService;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('list_view');
        }
    }

    /**
     * @Route("/list", name="list_view")
     */
    public function index(Request $request): Response
    {
        $list = $this->taskListRepository->findOneBy(['user' => $this->getUser()]);
        $totalTime = $this->listService->getTotalTime($list);
        $task = new Task();

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
            'userList' => $list,
            'totalTime' => $totalTime
        ]);
    }
}
