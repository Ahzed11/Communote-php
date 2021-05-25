<?php

namespace App\Repository;

use App\Entity\Download;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Download|null find($id, $lockMode = null, $lockVersion = null)
 * @method Download|null findOneBy(array $criteria, array $orderBy = null)
 * @method Download[]    findAll()
 * @method Download[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Download::class);
    }

    public function queryByCreatedAtDesc() : QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.note', 'n')
            ->leftJoin('d.author', 'a')
            ->orderBy('d.createdAt', 'DESC');
    }

    public function getNumberOfDownloadByDate()
    {
        return $this->createQueryBuilder('d')
            ->select('DATE(d.createdAt) AS date, COUNT(d.id) AS counter')
            ->orderBy('date')
            ->groupBy('date')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Download[] Returns an array of Download objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Download
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
