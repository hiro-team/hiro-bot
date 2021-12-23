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

/**
 * Nick command class
 */
class Nick
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
    public function __construct(DiscordCommandClient $client)
    {
        $this->discord = $client;
        $this->category = "mod";
        $client->registerCommand('nick', function($msg, $args)
        {
            if($msg->author->getPermissions()['manage_nicknames'])
            {
                $user = $msg->mentions->first();
                if($user)
                {
                    $newname = explode("$user ", implode(' ', $args))[1];
                    $msg->channel->guild->members[$user->id]->setNickname($newname);
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("Nickname was changed.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                    /*}else {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("I cant changed the nickname, give me permissions or put my role to top and try again.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                    }
                    }*/
                }else {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("If you want change name a user you must mention a user.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                }
            }else {
                $embed = new Embed($this->discord);
                $embed->setColor('#ff0000');
                $embed->setDescription("If you want change name a user u must have `manage_nicknames` permission.");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
            }
        }, [
            "aliases" => [
                "nickname"
            ],
            "description" => "Change users nick"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
