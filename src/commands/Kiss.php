<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Kiss command class
 */
class Kiss
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
        $client->registerCommand('kiss', function($msg, $args)
        {
            $gifs = [
                "https://bariscodefxy.github.io/cdn/hiro/kiss.gif",
                "https://bariscodefxy.github.io/cdn/hiro/kiss_1.gif",
                "https://bariscodefxy.github.io/cdn/hiro/kiss_2.gif",
                "https://bariscodefxy.github.io/cdn/hiro/kiss_3.gif",
                "https://bariscodefxy.github.io/cdn/hiro/kiss_4.gif",
                "https://bariscodefxy.github.io/cdn/hiro/kiss_5.gif",
                "https://bariscodefxy.github.io/cdn/hiro/kiss_6.gif",
            ];
            $random = $gifs[rand(0, sizeof($gifs) - 1)];
            $self = $msg->author->user;
            $user = $msg->mentions->first();
            if(empty($user))
            {
                $embed = new Embed($this->discord);
                $embed->setColor("#ff0000");
                $embed->setDescription("You must mention a user for kiss");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }else if($user->id == $self->id)
            {
                $embed = new Embed($this->discord);
                $embed->setColor("#ff0000");
                if($msg->author->user->id == 837641679879274506) $embed->setDescription("Kendini öpemezsin Reis-i Führer.");
                else $embed->setDescription("You cant kiss yourself stupid!");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
                return;
            }
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setDescription("$self kissed you! $user");
            $embed->setImage($random);
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "öp"
            ],
            "description" => "You can kiss everybody"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
