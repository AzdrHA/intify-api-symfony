<?php

namespace App\Command;

use App\Entity\Guild\Guild;
use App\Manager\Guild\GuildManager;
use App\Manager\Message\MessageManager;
use App\Manager\User\UserManager;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppInitDataCommand extends Command
{
    protected static $defaultName = "app:init:data";
    private UserManager $userManager;
    private MessageManager $messageManager;
    private GuildManager $guildManager;

    public function __construct(UserManager $userManager, MessageManager $messageManager, GuildManager $guildManager)
    {
        $this->userManager = $userManager;
        $this->messageManager = $messageManager;
        $this->guildManager = $guildManager;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('fr_FR');

        $user = $this->userManager->getRepository()->findOneBy(['email' => 'azdracito@gmail.com']);

        if (!$user)
            return Command::FAILURE;

        for ($i = 0; $i < rand(10, 50); $i++) {
            /** @var Guild $guild */
            $guild = $this->guildManager->create();
            $guild->setName($faker->company);
            $guild->setOwner($user);

            $this->guildManager->save($guild);
        }

        $output->writeln('');
        return Command::SUCCESS;
    }
}