<?php

namespace App\Service\Channel;

use App\Entity\Channel\Channel;
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
        /*
         * foreach ($channels as $channel) {
            if (!$channel->getParent()){
                $res[] = UtilsNormalizer::normalize($channel, [], [], $whiteList);
            }
            $t = $channel->getParent() ? array_search($channel->getParent()->getId(), array_column($res, 'id')) : null;
            if ($channel->getParent() && $res[$t]) $res[$t]['children'][] = UtilsNormalizer::normalize($channel, [], [], $whiteList);
        }
         */
        foreach ($channels as $channel)
            $res[] = UtilsNormalizer::normalize($channel, [], [], $whiteList);

        return $res;
    }
}