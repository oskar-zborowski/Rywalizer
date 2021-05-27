<?php

namespace App\Security;

use Exception;
use Firebase\JWT\JWT;
use App\Service\EncodeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @see https://symfony.com/doc/current/security/guard_authentication.html
 */
class JWTAuthenticator extends AbstractGuardAuthenticator {

    private $jwtSecret;
    private $tokenExtractor;
    private $encodeService;

    public function __construct(string $jwtSecret) {
        $this->jwtSecret = $jwtSecret;
        $this->tokenExtractor = new JWTExtractor('Authorization', 'Bearer');
        $this->cakeEncoder = new EncodeService();
    }

    public function supports(Request $request) {
        return $this->tokenExtractor->checkRequest($request);
    }

    public function getCredentials(Request $request) {
        return $this->tokenExtractor->extract($request);
    }

    /**
     * @param string $jwt
     */
    public function getUser($jwt, UserProviderInterface $userProvider) {
        if (null === $jwt) {
            return null;
        }

        try {
            $decodedJwt = (array) JWT::decode($jwt, $this->jwtSecret, ['HS256']);

            if (!isset($decodedJwt['sub'])) {
                throw new AuthenticationException('Invalid payload');
            }

            $encoderUsername = $this->encodeService->encode($decodedJwt['sub']);
            $user = $userProvider->loadUserByUsername($encoderUsername);
        } catch (Exception $e) {
            //TODO: catcha mozna rozbic na rozne wyrzucane wyjątki (JWT::decode())
            throw new AuthenticationException($e->getMessage());
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        //TODO: Mozna dorobić eventy jeżeli bedzie potrzeba
        throw $exception;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        //TODO: Mozna dorobić eventy jeżeli bedzie potrzeba
        throw new UnauthorizedHttpException('Authentication Required');
    }

    public function supportsRememberMe() {
        return false;
    }
}