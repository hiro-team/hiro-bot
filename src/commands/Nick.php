<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Nick command class
 */
class Nick
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
        $client->registerCommand('nick', function($msg, $args)
        {
            if($msg->author->getPermissions()['manage_nicknames'])
            {
                $user = $msg->mentions->first();
                if($user)
                {
                    $newname = explode("$user ", implode(' ', $args))[1];
                    $msg->channel->guild->members[$user->id]->setNickname($newname);
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("Nickname was changed.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                    /*}else {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("I cant changed the nickname, give me permissions or put my role to top and try again.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                    }
                    }*/
                }else {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("If you want change name a user you must mention a user.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                }
            }else {
                $embed = new Embed($this->discord);
                $embed->setColor('#ff0000');
                $embed->setDescription("If you want change name a user u must have `manage_nicknames` permission.");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
            }
        }, [
            "aliases" => [
                "nickname"
            ],
            "description" => "Change users nick"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
