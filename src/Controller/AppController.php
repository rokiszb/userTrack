<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Repository\TaskListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $taskListRepository;

    public function __construct(
        TaskListRepository $taskListRepository
    )
    {
        $this->taskListRepository = $taskListRepository;
    }
    /**
     * @Route("/list", name="list_view")
     */
    public function index(): Response
    {
        $list = $this->taskListRepository->findOneBy(['user' => $this->getUser()]);
        $form = $this->createForm(TaskType::class);
        dump($list);
        return $this->render('app/list/index.html.twig', [
            'task' => $form->createView(),
        ]);
    }
}
