<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Waifu command class
 */
class Waifu
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
        $client->registerCommand('Waifu', function($msg, $args)
        {
            $user = $msg->mentions->first();
            if(!$user) $user = $msg->author->user;
            $random = rand(0, 100);
            $embed = new Embed($this->discord);
            $embed->setColor("#EB00EA");
            $embed->setDescription("$user you are $random% waifu");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "waifu"
            ],
            "description" => "How much u are waifu"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
