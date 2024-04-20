<?php

/**
 * Copyright 2021-2024 bariscodefx
 *
 * This file part of project Hiro 016 Discord Bot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
        global $language;
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage($language->getTranslator()->trans('database.notconnect'));
            return;
        }

        $charType = $database->getRPGCharType($database->getUserIdByDiscordId($msg->author->id));
        $charNation = $database->getRPGCharRace($database->getUserIdByDiscordId($msg->author->id));
        $charGender = $database->getRPGCharGender($database->getUserIdByDiscordId($msg->author->id));

        if ($charType || $charNation || $charGender) {
            $msg->reply($language->getTranslator()->trans('commands.createchar.already_created'));
            return;
        }

        if (!isset($args[2])) {
            $prefix = @$_ENV['PREFIX'];
            $races_string = "";
            foreach($races = RPG::getRacesAsArray(true) as $race)
            {
                if (end($races) === $race)
                {
                    $races_string .= $race;
                    break;
                }
                $races_string .= $race . ", ";
            }
            $msg->reply(sprintf($language->getTranslator()->trans('commands.createchar.description'), $prefix, $races_string, $prefix));
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
            $msg->reply($language->getTranslator()->trans('commands.createchar.unknown_type'));
            return;
        }

        $races = RPG::getRacesAsArray();
        $race = 0;
        if (isset($races[strtolower($args[1])])) {
            $race = $races[strtolower($args[1])];
        }

        if (!$char) {
            $msg->reply($language->getTranslator()->trans('commands.createchar.unknown_race'));
            return;
        }

        $gender = 0;
        if (strtolower($args[2]) == "male") {
            $gender = RPG::MALE_GENDER;
        } elseif (strtolower($args[2]) == "female") {
            $gender = RPG::FEMALE_GENDER;
        }

        if (!$gender) {
            $msg->reply($language->getTranslator()->trans('commands.createchar.unknown_gender'));
            return;
        }

        // database progress

        $msg_str = "";
        if ($t_state = $database->setRPGCharType($database->getUserIdByDiscordId($msg->author->id), $char)) {
            $msg_str .= sprintf($language->getTranslator()->trans('commands.createchar.set_type'), $args[0]) . "\n";
        } else {
            $msg_str .= $language->getTranslator()->trans('global.unknown_error');
            goto send_reply;
        }

        if ($r_state = $database->setRPGCharRace($database->getUserIdByDiscordId($msg->author->id), $race)) {
            $msg_str .= sprintf($language->getTranslator()->trans('commands.createchar.set_race'), $args[1]) . "\n";
        } else {
            $msg_str .= $language->getTranslator()->trans('global.unknown_error');
            goto send_reply;
        }

        if ($g_state = $database->setRPGCharGender($database->getUserIdByDiscordId($msg->author->id), $gender)) {
            $msg_str .= sprintf($language->getTranslator()->trans('commands.createchar.set_gender'), $args[2]) . "\n";
        } else {
            $msg_str .= $language->getTranslator()->trans('global.unknown_error');
            goto send_reply;
        }

        send_reply:
        $msg->reply($msg_str .
            ($t_state && $r_state && $g_state) ? $language->getTranslator()->trans('commands.createchar.on_success') : $language->getTranslator()->trans('commands.createchar.on_failure')
        );
    }
}
