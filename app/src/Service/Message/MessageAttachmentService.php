<?php

namespace App\Service\Message;

use App\Entity\Message\MessageAttachment;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class MessageAttachmentService
{
    const serializeWhitelist = [
        'id', 'clientName', 'path', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeMessageAttachment(MessageAttachment $attachment): array
    {
        return UtilsNormalizer::normalize($attachment, [], [], self::serializeWhitelist);
    }
}