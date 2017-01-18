<?php
declare(strict_types=1);

namespace StackGuru\Core\Command;

use StackGuru\Core\Utils;


abstract class AbstractCommand implements CommandInterface
{
    protected static $name = ""; // Name of the command.
    protected static $aliases = []; // List of top-level aliases for the command.
    protected static $description = ""; // Short summary of the commands purpose.

    protected static $default = null; // Default subcommand name, takes precedence over process().


    public function __construct()
    {
    }


    /**
     * Abstract functions
     */

    // TODO: Show Help for command by default.
    abstract public function process(string $query, ?CommandContext $ctx): string;


    /**
     * Getters for static command properties.
     */

    final public static function getName(): string
    {
        // Use class name by default as command name
        if (empty(static::$name))
            $name = Utils\Reflection::getShortClassName(static::class);
        else
            $name = static::$name;

        return strtolower($name);
    }

    final public static function getAliases(): array { return static::$aliases; }
    final public static function getDescription(): string { return static::$description; }
    final public static function getDefault(): ?string { return static::$default; }
}