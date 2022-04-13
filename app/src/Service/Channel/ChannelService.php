<?php

namespace App\Service\Channel;

use App\Utils\UtilsNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ChannelService
{
    /**
     * @throws ExceptionInterface
     */
    public function serializeChannel(ArrayCollection|Collection $channels): array
    {
        $whiteList = ['id', 'type', 'name', 'topic', 'position', 'parent', 'recipients'];
        $res = [];
        foreach ($channels as $channel)
            $res[] = UtilsNormalizer::normalize($channel, [], [], $whiteList);

        return $res;
    }
}