<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Ban command class
 */
class Ban
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
        $client->registerCommand('ban', function($msg, $args)
        {
            if($msg->author->getPermissions()['ban_members'])
            {
                $user = $msg->mentions->first();
                if($user)
                {
                    $banner = $msg->author->user;
                    $rp_men = $msg->channel->guild->members[$user->id]->roles->first()->position;
                    $rp_self = $msg->author->roles->first()->position;
                    if($banner->id == $user->id)
                    {
                        $embed = new Embed($this->discord);
                        $embed->setColor('#ff0000');
                        $embed->setDescription("You cant ban yourself");
                        $embed->setTimestamp();
                        $msg->channel->sendEmbed($embed);
                        return;
                    }else {
                        if( $rp_self < $rp_men )
                        {
                            $embed = new Embed($this->discord);
                            $embed->setColor('#ff0000');
                            $embed->setDescription("Your role position too low!");
                            $embed->setTimestamp();
                            $msg->channel->sendEmbed($embed);
                            return;
                         }else {
                             $msg->channel->guild->members[$user->id]->ban(null, null);
                             $embed = new Embed($this->discord);
                             $embed->setColor('#ff0000');
                             $embed->setDescription("$user banned by $banner.");
                             $embed->setTimestamp();
                             $msg->channel->sendEmbed($embed);
                         }
                     }
                }else {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("If you want ban a user you must mention a user.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                }
            }else {
                $embed = new Embed($this->discord);
                $embed->setColor('#ff0000');
                $embed->setDescription("If you want ban a user u must have `ban_members` permission.");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
            }
        }, [
            "aliases" => [
                "yasakla"
            ],
            "description" => "Bans user"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}






        
       
            
            

            

            
                
            

        
    
    

    

    
    

