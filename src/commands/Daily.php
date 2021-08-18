<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;
use Dotenv\Dotenv;
use hiro\CommandLoader;
use hiro\database\Database;

/**
 * Class Daily
 * @package hiro\commands
 */
class Daily
{

    /**
     * @var string Command Category
     */
    private $category;

    /**
     * @var DiscordCommandClient
     */
    private $discord;

    /**
     * Money constructor.
     * @param DiscordCommandClient $client
     */
    public function __construct(DiscordCommandClient $client)
    {
        $this->category = "economy";
        $this->discord = $client;
        $client->registerCommand('daily', function($msg, $args)
        {
            include __DIR__ . '/../../db-settings.inc';
            $database = new Database($db_host, $db_dbname, $db_user, $db_pass);
            $user_money = $database->getUserMoney($database->getUserIdByDiscordId($msg->author->id));
	    $last_daily = $database->getLastDailyForUser($database->getUserIdByDiscordId($msg->author->id));
	    if(time() - $last_daily < 86400)
	   {
		$msg->channel->sendMessage('You must wait 24 hours.');
		return;
	    }
            if(!is_numeric($user_money))
            {
                echo "money is empty" . PHP_EOL;
                if(!$database->addUser([
                    "discord_id" => $msg->author->id
                ]))
                {
                    $embed = new Embed($this->discord);
                    $embed->setTitle('You are couldnt added to database.');
                    $msg->channel->sendEmbed($embed);
                    echo "cant added" . PHP_EOL;
                    return;
                }else
                {
                    echo "User added" . PHP_EOL;
                    $user_money = 0;
                }
            }
            setlocale(LC_MONETARY, 'en_US');
            $daily = $database->daily($database->getUserIdByDiscordId($msg->author->id));
            if($daily)
            {
                $embed = new Embed($this->discord);
                $embed->setTitle("You Gained $" . $daily);
                $embed->setTimestamp();
                $embed->setColor('#ff0000');
                $msg->channel->sendEmbed($embed);
            }else {
                $msg->channel->sendMessage('Cant give daily');
            }
            $database = NULL;
        }, [
            "aliases" => [
                
            ],
            "description" => "Daily moneys."
        ]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->{$name};
    }

}
