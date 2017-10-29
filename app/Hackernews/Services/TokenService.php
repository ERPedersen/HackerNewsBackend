<?php

namespace Hackernews\Services;

use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;

/**
 * Class TokenService
 *
 * @package Hackernews\Services
 */
class TokenService
{
    /**
     * @param String $jwt
     * @return bool|Token
     */
    public function decode(String $jwt)
    {
        try {
            return (new Parser)->parse($jwt);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token): bool
    {
        return ($token->verify($this->getSigner(), getenv("JWT_SECRET")));
    }

    /**
     * @param array $claims
     * @return string
     */
    public function sign(array $claims)
    {
        $builder = new Builder();

        foreach ($claims as $key => $value) {
            $builder->set($key, $value);
        }

        return (String) $builder->sign($this->getSigner(), getenv("JWT_SECRET"))->getToken();
    }

    /**
     * @return \Lcobucci\JWT\Signer\Hmac\Sha256
     */
    private function getSigner()
    {
        return new Sha256();
    }
}