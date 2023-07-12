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
use hiro\Version;

/**
 * Botinfo
 */
class Botinfo extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "botinfo";
        $this->description = "Bans mentioned user.";
        $this->aliases = [];
        $this->category = "bot";
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
        $guilds             = $this->discord->formatNumber(sizeof($this->discord->guilds));
        $members            = $this->discord->formatNumber(sizeof($this->discord->users));
        $embed = new Embed($this->discord);
        $embed->setTitle("Bot Info");
        $embed->addField($this->discord->makeField("Shard ID", $this->discord->options['shardId']));
        $embed->addField($this->discord->makeField("Shard Count", $this->discord->options['shardCount']));
        $embed->addField($this->discord->makeField("Guilds", $guilds));
        $embed->addField($this->discord->makeField("Members", $members));
        $embed->addField($this->discord->makeField("Commands", $this->loader->getCommandsCount()));
        $embed->addField($this->discord->makeField("Version", sprintf("%s %s", Version::VERSION, Version::TYPE)));
        $embed->addField($this->discord->makeField("Latency", intval($msg->timestamp->floatDiffInRealSeconds() * 1000) . "ms"));
        $embed->setThumbnail($this->discord->avatar);
        $embed->setAuthor($msg->member->username, $msg->author->avatar);
        $embed->setTimestamp();
        $msg->channel->sendEmbed($embed);
    }
}
