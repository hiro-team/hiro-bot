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

/**
 * Nick
 */
class Nick extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "nick";
        $this->description = "Change users nick.";
        $this->aliases = ["nickname"];
        $this->category = "mod";
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
        if ($msg->member->getPermissions()['manage_nicknames']) {
            $user = $msg->mentions->first();
            if ($user) {
                $newname = explode("$user ", implode(' ', $args))[1] ?? ""; // else set to default
                $msg->channel->guild->members[$user->id]->setNickname($newname)->then(function() use ($msg) {
                    $msg->reply("Nickname changed.");
                }, function( \Throwable $reason ) use ($msg) {
                    $msg->reply($reason->getCode() === 50013 ? "I don't have permission to manage nicknames." : "Unknown error excepted.");
                });
            } else {
                $msg->reply("If you wanna change name for a user, you must mention a user.");
            }
        } else {
            $msg->reply("If you wanna change name for a user, you must have `manage_nicknames` permission.");
        }
    }
}
