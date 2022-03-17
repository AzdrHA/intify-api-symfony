<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrineCacheClearCommand extends BaseCommand
{
    protected static $defaultName = "core:cc";
    protected static array $commands = [
        "bin/console doctrine:cache:clear-result",
        "bin/console doctrine:cache:clear-query",
        "bin/console doctrine:cache:clear-metadata",
        "bin/console assets:install",
    ];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        apcu_clear_cache();
        opcache_reset();

        return parent::execute($input, $output);
    }
}