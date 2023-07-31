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
 * Ping
 */
class Ping extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "ping";
        $this->description = "Displays bot's latency.";
        $this->aliases = ["latency", "ms"];
        $this->category = "utility";
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
        $diff = intval($msg->timestamp->floatDiffInRealSeconds() * 1000);
        $msg->channel->sendMessage(sprintf($language->getTranslator()->trans('commands.ping.reply'), $diff));
    }
}
