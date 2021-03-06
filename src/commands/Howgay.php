<?php

/**
 * Copyright 2021 bariscodefx
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
 * Howgay command class
 */
class Howgay implements CommandInterface
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
        $this->category = "fun";
        $client->registerCommand('howgay', function($msg, $args)
        {
            $user = $msg->mentions->first();
            if(!$user) $user = $msg->author->user;
            $random = rand(0, 100);
            $embed = new Embed($this->discord);
            $embed->setColor("#EB00EA");
            $embed->setDescription("$user you are $random% gay. :gay_pride_flag:");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "gay"
            ],
            "description" => "How much u are gay"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
