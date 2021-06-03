<?php

namespace App\Security;

use DateInterval;
use DateTime;
use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTGenerator {

    private $jwtSecret;
    private $jwtTTL;

    public function __construct(string $jwtSecret, int $jwtTTL) {
        $this->jwtSecret = $jwtSecret;
        $this->jwtTTL = $jwtTTL;
    }

    /**
     * Generate access token (JWT)
     *
     * @param UserInterface $user
     * @return string
     */
    public function genereateAccessToken(UserInterface $user): string {
        $issuedAt = new DateTime();
        $expirationTime = (new DateTime())->add(new DateInterval('PT' . $this->jwtTTL . 'S'));

        $payload = [
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expirationTime->getTimestamp(),
            'sub' => $user->getUsername(),
            'roles' => $user->getRoles()
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    /**
     * Generate refresh token
     *
     * @return string
     */
    public function generateRefreshToken(int $length = 40): string {
        return bin2hex(random_bytes($length));
    }
}