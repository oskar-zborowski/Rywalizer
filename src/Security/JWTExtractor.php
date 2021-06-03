<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

class JWTExtractor {

    private $headerName;
    private $prefix;

    public function __construct(string $headerName, string $prefix) {
        $this->headerName = $headerName;
        $this->prefix = $prefix;
    }

    public function checkRequest(Request $request) {
        return $request->headers->has($this->headerName);
    }

    public function extract(Request $request) {
        if (!$this->checkRequest($request)) {
            return null;
        }

        $authorizationHeader = $request->headers->get($this->headerName);

        if (empty($this->prefix)) {
            return $authorizationHeader;
        }

        $headerParts = explode(' ', $authorizationHeader);

        if (!(2 === count($headerParts) && 0 === strcasecmp($headerParts[0], $this->prefix))) {
            return null;
        }

        return $headerParts[1];
    }
}