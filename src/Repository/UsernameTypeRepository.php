<?php

namespace App\Repository;

use App\Entity\UsernameType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsernameType|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsernameType|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsernameType[]    findAll()
 * @method UsernameType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsernameTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsernameType::class);
    }

    // /**
    //  * @return UsernameType[] Returns an array of UsernameType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsernameType
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
