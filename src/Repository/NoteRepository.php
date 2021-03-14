<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function queryByTitleAndCourse(?string $term, string $course) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('n')
            ->leftJoin('n.course', 'c')
            ->addSelect('c')
            ->leftJoin('n.author', 'a')
            ->addSelect('a')
            ->andWhere('c.title LIKE :course')
            ->setParameter('course', '%'.$course.'%');

        if($term)
        {
            $qb->andWhere('LOWER(n.title) LIKE LOWER(:term)')
                ->setParameter('term', '%'.$term.'%');
        }

        $qb->orderBy('n.createdAt', 'DESC');
        return $qb;
    }

    public function getNoteBySlug(string $slug) : Note
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.course', 'c')
            ->addSelect('c')
            ->leftJoin('n.author', 'a')
            ->addSelect('a')
            ->andWhere('n.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
