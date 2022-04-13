<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class AuthService
{
    private JWTEncoderInterface $encoder;
    private RefreshTokenManagerInterface $refreshToken;
    public function __construct(JWTEncoderInterface $encoder, RefreshTokenManagerInterface $refreshToken)
    {
        $this->encoder = $encoder;
        $this->refreshToken = $refreshToken;
    }

    public function createToken(User $user): string
    {
        return $this->encoder->encode([
            'email' => $user->getEmail(),
        ]);
    }
}