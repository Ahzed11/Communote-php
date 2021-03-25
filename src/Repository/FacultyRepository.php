<?php

namespace App\Repository;

use App\Entity\Faculty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Faculty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faculty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faculty[]    findAll()
 * @method Faculty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacultyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faculty::class);
    }

    public function queryByTitle(?string $term) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('f');
        if ($term) {
            $qb->andWhere('LOWER(f.title) LIKE LOWER(:term)')
                ->setParameter('term', '%'.$term.'%');
        }
        $qb->orderBy('f.title', 'ASC');
        return $qb;
    }

    public function queryAlphabetically() : QueryBuilder
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.title', 'ASC')
            ->setMaxResults(10);
    }
}
