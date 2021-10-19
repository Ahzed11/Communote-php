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
            ->leftJoin('n.reviews', 'r')
            ->addSelect('r')
            ->andWhere('c.title LIKE :course')
            ->setParameter('course', '%'.$course.'%');

        if($term)
        {
            $qb->andWhere('LOWER(n.title) LIKE LOWER(:term)')
                ->setParameter('term', '%'.$term.'%');
        }

        $qb->addOrderBy('n.wroteAt', 'DESC')
            ->addOrderBy('n.createdAt', 'DESC');
        return $qb;
    }

    public function queryByTitle(string $term) : QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.course', 'c')
            ->addSelect('c')
            ->leftJoin('n.author', 'a')
            ->addSelect('a')
            ->leftJoin('n.reviews', 'r')
            ->addSelect('r')
            ->andWhere('LOWER(n.title) LIKE LOWER(:term)')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('n.title', 'ASC');
    }

    public function queryByUser(string $email) : QueryBuilder
    {
        $qb = $this->createQueryBuilder('n')
            ->leftJoin('n.course', 'c')
            ->addSelect('c')
            ->leftJoin('n.author', 'a')
            ->addSelect('a')
            ->leftJoin('n.reviews', 'r')
            ->addSelect('r')
            ->andWhere('a.email = :email')
            ->setParameter('email', $email);

        $qb->orderBy('n.createdAt', 'DESC');
        return $qb;
    }

    public function getNoteBySlug(string $slug) : ?Note
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.author', 'a')
            ->addSelect('a')
            ->leftJoin('n.course', 'c')
            ->addSelect('c')
            ->leftJoin('c.study', 's')
            ->addSelect('s')
            ->leftJoin('s.faculty', 'f')
            ->addSelect('f')
            ->andWhere('n.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function queryByCreatedAtDesc() : QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.course', 'c')
            ->leftJoin('n.author', 'a')
            ->orderBy('n.createdAt', 'DESC');
    }

    public function queryByWroteAtDesc() : QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->leftJoin('n.course', 'c')
            ->leftJoin('n.author', 'a')
            ->orderBy('n.wrote_at', 'DESC');
    }

    public function getNumberOfDownloadByDate()
    {
        return $this->createQueryBuilder('n')
            ->select('DATE(n.createdAt) AS date, COUNT(n.id) AS counter')
            ->orderBy('date')
            ->groupBy('date')
            ->getQuery()
            ->getResult();
    }
}
