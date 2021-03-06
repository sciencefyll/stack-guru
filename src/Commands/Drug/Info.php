<?php

namespace StackGuru\Commands\Drug;

use StackGuru\Core\Command\AbstractCommand;
use StackGuru\Core\Command\CommandContext;
use StackGuru\Core\Utils\Response as Response;
use React\Promise\Promise as Promise;
use React\Promise\Deferred as Deferred;


class Info extends AbstractCommand
{
    protected static $name = "info";
    protected static $description = "something about the info command";

    const QUERY_URLS = ["https://www.ncbi.nlm.nih.gov/pubmed/?term="];

    // Temporary data while i have no database
    // Schema
    //  id: string or int
    //  trivialName: string
    //  dose: string (ALWAYS milligram (mg))
    //  duration: string
    //  effects: string[]
    //  bioavailability: string
    //  alias: string[]
    //  resources: string[]
    private $datas = [
        [
            "id" => 14389,
            "name" => "modafinil",
            "dose" => "100", //mg
            "duration" => "9t - 15t", // t m s must be specified
            "effects" => [],
            "bioavailability" => "75% - 80%",
            "alias" => ["provigil", "modafinil", "nzt-48 precursor"],
            "resources" => ["https://examine.com/supplements/modafinil/", "https://psychonautwiki.org/wiki/Modafinil"]
        ]
    ];


    /**
     * Retrieves information about a known drug.
     *
     * @param  string $query Message input from the user after the commands have been stripped off.
     * @param  ?CommandContext $ctx   Context class to access parent object, among others.
     *
     * @return string Response to be sent.
     */
    public function process(string $query, CommandContext $ctx): Promise
    {
        $args = explode(' ', $query);

        var_dump($args);

        $result = '';

        if (2 <= sizeof($args)) {
            $result .= $this->getProperty($args[0], $args[1]);

            if ('' === $result) {
                $args = [$args[0]]; // so that the next if picks it up and returns all the data.
            }
        }


        if (1 === sizeof($args)) {
            $results = $this->getDatasheet($args[0]);

            foreach ($results as $key => $value) {
                $result .= $key . ": " . $this->parseValue($key, $value) . PHP_EOL;
            }
        }


        // in cae result is empty
        if (empty($result) || 1 >= strlen($result)) {
            $result = "Not found!";
        }
        else {
            $result = PHP_EOL . strtoupper($args[0]) . PHP_EOL . $result;
        }

        return Response::sendMessage($result, $ctx->message);
    }


    private function getProperty(string $drugname, string $category, array $drugData = null): mixed // :int, :string, :array.....
    {
        // set drug record
        $drug = [];
        if (null === $drugData) {
            $drug = $this->getDatasheet($drugname);
        }
        else {
            $drug = $drugData;
            $drugData = null; //clear memory
        }

        // validate it....
        if (empty($drug)) {
            return ''; // nothing
        }


        // check if category exists
        $category = strtolower(trim($category));
        if (!isset($drug[$category])) {
            return ''; // nothing
        }


        // Drug and category exists
        return $category .": " . $this->parseValue($category, $drug[$category]);
    }

    private function getDatasheet(string $drugname): array
    {
        $drug = [];

        if (null !== $drugname) {
            // check if drug exists in record
            foreach ($this->datas as $data) {
                if (in_array($drugname, $data["alias"])) {
                    $drug = $data;
                    break;
                }
            }
        }

        return $drug;
    }

    private function parseValue(string $key, $value): string
    {
        if (is_array($value)) {
            $value = implode(", ", $value);
        }

        if ("dose" === $key) {
            $value .= "mg";
        }

        return $value;
    }
}
