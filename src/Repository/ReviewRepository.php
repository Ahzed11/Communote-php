<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getByNoteSlugAndUser(string $slug, int $id) : ?Review {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.note', 'n')
            ->addSelect('n')
            ->leftJoin('r.author', 'a')
            ->addSelect('a')
            ->andWhere('n.slug = :slug')
            ->setParameter('slug', $slug)
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function queryByCreatedAtDesc() : QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.note', 'n')
            ->leftJoin('r.author', 'a')
            ->orderBy('r.createdAt', 'DESC');
    }

    public function getReviewsGroupedByScore()
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id) AS counter, r.score')
            ->orderBy('r.score')
            ->groupBy('r.score')
            ->getQuery()
            ->getResult();
    }

    public function getAverageOfNotesByUserId(int $userId) : float
    {
        $result = $this->createQueryBuilder('r')
            ->leftJoin('r.note', 'n')
            ->leftJoin('n.author', 'a')
            ->andWhere('a.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        $sum = 0;
        foreach ($result as $value) {
            $sum += $value->getScore();
        }

        return $sum / sizeof($result);
    }
}
