<?php

namespace App\Service\Channel;

use App\Entity\Channel\Channel;
use App\Entity\Guild\Guild;
use App\Service\DefaultService;
use App\Utils\UtilsStr;
use Symfony\Component\Form\FormFactoryInterface;

class ChannelService extends DefaultService
{
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
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