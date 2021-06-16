<?php

namespace hiro\commands;

use Discord\DiscordCommandClient;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Embed\Field;
use Dotenv\Dotenv;
use hiro\CommandLoader;
use hiro\database\Database;

/**
 * Class Money
 * @package hiro\commands
 */
class Money
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
     * @var Database
     */
    private $database;

    /**
     * Money constructor.
     * @param DiscordCommandClient $client
     */
    public function __construct(DiscordCommandClient $client)
    {
        $this->category = "economy";
        $this->discord = $client;
        include_once('../../db-settings.inc');
        $this->database = new Database($db_host, $db_dbname, $db_user, $db_pass);
        $client->registerCommand('money', function($msg, $args)
        {
            $user_money = $this->database->getUserMoney($this->database->getUserIdByDiscordId($msg->author->id));
            if(!$user_money)
            {
                if(!$this->database->addUser([
                    "discord_id" => $msg->author->id
                ]))
                {
                    $embed = new Embed($this->discord);
                    $embed->setTitle('You are couldnt added to database.');
                    $msg->channel->sendEmbed($embed);
                    return;
                }else
                    $user_money = 0;
            }
            $embed = new Embed($this->discord);
            $embed->setTitle("Your money: $".$user_money);
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

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->{$name};
    }

}
