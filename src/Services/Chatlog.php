<?php
declare(strict_types=1);

namespace StackGuru\Services;
use \StackGuru\Core\Service\AbstractService;
use StackGuru\Core\Command\CommandContext as CommandContext;
use StackGuru\Core\Utils;
use \Discord\WebSockets\Event as DiscordEvent;
use \Discord\Parts\Channel\Message as Message;

class Chatlog extends AbstractService
{
    protected static $name = "chatlog"; // Name of the service.
    protected static $description = "stores messages to database"; // Short summary of the service purpose.
    protected static $event = \StackGuru\Core\BotEvent::MESSAGE_ALL_I_SELF;


	final public function process(string $query, ?CommandContext $ctx): string
	{
		return "";
	}

	final public function response(string $event, string $msgId, CommandContext $serviceCtx, $message = null)
	{

        	// ignore empty messages
        	if (null === $message) {
        	    return;
        	}

		$channel_id = "private messaging";
        	$private = $message->channel->is_private;

		// ignore private messaging
		if (!$private) {
			$channel_id = $message->channel_id;
		}

		// If this channel can't be logged, ignore it.
		if (!$serviceCtx->database->chatlog_loggableChannel($channel_id)) {
			return;
		}

        	$messageContent = null === $message->content ? "" : $message->content;
	
		// new message
		if (DiscordEvent::MESSAGE_CREATE == $event) {
			$author_id = "?";
			if (!isset($message->author)) {
				// make sure author is a valid object!
				//return;
			}
        		else {
				$author_id = isset($message->author->user) ? $message->author->user->id : $message->author->id; // on bot message from github: Trying to get property of non-object
			}
				
        		// built in discord bots causes an error..
        		if (null === $author_id) {
                		return; // never fires now..
			}

			if ($serviceCtx->database->chatlog_saveMessage($msgId, $channel_id, $author_id)) {
				$serviceCtx->database->chatlog_saveMessageContent($messageContent, $msgId);
			}
		}

		// updated message
		else if (DiscordEvent::MESSAGE_UPDATE == $event) {
			$serviceCtx->database->chatlog_saveMessageContent($messageContent, $$msgId);
		}

		// deleted message
		else if (DiscordEvent::MESSAGE_DELETE == $event) {
			$deleted = true;
			$serviceCtx->database->chatlog_updateMessage($msgId, $deleted);
		}
	}
}
