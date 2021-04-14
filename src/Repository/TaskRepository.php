<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getUserTasks(?UserInterface $user): QueryBuilder
    {
        $qb = $this->createQueryBuilder('task');
        $qb->andWhere('task.taskList = :user')
            ->setParameter('user', $user->getTaskList())
        ;

        return $qb
            ->orderBy('task.id', 'DESC')
            ;
    }

    public function getByDate(\DateTime $dateFrom, \DateTime $dateTo, UserInterface $user)
    {
        $qb = $this->createQueryBuilder('task');
        $qb
            ->andWhere('task.taskList = :userList')
            ->andWhere('task.date BETWEEN :dateFrom AND :dateTo')
            ->setParameter('dateFrom', $dateFrom->format('Y-m-d'))
            ->setParameter('dateTo', $dateTo->format('Y-m-d'))
            /** @var $user User  */
            ->setParameter('userList', $user->getTaskList())
        ;

        return $qb->getQuery()->execute();
    }
}
