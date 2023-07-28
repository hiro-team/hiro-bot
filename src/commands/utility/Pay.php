<?php

/**
 * Copyright 2023 bariscodefx
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

use Discord\Parts\Embed\Embed;
use hiro\database\Database;

class Pay extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "pay";
        $this->description = "Send your money to anybody.";
        $this->aliases = [];
        $this->category = "utility";
        $this->cooldown = 10 * 1000;
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
            $msg->reply("Couldn't connect to database.");
            return;
        }
        $embed = new Embed($this->discord);
        $user = $msg->mentions->first();
        if (!$user) {
            $msg->reply("You should select a user for send money.");
            return;
        }
        if ($user->id === $msg->author->id) {
            $msg->reply("You can't send your money to yourself.");
            return;
        }
        if (!isset($args[1]) && !is_numeric($args[1])) {
            $msg->reply("You should give a numeric money.");
            return;
        }
        if (!$database->getUser($database->getUserIdByDiscordId($msg->author->id))) {
            if (!$database->addUser(["discord_id" => $user->id])) {
                $msg->reply("An error excepted while registering you to database :(");
                return;
            }
        }
        if (!$database->getUser($database->getUserIdByDiscordId($user->id))) {
            if (!$database->addUser(["discord_id" => $user->id])) {
                $msg->reply("An error excepted while registering you to database :(");
                return;
            }
        }
        if (!$database->pay($database->getUserIdByDiscordId($msg->author->id), $database->getUserIdByDiscordId($user->id), $args[1])) {
            $msg->reply("An error excepted when sending money :(");
            return;
        }
        setlocale(LC_MONETARY, 'en_US');
        $msg->reply("Money Sent!\n\n" . $msg->user->username . " - <:hirocoin:1130392530677157898> " . number_format($args[1], 2, ',', '.') . "  -->  " . $user->username . " + <:hirocoin:1130392530677157898> " . number_format($args[1], 2, ',', '.'));
        return;
    }
}
