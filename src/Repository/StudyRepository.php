<?php

namespace App\Repository;

use App\Entity\Study;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Study|null find($id, $lockMode = null, $lockVersion = null)
 * @method Study|null findOneBy(array $criteria, array $orderBy = null)
 * @method Study[]    findAll()
 * @method Study[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Study::class);
    }

    public function queryByTitleAndFaculty(?string $term, string $faculty) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('s');

        $qb->leftJoin('s.faculty', 'f')
            ->andWhere('f.title = :faculty')
            ->setParameter('faculty', $faculty);

        if ($term) {
            $qb->andWhere('LOWER(s.title) LIKE LOWER(:term)')
                ->setParameter('term', '%'.$term.'%');
        }

        $qb->orderBy('s.title', 'ASC');
        return $qb;
    }
}
