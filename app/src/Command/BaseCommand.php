<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class BaseCommand extends Command
{
    protected static array $commands = [];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ( static::$commands as $command )
        {
            $output->writeln("<info>$command</info>");
            $process = new Process(explode(" ", $command));
            $process->setTimeout(3600);
            $process->run();
            echo $process->getOutput();
        }

        $output->writeln('');
        return Command::SUCCESS;
    }
}