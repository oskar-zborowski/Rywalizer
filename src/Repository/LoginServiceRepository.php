<?php

namespace App\Repository;

use App\Entity\LoginService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoginService|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginService|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginService[]    findAll()
 * @method LoginService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginService::class);
    }

    // /**
    //  * @return LoginService[] Returns an array of LoginService objects
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
    public function findOneBySomeField($value): ?LoginService
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
