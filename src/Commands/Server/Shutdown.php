<?php

namespace StackGuru\Commands\Server;

use StackGuru\Core\Command\AbstractCommand;
use StackGuru\Core\Command\CommandContext;


class Shutdown extends AbstractCommand
{
    protected static $name = "shutdown";
    protected static $description = "something about the shutdown command";


    public function process(string $query, ?CommandContext $ctx): string
    {
        $args = explode(' ', trim($query) . ' ');
        return "gkdfjhlg";
    }
}