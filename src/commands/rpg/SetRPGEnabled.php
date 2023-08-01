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

use hiro\database\Database;

class SetRPGEnabled extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "setrpgenabled";
        $this->description = "Sets RPG enabled for the server.";
        $this->aliases = [];
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

        if (!$msg->member->getPermissions()['manage_channels']) {
            $msg->reply($language->getTranslator()->trans('commands.setrpgenabled.no_perm'));
            return;
        }

        $enabled = $args[0] ?? null;

        if ($enabled === null || ($enabled != 0 && $enabled != 1)) {
            $msg->reply($language->getTranslator()->trans('commands.setrpgenabled.invalid_parameter'));
            return;
        }

        if (!$database->setServerRPGEnabled($database->getServerIdByDiscordId($msg->guild->id), $enabled)) {
            $msg->reply($language->getTranslator()->trans('global.unknown_error'));
            return;
        }

        $msg_str = $enabled ? $language->getTranslator()->trans('commands.setrpgenabled.on_enable') : $language->getTranslator()->trans('commands.setrpgenabled.on_disable');
        $msg->reply($msg_str);
    }
}
