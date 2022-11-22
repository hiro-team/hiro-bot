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
use hiro\interfaces\HiroInterface;
use hiro\interfaces\CommandInterface;

/**
 * Git command class
 */
class Git implements CommandInterface
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
        $client->registerCommand('git', function($msg, $args)
        {
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setTitle("Git (Github)");
            $embed->setURL("https://github.com/hiro-team/hiro-bot.git");
            $embed->setDescription("I'm open source!\nClick to go to the link.");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "source",
                "opensource",
                "open-source",
                "github",
                "code",
            ],
            "description" => "URL of bot's source code"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
