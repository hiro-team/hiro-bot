<?php

/**
 * Copyright 2021 bariscodefx
 * 
 * This file part of project Hiro 016 Discord Bot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
                $embed->setTitle("You Gained $" . number_format($daily, 2,',', '.') );
                $embed->setTimestamp();
                $embed->setColor('#7CFC00');
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
