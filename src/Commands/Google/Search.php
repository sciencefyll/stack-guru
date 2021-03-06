<?php

namespace StackGuru\Commands\Google;

use StackGuru\Core\Command\AbstractCommand;
use StackGuru\Core\Command\CommandContext;
use StackGuru\Core\Utils\Response as Response;
use React\Promise\Promise as Promise;
use React\Promise\Deferred as Deferred;


class Search extends AbstractCommand
{
    use UrlHelper;
    
    protected static $name = "search";
    protected static $description = "search";


    public function process(string $query, CommandContext $ctx): Promise
    {
        $args = explode(' ', $query);

        if (sizeof($args) >= 1 && 0 !== strlen($args[0])) {
            $query = implode(" ", $args);
        } else {
            $query = "Why am I such an asshole?";
        }

        $link = self::buildSearchUrl(['q' => $query]);

        return Response::sendMessage($link, $ctx->message);
    }
}
