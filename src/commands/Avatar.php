<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Avatar command class
 */
class Avatar
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
        $client->registerCommand('avatar', function($msg, $args)
        {
            $user = $msg->mentions->first();
            if($user)
            {
                $avatar = $user->avatar;
            }else {
                $avatar = $msg->author->user->avatar;
            }
            if (strpos($avatar, 'a_') !== false){
                $avatar= str_replace('jpg', 'gif', $avatar);
            }
            $embed = new Embed($this->discord);
            $embed->setColor("#ff0000");
            $embed->setTitle("Avatar");
            $embed->setImage($avatar);
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "whoami"
            ],
            "description" => "Shows avatar"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
