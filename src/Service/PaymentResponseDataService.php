<?php

namespace App\Service;

class PaymentResponseDataService
{
    public function getErrorResponse(string $errorMsg): array
    {
        return ['error' => $errorMsg];
    }

    public function getSuccessResponse(array $paymentData): array
    {
        return [
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'signature' => $paymentData['signature'],
        ];
    }
}