<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Kick command class
 */
class Kick
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
        $client->registerCommand('kick', function($msg, $args)
        {
            $msg->channel->sendMessage("This feature is not available for now!");
        }, [
            "aliases" => [],
            "description" => "Kicks user"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
