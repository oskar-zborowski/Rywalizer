<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ExceptionController extends AbstractController {

    public function showError(FlattenException $exception): Response {
        $isProduction = strtolower($this->getParameter('kernel.environment')) == 'prod';

        if ($isProduction) {
            return $this->createProdResponse($exception);
        } else {
            return $this->createDevResponse($exception);
        }
    }

    private function createProdResponse(FlattenException $exception): Response {
        // Wyświetlanie wiadomości tylko ze zdefiniowanych typów wyjątków. W każdym innym przypadku
        // jakaś defaultowa wiadomość: 'Error occured' czy cos tego typu

        return $this->json([
            'code' => $exception->getStatusCode(),
            'error' => $exception->getMessage()
        ], $exception->getStatusCode());
    }

    private function createDevResponse(FlattenException $exception): Response {
        return $this->json([
            'code' => $exception->getStatusCode(),
            'error' => $exception->getMessage(),
            'errorDetails' => $exception
        ], $exception->getStatusCode());
    }

}
