<?php

namespace App\Service\Channel;

use App\Entity\Channel\Channel;
use App\Manager\Guild\GuildManager;
use App\Service\DefaultService;
use App\Service\User\UserService;
use Symfony\Component\Form\FormFactoryInterface;

class ChannelService extends DefaultService
{
    public function __construct(FormFactoryInterface $formFactory,)
    {
        $this->formFactory = $formFactory;
    }

    private function initializeDefaultChannel(object $guild)
    {
        foreach ([Channel::GUILD_TEXT, Channel::GUILD_VOICE] as $channel)
        {
            dump($channel);
        }
    }
}