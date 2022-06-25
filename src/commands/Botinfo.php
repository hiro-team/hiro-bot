<?php

/**
 * Copyright 2022 bariscodefx
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

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;
use hiro\interfaces\HiroInterface;
use hiro\interfaces\CommandInterface;

/**
 * Botinfo command class
 */
class Botinfo implements CommandInterface
{
    
    /**
     * command category
     */
    private $category;
    
    /**
     * $client
     */
    private $discord;
    
    /**
     * __construct
     */
    public function __construct(HiroInterface $client)
    {
        $this->discord = $client;
        $this->category = "bot";
        $client->registerCommand('botinfo', function($msg, $args)
        {
            $guilds             = $this->discord->formatNumber(sizeof($this->discord->guilds));
            $members            = $this->discord->formatNumber(sizeof($this->discord->users));
            $embed = new Embed($this->discord);
            $embed->setTitle("Bot Info");
            $embed->addField($this->makeField("Shard ID", $this->discord->options['shardId']));
            $embed->addField($this->makeField("Shard Count", $this->discord->options['shardCount']));
            $embed->addField($this->makeField("Guilds", $guilds));
            $embed->addField($this->makeField("Members", $members));
            $embed->addField($this->makeField("Latency", intval($msg->timestamp->floatDiffInRealSeconds() * 1000) . "ms"));
            $embed->setThumbnail($this->discord->avatar);
            $embed->setAuthor($msg->author->username);
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        },
        [
            "description" => "Shows bot's info"
        ]);
    }

    protected function makeField(string $name, string $value)
    {
        $field = new Field($this->discord);
        $field->name = $name;
        $field->value = $value;
        return $field;
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
