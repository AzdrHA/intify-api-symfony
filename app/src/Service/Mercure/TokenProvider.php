<?php

namespace App\Service\Mercure;

use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;

class TokenProvider implements TokenFactoryInterface
{
    private string $secret;

    public function __construct(string $mercureJwtSecret)
    {
        $this->secret = $mercureJwtSecret;
    }

    public function create(array $subscribe = [], array $publish = [], array $additionalClaims = []): string
    {
        $configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            Key\InMemory::plainText($this->secret, '')
        );
        return ($configuration->builder())
            ->withClaim('mercure', ['publish' => ['*']])
            ->expiresAt(new \DateTimeImmutable("tomorrow"))
            ->getToken(new Sha256(), Key\InMemory::plainText($this->secret, ''))->toString();
    }
}