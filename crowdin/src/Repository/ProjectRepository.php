<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[] Returns an array of Project objects
     */

    public function findByUserWithPagination($user, $page)
    {
        $limit = 5;

        $items = $this->createQueryBuilder('p')
            ->andWhere('p.userId=:val')
            ->andWhere('p.isDeleted = 0')
            ->setParameter('val', $user)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();
        $count = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.userId=:val')
            ->andWhere('p.isDeleted=0')
            ->setParameter('val', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return ['items' => $items, 'count' => ceil($count / $limit)];
    }

    public function findByIsTranslated($user, $page)
    {
        $limit = 3;

        $items = $this->createQueryBuilder('p')
            ->andWhere('p.userId=:val')
            ->andWhere('p.isTranslated > 0')
            ->andWhere('p.isDeleted = 0')
            ->setParameter('val', $user)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();
        $count = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.userId=:val')
            ->andWhere('p.isDeleted=0')
            ->andWhere('p.isTranslated > 0')
            ->setParameter('val', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return ['items' => $items, 'count' => ceil($count / $limit)];
    }

    public function findAll() {
        return $this->createQueryBuilder('p')
        ->select('p')
        ->andWhere('p.isDeleted = 0')
        ->getQuery()
        ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
