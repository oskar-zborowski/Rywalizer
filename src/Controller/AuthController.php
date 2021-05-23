<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\RefreshToken;
use App\Repository\RefreshTokenRepository;
use App\Service\CakeEncoder;
use App\Security\JWTGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class AuthController extends AbstractController {

    private $entityManager;

    private $cakeEncoder;

    private $jwtGenerator;

    private $jwtTTL;

    private $refreshTokenTTL;

    public function __construct(
        EntityManagerInterface $entityManager,
        JWTGenerator $jwtGenerator,
        int $jwtTTL,
        int $refreshTokenTTL
    ) {
        $this->entityManager = $entityManager;
        $this->jwtGenerator = $jwtGenerator;
        $this->jwtTTL = $jwtTTL;
        $this->refreshTokenTTL = $refreshTokenTTL;
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    public function login(Request $request, UserProviderInterface $userProvider): Response {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        if (!$username || !$password) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

        $encodedUsername = $this->cakeEncoder->encode($username);

        try {
            $user = $userProvider->loadUserByUsername($encodedUsername);
        } catch (UsernameNotFoundException $e) {
            throw new UnauthorizedHttpException('User not found');
        }

        $encodedPassword = $this->cakeEncoder->hash($password);

        if ($encodedPassword !== $user->getPassword()) {
            throw new UnauthorizedHttpException('Invalid password');
        }

        $accessToken = $this->jwtGenerator->genereateAccessToken($user);
        $refreshToken = $this->jwtGenerator->generateRefreshToken();

        /**
         * @var App\Entity\User $user
         */
        $refreshTokenEntity = $user->getRefreshToken();

        if (!$refreshTokenEntity) {
            $refreshTokenEntity = new RefreshToken();
        }

        $refreshTokenEntity->setIssuedAt(new DateTime());
        $refreshTokenEntity->setExpiresAt(
            (new DateTime())->add(new DateInterval('P' . $this->refreshTokenTTL . 'D'))
        );
        $refreshTokenEntity->setToken($refreshToken);
        $refreshTokenEntity->setUser($user);

        $this->entityManager->persist($refreshTokenEntity);
        $this->entityManager->flush();

        return $this->json([
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'expires' => $this->jwtTTL
        ]);
    }

    /**
     * @Route("/refresh", methods={"POST"})
     */
    public function refresh(Request $request, RefreshTokenRepository $tokenRepository): Response {
        $refreshToken = $request->request->get('refresh-token');

        if (!$refreshToken) {
            throw new UnauthorizedHttpException('Invalid token');
        }

        $refreshTokenEntity = $tokenRepository->findByToken($refreshToken);

        if (!$refreshTokenEntity) {
            throw new UnauthorizedHttpException('Invalid token');
        }

        $user = $refreshTokenEntity->getUser();

        //TODO: sprawdzenie czy user istnieje ??

        if ($refreshTokenEntity->getExpiresAt() < new DateTime()) {
            throw new UnauthorizedHttpException('Refresh token is expired');
        }

        return $this->json([
            'accessToken' => $this->jwtGenerator->genereateAccessToken($user),
            'expires' => $this->jwtTTL
        ]);
    }
}
