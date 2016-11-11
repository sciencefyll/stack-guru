<?php
/**
 * interface for all commands to be written
 *
 * @author http://github.com/sciencefyll
 */

namespace StackGuru;

interface CommandInterface
{
    public function __construct();

    /*
     * Parses the give string array to do further actions.. improvements?
     */
    public function /*        */ process (/* string[] */ array $args, CommandContext $ctx) : string;
    //public function /*        */ linkDiscordObject  (/* function () : Object */ $callback);

}