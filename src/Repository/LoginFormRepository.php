<?php

namespace App\Repository;

use App\Entity\LoginForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LoginForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginForm[]    findAll()
 * @method LoginForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LoginForm::class);
    }

    // /**
    //  * @return LoginForm[] Returns an array of LoginForm objects
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
    public function findOneBySomeField($value): ?LoginForm
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
