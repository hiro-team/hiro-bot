<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Howgay command class
 */
class Howgay
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
