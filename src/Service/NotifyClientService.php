<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class NotifyClientService
{
    public function notify(string $notifyUrl, array $notifyRequest): void
    {
        $client = HttpClient::create();

        $client->request('POST', $notifyUrl, ['query' => $notifyRequest]);
    }
}