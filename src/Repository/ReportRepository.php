<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function queryReportsByAuthorOrNote(string $term) : QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.note', 'n')
            ->addSelect('n')
            ->leftJoin('r.author', 'a')
            ->addSelect('a')
            ->andWhere('LOWER(n.title) LIKE LOWER(:term) OR LOWER(a.firstName) LIKE LOWER(:term) 
                        OR LOWER(a.lastName) LIKE LOWER(:term)')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('r.createdAt', 'DESC');
    }

    public function queryByCreatedAtDesc() : QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.note', 'n')
            ->leftJoin('r.author', 'a')
            ->orderBy('r.createdAt', 'DESC');
    }
}
