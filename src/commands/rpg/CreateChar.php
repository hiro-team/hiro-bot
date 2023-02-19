<?php

namespace hiro\commands;

use hiro\database\Database;
use hiro\consts\RPG;

class CreateChar extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "createchar";
        $this->description = "Creates your character.";
        $this->aliases = ["createcharacter"];
        $this->category = "rpg";
    }

    /**
     * handle
     *
     * @param [type] $msg
     * @param [type] $args
     * @return void
     */
    public function handle($msg, $args): void
    {
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage("Couldn't connect to database.");
            return;
        }

        $charType = $database->getRPGCharType($database->getUserIdByDiscordId($msg->author->id));
        $charNation = $database->getRPGCharRace($database->getUserIdByDiscordId($msg->author->id));
        $charGender = $database->getRPGCharGender($database->getUserIdByDiscordId($msg->author->id));

        if ($charType || $charNation || $charGender) {
            $msg->reply('You have already created your character!');
            return;
        }

        if (!isset($args[2])) {
            $prefix = $_ENV['PREFIX'];
            $races_string = "";
            foreach(RPG::getRacesAsArray(true) as $race)
            {
                if (end(RPG::getRacesAsArray(true)) === $race)
                {
                    $races_string .= $race;
                    break;
                }
                $races_string .= $race . ", ";
            }
            $msg->reply(<<<EOF
            Usage:
            {$prefix}createchar [type] [race] [gender]

            Available:
            Types: warrior, ranger, mage, healer
            
            Races: $races_string

            Genders: male or female

            Example:
            {$prefix}createchar healer elf female
            EOF);
            return;
        }

        $char = 0;
        if (strtolower($args[0]) == "warrior") {
            $char = RPG::WARRIOR_CHAR;
        } elseif (strtolower($args[0]) == "ranger") {
            $char = RPG::RANGER_CHAR;
        } elseif (strtolower($args[0]) == "mage") {
            $char = RPG::MAGE_CHAR;
        } elseif (strtolower($args[0]) == "healer") {
            $char = RPG::HEALER_CHAR;
        }

        if (!$char) {
            $msg->reply("Unknown type.");
            return;
        }

        $races = RPG::getRacesAsArray();
        $race = 0;
        if (isset($races[strtolower($args[1])])) {
            $race = $races[strtolower($args[1])];
        }

        if (!$char) {
            $msg->reply("Unknown race.");
            return;
        }

        $gender = 0;
        if (strtolower($args[2]) == "male") {
            $gender = RPG::MALE_GENDER;
        } elseif (strtolower($args[2]) == "female") {
            $gender = RPG::FEMALE_GENDER;
        }

        if (!$gender) {
            $msg->reply("Unknown gender.");
            return;
        }

        // database progress

        if ($database->setRPGCharType($database->getUserIdByDiscordId($msg->author->id), $char)) {
            $msg->reply("Your class has been changed to " . $args[0] . " successfully.");
        } else {
            $msg->reply("An error excepted.");
            return;
        }

        if ($database->setRPGCharRace($database->getUserIdByDiscordId($msg->author->id), $race)) {
            $msg->reply("Your race has been changed to " . $args[1] . " successfully.");
        } else {
            $msg->reply("An error excepted.");
            return;
        }

        if ($database->setRPGCharGender($database->getUserIdByDiscordId($msg->author->id), $gender)) {
            $msg->reply("Your gender has been changed to " . $args[2] . " successfully.");
        } else {
            $msg->reply("An error excepted.");
            return;
        }

        $msg->reply("Character created successfully.");
    }
}
