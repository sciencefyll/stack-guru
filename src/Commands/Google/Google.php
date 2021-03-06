<?php

namespace StackGuru\Commands\Google;

use StackGuru\Core\Command\AbstractCommand;
use StackGuru\Core\Command\CommandContext;
use StackGuru\Core\Utils\Response as Response;
use React\Promise\Promise as Promise;
use React\Promise\Deferred as Deferred;


class Google extends AbstractCommand
{
    protected static $name = "google";
    protected static $description = "link to google";


    public function process(string $query, CommandContext $ctx): Promise
    {
    	$search = $ctx->cmdRegistry->loadCommand("\\StackGuru\\Commands\\Google", "Search");
    	$searchCommand = $search->createInstance();

        return $searchCommand->process($query, $ctx);
    }
}
