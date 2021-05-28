<?php

namespace App\Repository;

use App\Entity\RefreshToken;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RefreshTokenRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, RefreshToken::class);
    }

    public function findByToken(string $refreshToken): ?RefreshToken {
        return $this->findOneBy(['token' => $refreshToken]);
    }
}