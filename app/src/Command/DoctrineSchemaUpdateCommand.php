<?php

namespace App\Command;

class DoctrineSchemaUpdateCommand extends BaseCommand
{
    protected static $defaultName = 'core:db';
    protected static array $commands = [
        "bin/console doctrine:cache:clear-result",
        "bin/console doctrine:cache:clear-query",
        "bin/console doctrine:cache:clear-metadata",
        "bin/console doctrine:migrations:diff",
        "bin/console doctrine:migrations:migrate --no-interaction"
    ];
}