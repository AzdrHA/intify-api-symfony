<?php

namespace App\ServiceApi\Channel;

use App\Entity\Channel\Channel;
use App\Entity\Guild\Guild;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Channel\ChannelCreateType;
use App\Form\Guild\Channel\GuildChannelCreateType;
use App\Service\Channel\ChannelService as BaseChannelService;
use App\ServiceApi\DefaultService;
use App\Utils\UtilsStr;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ChannelService extends DefaultService
{
    private BaseChannelService $channelService;

    /**
     * @param FormFactoryInterface $formFactory
     * @param BaseChannelService $channelService
     */
    public function __construct(FormFactoryInterface $formFactory, BaseChannelService $channelService)
    {
        $this->formFactory = $formFactory;
        $this->channelService = $channelService;
    }

    /**
     * @throws ApiFormErrorException
     * @throws ExceptionInterface|ApiException
     */
    public function createGuildChannel(Request $request, Guild $guild): array
    {
        $channel = new Channel();

        $callback = function () use ($channel, $guild)
        {
            if (!$channel->isGuildChannel())
                throw new ApiException('Only Guild channel is available', 400, $channel->guildChannel());

            $channel->setGuild($guild);
        };

        $this->handleForm($request, GuildChannelCreateType::class, $channel, $callback);

        return $this->channelService->serializeChannel($channel);
    }

    /**
     * @param Guild $guild
     * @return void
     */
    public function initializeDefaultChannel(Guild $guild): void
    {
        foreach ([Channel::GUILD_TEXT, Channel::GUILD_VOICE] as $channel)
        {
            $parent = new Channel();
            $parent->setName(UtilsStr::ucFirst(Channel::inverse_type[$channel]). ' Channel');
            $parent->setType(Channel::GUILD_CATEGORY);
            $guild->addChannel($parent);

            $child = new Channel();
            $child->setName('General');
            $child->setType($channel);
            $child->setParent($parent);
            $guild->addChannel($child);
        }
    }
}