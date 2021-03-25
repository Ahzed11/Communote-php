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
}
