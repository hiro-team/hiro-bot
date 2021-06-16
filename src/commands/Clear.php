<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Clear command class
 */
class Clear
{
    
    /**
     * Command category
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
        $client->registerCommand('clear', function($msg, $args)
        {
            if(!$msg->author->getPermissions()["manage_messages"])
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("You must have manage messages permission for use this");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $limit = $args[0];
            if(!isset($limit))
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("You must give an amount");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if(!is_numeric($limit))
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("You must give an numeric parameter");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if($limit < 1 || $limit > 100)
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("Error!");
                $embed->setDescription("Amount must be around of 1-100");
                $embed->setColor("#ff000");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $msg->channel->limitDelete($limit);
            $embed = new Embed($this->discord);
            $embed->setTitle("Clear Command");
            $embed->setDescription($limit . " messages was deleted.");
            $embed->setColor("#5558E0");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "purge"
            ],
            "description" => "Clears messages"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
