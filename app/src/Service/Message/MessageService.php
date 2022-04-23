<?php

namespace App\Service\Message;

use App\Entity\Message\Message;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class MessageService
{
    const serializeWhitelist = [
        'id', 'owner', 'channel', 'content', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeMessage(Message $message): array
    {
        return UtilsNormalizer::normalize($message, [], [], self::serializeWhitelist);
    }
}