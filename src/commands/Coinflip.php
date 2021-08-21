<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;
use hiro\CommandLoader;
use hiro\database\Database;

/**
 * Coinflip command class
 */
class Coinflip
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
    public function __construct(DiscordCommandClient $client, CommandLoader $loader)
    {
        $this->category = "economy";
        $this->discord = $client;
        $client->registerCommand('coinflip', function($msg, $args)
        {
			include __DIR__ . '/../../db-settings.inc';
			$database = new Database($db_host, $db_dbname, $db_user, $db_pass);
            $embed = new Embed($this->discord);
            if(!$args[0] || !is_numeric($args[0]))
            {
                $embed->setColor('#ff0000');
                $embed->setDescription('You should type payment amount.');
            }else {
				if($args[0] <= 0 || $args[0] > )
                $payamount = $args[0];
                $rand = rand(0, 1);
				$usermoney = $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id));
                $embed->setColor('#ffffff');
                if($rand)
                {
                    $database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $usermoney + $payamount * 2);
                    $embed->setDescription("You win " . $payamount * 2);
                }else {
					$database->setUserMoney($database->getUserIdByDiscordId($msg->author->id), $usermoney - $payamount);
                    $embed->setDescription("You lose " . $payamount);
                }
            }
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }, [
            "aliases" => [
                "cf"
            ],
            "description" => "An economy game"
        ]);
    }
    
    public function __get(string $name)
    {
        return $this->{$name};
    }
    
}
