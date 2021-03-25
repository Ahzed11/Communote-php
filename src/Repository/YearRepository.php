<?php

namespace App\Repository;

use App\Entity\Year;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Year|null find($id, $lockMode = null, $lockVersion = null)
 * @method Year|null findOneBy(array $criteria, array $orderBy = null)
 * @method Year[]    findAll()
 * @method Year[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Year::class);
    }

    public function queryByTitle(?string $term) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('y');
        if ($term) {
            $qb->andWhere('LOWER(y.title) LIKE LOWER(:term)')
                ->setParameter('term', '%'.$term.'%');
        }
        $qb->orderBy('y.title', 'ASC');
        return $qb;
    }

    public function queryAlphabetically() : QueryBuilder
    {
        return $this->createQueryBuilder('y')
            ->orderBy('y.title', 'ASC')
            ->setMaxResults(10);
    }
}
