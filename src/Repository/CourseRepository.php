<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function queryByTitleStudyAndYear(?string $term, string $study, string $year) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.study', 's')
            ->andWhere('s.title = :study')
            ->setParameter('study', $study)
            ->leftJoin('c.year', 'y')
            ->addSelect('y')
            ->andWhere('y.title = :year')
            ->setParameter('year', $year);

        if($term)
        {
            $qb->andWhere('LOWER(c.title) LIKE LOWER(:term) OR LOWER(c.title) LIKE LOWER(:term) OR LOWER(s.title) LIKE LOWER(:term) OR
            LOWER(y.title) LIKE LOWER(:term)')
                ->setParameter('term', '%'.$term.'%');
        }

        $qb->orderBy('c.title', 'ASC');
        return $qb;
    }
}
