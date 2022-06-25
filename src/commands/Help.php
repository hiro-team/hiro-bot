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
use Discord\Parts\Embed\Field;
use hiro\CommandLoader;
use hiro\interfaces\HiroInterface;
use hiro\interfaces\ExtendedCommandInterface;

/**
 * Help command class
 */
class Help implements ExtendedCommandInterface
{
    
    /**
     * command $category
     */
    private $category;
    
    /**
     * $client
     */
    private $discord;
    
    /**
     * $loader
     */
    private $loader;
    
    /**
     * __construct
     */
    public function __construct(HiroInterface $client, CommandLoader $loader)
    {
        $this->loader = $loader;
        $this->category = "bot";
        $this->discord = $client;
        $client->unregisterCommand('help');
        $client->registerCommand('help', function($msg, $args)
        {
            $prefix = "hiro!";
            //$msg->channel->sendMessage("ok");
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setTitle("Help");
            $embed->setDescription("Displaying all commands of the bot");
            $embed->setImage("https://raw.githubusercontent.com/hiro-team/hiro-bot/master/resources/zero-two-hiro.gif");
            foreach($this->loader->categories as $category)
            {
                if(array_search($category, $this->loader->categories) == "author") continue;
                $field = new Field($this->discord);
                $value = "`";
                $b = 0;
                $field->name = "category";
                foreach($category as $cmd)
                {
                    if($b == sizeof($category) - 1)
                    {
                        $field->name = "Category: " . array_search($category, $this->loader->categories) . " (" . sizeof($category) . ")";
                        $value .= "$prefix$cmd`";
                    }else {
                        $value .= "$prefix$cmd, ";
                    }
                    $b++;
                }
                $field->value = $value;
                $embed->addField($field);
            }
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "?"
            ],
            "description" => "Displays commands"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
