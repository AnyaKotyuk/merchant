<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SignatureService
{
    private const SECRET_KEY = 'IRNUALP';
    private const DATA_CONCATENATE = ':';

    public function createSign(?float $amount, ?string $currency, ?string $callbackUrl): string
    {
        if (!$amount || !$currency || !$callbackUrl) {
            throw new BadRequestHttpException();
        }

        $userData = $amount . self::DATA_CONCATENATE . $currency . self::DATA_CONCATENATE . $callbackUrl;

        return sha1($userData. self::DATA_CONCATENATE . self::SECRET_KEY);
    }
}