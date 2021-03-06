<?php

namespace StackGuru\Commands\Scanner;

use StackGuru\Core\Command\AbstractCommand;
use StackGuru\Core\Command\CommandContext;
use React\Promise\Promise as Promise;
use React\Promise\Deferred as Deferred;
use StackGuru\Core\Utils\Response as Response;


class UserCount extends AbstractCommand
{
    protected static $name = "UserCount";
    protected static $description = "Display number of members";
    private static $printf1 = "%-26s";


    public function process(string $query, CommandContext $ctx): Promise
    {
        $users = $ctx->parentCommand->getUsers($ctx);
        $usercount = sizeof($users);

        $enabled = false;
        $showChannels = "--channels" == trim($query) || "-c" == trim($query);



        $res = "";
        $res .= "```Markdown" . PHP_EOL;

        // title
        $res .= $showChannels ? sprintf(self::$printf1, "# Members") : "# Members ";
        $res .= "[{$usercount}]" . PHP_EOL;

        // channels
        if ($enabled && $showChannels) {
            foreach ($ctx->guild->channels as $channel) {
                $memberCount = sizeof($channel->recipients);

                $res .= sprintf(self::$printf1, "* {$channel->name}");
                $res .= "{$memberCount} " . PHP_EOL;
            }
        }

        // ending
        $res .= "```";


        return Response::sendMessage($res, $ctx->message);
    }
}
