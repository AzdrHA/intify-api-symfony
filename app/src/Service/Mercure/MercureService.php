<?php

namespace App\Service\Mercure;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercureService
{
    const CREATE_MESSAGE = 'channels/%s/messages';

    private HubInterface $hub;
    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    public function makeRequest(string $topic, array $data): void
    {
        $update = new Update(
            $topic,
            json_encode($data)
        );

        $this->hub->publish($update);
    }
}