<?php

namespace App\Repository;

use App\Entity\BlogArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogArticle[]    findAll()
 * @method BlogArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogArticle::class);
    }

    /**
     * @param BlogArticle $entity
     * @param bool $flush
     */
    public function add(BlogArticle $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param BlogArticle $entity
     * @param bool $flush
     */
    public function remove(BlogArticle $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $category
     * @return float|int|mixed|string
     */
    public function showByCategory($category): mixed
    {
        return $this->createQueryBuilder('cat')
            ->andWhere('cat.Category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->execute();
    }
}