<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function queryCommentsByNoteSlug(string $slug) : QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.note', 'n')
            ->addSelect('n')
            ->leftJoin('c.author', 'a')
            ->addSelect('a')
            ->andWhere('n.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('c.createdAt', 'DESC');
    }

    public function queryByCreatedAtDesc() : QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.note', 'n')
            ->leftJoin('c.author', 'a')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(10);
    }
}
