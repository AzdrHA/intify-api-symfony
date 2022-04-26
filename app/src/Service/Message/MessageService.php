<?php

namespace App\Service\Message;

use App\Entity\Message\Message;
use App\Service\Guild\GuildService;
use App\Utils\UtilsNormalizer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class MessageService
{
    private MessageAttachmentService $messageAttachmentService;
    public function __construct(MessageAttachmentService $messageAttachmentService)
    {
        $this->messageAttachmentService = $messageAttachmentService;
    }
    const serializeWhitelist = [
        'id', 'owner', 'channel', 'content', 'createdAt', 'updatedAt'
    ];

    /**
     * @throws ExceptionInterface
     */
    public function serializeMessage(Message $message): array
    {
        $res = UtilsNormalizer::normalize($message, [], [], self::serializeWhitelist);

        foreach ($message->getMessageAttachments() as $attachment) {
            $res['messageAttachments'][] = $this->messageAttachmentService->serializeMessageAttachment($attachment);
        }
        return $res;
    }
}