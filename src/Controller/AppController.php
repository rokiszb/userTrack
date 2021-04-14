<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
use App\Service\ListService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $taskListRepository;
    private $entityManager;
    private $listService;
    private $taskRepository;

    public function __construct(
        TaskListRepository $taskListRepository,
        EntityManagerInterface $entityManager,
        ListService $listService,
        TaskRepository $taskRepository
    )
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
        $this->listService = $listService;
        $this->taskRepository = $taskRepository;
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
     * @Template("app/list/index.html.twig")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $list = $this->taskListRepository->findOneBy(['user' => $this->getUser()]);
        $totalTime = $this->listService->getTotalTime($list);
        $task = new Task();

        $queryBuilder = $this->taskRepository->getUserTasks($this->getUser());
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1),
            10
        );

        dump($pagination);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTaskList($this->getUser()->getTaskList());
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'Task created');
            return $this->redirectToRoute('list_view');
        }

        return $this->render('app/list/index.html.twig', [
            'taskForm' => $form->createView(),
            'userList' => $list,
            'totalTime' => $totalTime,
            'pagination' => $pagination,
        ]);
    }
}
