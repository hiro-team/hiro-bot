<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Ping command class
 */
class Ping
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
     * __construct
     */
    public function __construct(DiscordCommandClient $client)
    {
        $this->discord = $client;
        $this->category = "bot";
        $client->registerCommand('ping', function($msg, $args)
        {
            $embed = new Embed($this->discord);
            $embed->setTitle("Pong");
            $diff = $msg->timestamp->floatDiffInRealSeconds();
            $embed->setDescription("Your ping took ".$diff."s to arrive.");
            $embed->setColor("#ffffff");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => ["latency", "ms"],
            "description" => "Displays bot's latency"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
