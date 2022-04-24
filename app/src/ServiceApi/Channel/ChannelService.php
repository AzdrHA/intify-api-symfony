<?php

namespace App\ServiceApi\Channel;

use App\Entity\Channel\Channel;
use App\Entity\Guild\Guild;
use App\Exception\ApiException;
use App\Exception\ApiFormErrorException;
use App\Form\Guild\Channel\GuildChannelCreateType;
use App\Form\User\Channel\UserPrivateChannelCreateType;
use App\Manager\Channel\ChannelManager;
use App\Service\Channel\ChannelService as BaseChannelService;
use App\ServiceApi\DefaultService;
use App\ServiceApi\User\UserService;
use App\Utils\UtilsNormalizer;
use App\Utils\UtilsStr;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ChannelService extends DefaultService
{
    private BaseChannelService $channelService;
    private ChannelManager $channelManager;
    private UserService $userService;

    public function __construct(
        FormFactoryInterface $formFactory, BaseChannelService $channelService, ChannelManager $channelManager, UserService $userService
    )
    {
        $this->formFactory = $formFactory;
        $this->channelService = $channelService;
        $this->channelManager = $channelManager;
        $this->userService = $userService;
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

        return $this->channelService->serializeChannel(new ArrayCollection([$channel]));
    }

    /**
     * @throws ExceptionInterface
     * @throws ApiFormErrorException
     * @throws ApiException
     */
    public function createPrivateMessage(Request $request): array
    {
        $channel = new Channel();

        $callback = function () use ($channel)
        {
            $channel->setType(Channel::DM);
            if (count($channel->getRecipients()) < 2)
                throw new ApiException('Recipents needs 2 users');

            $this->channelManager->save($channel);
        };

        $this->handleForm($request, UserPrivateChannelCreateType::class, $channel, $callback, [
            'validation_groups' => ['dm']
        ]);

        return $this->channelService->serializeChannel(new ArrayCollection([$channel]));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getPrivateMessage(Request $request): array
    {
        $user = $this->userService->getUserOrException();

        $qb = $this->channelManager->getRepository()->createQueryBuilder('channel')
            ->leftJoin('channel.recipients', 'recipients')
            ->where('channel.type = :channel_id')
            ->andWhere('recipients.id = :recipients_id')
            ->setParameters([
                'channel_id' => Channel::DM,
                'recipients_id' => $user->getId(),
            ])
            ->orderBy('channel.updatedAt', 'ASC')
            ->groupBy('channel.id')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        UtilsNormalizer::normalizeArray($qb);

        return $qb;
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