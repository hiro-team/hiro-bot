<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;
use hiro\CommandLoader;

/**
 * Help command class
 */
class Help
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
    public function __construct(DiscordCommandClient $client, CommandLoader $loader)
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
            $embed->setImage("https://i.pinimg.com/originals/50/34/93/503493fb0f30c1f84880bdf98b543e97.gif");
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
