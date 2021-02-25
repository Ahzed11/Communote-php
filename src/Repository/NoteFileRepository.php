<?php

namespace App\Repository;

use App\Entity\NoteFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoteFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteFile[]    findAll()
 * @method NoteFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteFile::class);
    }

    // /**
    //  * @return NoteFile[] Returns an array of NoteFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NoteFile
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
