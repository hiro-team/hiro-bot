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
        global $language;
        $database = new Database();
        if (!$database->isConnected) {
            $msg->reply($language->getTranslator()->trans('database.notconnect'));
            return;
        }
        $embed = new Embed($this->discord);
        $user = $msg->mentions->first();
        if (!$user) {
            $msg->reply($language->getTranslator()->trans('commands.pay.no_user'));
            return;
        }
        if ($user->id === $msg->author->id) {
            $msg->reply($language->getTranslator()->trans('commands.pay.selfsend'));
            return;
        }
        if (!isset($args[1]) && !is_numeric($args[1])) {
            $msg->reply($language->getTranslator()->trans('commands.pay.no_numeric_arg'));
            return;
        }
        if (!$database->pay($database->getUserIdByDiscordId($msg->author->id), $database->getUserIdByDiscordId($user->id), $args[1])) {
            $msg->reply($language->getTranslator()->trans('commands.pay.fail_msg'));
            return;
        }
        setlocale(LC_MONETARY, 'en_US');
        $msg->reply(
            sprintf(
                $language->getTranslator()->trans('commands.pay.pay_msg'),
                $msg->user->username, number_format($args[1], 2, ',', '.'), "<:hirocoin:1130392530677157898>",
                $user->username, number_format($args[1], 2, ',', '.'), "<:hirocoin:1130392530677157898>" 
            )
        );
        return;
    }
}
