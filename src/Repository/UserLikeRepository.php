<?php

namespace App\Repository;

use App\Entity\UserLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLike[]    findAll()
 * @method UserLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLikeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLike::class);
    }

    /**
     * @return int
     */
    public function findByLiked()
    {
        $qb =  $this->createQueryBuilder('u')
            ->andWhere('u.likeOn = :val')
            ->setParameter('val', true)
            ->getQuery()
            ->getResult()
        ;

        return count($qb);
    }


    /*
    public function findOneBySomeField($value): ?UserLike
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
