<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;

/**
 * Money command class
 */
class Money
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
    public function __construct(DiscordCommandClient $client)
    {
        $this->loader = $loader;
        $this->category = "economy";
        $this->discord = $client;
        $client->registerCommand('money', function($msg, $args)
        {
            $embed = new Embed($this->discord);
            $embed->setTitle('Your money: $0');
            $embed->setTimestamp();
            $embed->setColor('#ff0000');
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "cash"
            ],
            "description" => "Displays your money."
        ]);
    }

    public function __get(string $name)
    {
        return $this->{$name};
    }

}
