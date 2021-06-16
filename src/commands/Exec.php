<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;

/**
 * Exec command class
 */
class Exec
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
        $this->category = "author";
        $client->registerCommand('exec', function($msg, $args)
        {
            if($msg->author->user->id != 793431383506681866)
            {
                $msg->channel->sendMessage("No");
                return;
            }
            $ex = implode(' ', $args);
            if(!$ex) $ex = " ";
            $output = shell_exec($ex);
            $msg->channel->sendMessage("```\n" . $output . "\n```");
        }, [
            "aliases" => [
                "execute", "shell-exec"
            ],
            "description" => "Executes an command **ONLY FOR AUTHOR**"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
