<?php

namespace App\Http\Services\Tokens;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Configuration;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use App\Http\Controllers\CommonController;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;

class lcobucci extends CommonController
{
    private Configuration $config;

    function __construct() {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded(env('JWT_LCOBUCCI')));
    }

    function GenerateToken($claims): String {
        $time = new \DateTimeImmutable();
        $time = $time->format('Y-m-d H:i:s');

        $now = new  \DateTimeImmutable($time);

        $token = $this->config->builder()
            ->issuedBy(env('APP_URL'))
            ->permittedFor(env('APP_URL'))
            ->identifiedBy($claims['ci'])
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('1 day'))
            ->withClaim('uid', $claims)
            ->getToken($this->config->signer(), $this->config->signingKey());

        $token->headers();
        $token->claims();

        return $token->toString();
    }

    function ValidateToken($token) {
        $clock = new FrozenClock(new \DateTimeImmutable());

        $signer = $this->config->signer();
        $key    = $this->config->verificationKey();
        $parse  = $this->config->parser()->parse($token);

        $constraints = [
            new IssuedBy(env('APP_URL')),
            new PermittedFor(env('APP_URL')),
            new ValidAt($clock),
            new SignedWith($signer, $key),
        ];

        if (!$this->config->validator()->validate($parse, ...$constraints)) throw new \Exception($this->getConstantsTranslate('UNAUTHORIZED'));

        return $parse->claims()->get('uid');
    }
}
