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

namespace hiro\database;

use PDO;
use PDOException;

/**
 * Class Database
 * @package hiro\database
 */
class Database extends PDO
{

    /**
     * Database constructor.
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pass
     * @param string|string $charset
     */
    public function __construct(string $host,
                                string $dbname,
                                string $user,
                                string $pass,
                                string $charset = "utf8"){
        try {
            parent::__construct("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass);
        }catch(PDOException $e)
        {
            echo $e . PHP_EOL;
        }
    }

    /**
     * @param int $user_id
     * @return false|mixed
     */
    public function getUser(int $user_id)
    {
        $query = $this->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$user_id]);
        if($query->rowCount())
            return $query->fetch(PDO::FETCH_ASSOC);
        else
            return false;
    }

    /**
     * @param int $user_id
     * @return false|mixed
     */
    public function getUserMoney(int $user_id)
    {
        if($this->getUser($user_id))
        {
            $user = $this->getUser($user_id);
            return $user['money'];
        }else {
            return false;
        }
    }

    /**
     * @param int $discord_id
     * @return false|mixed
     */
    public function getUserIdByDiscordId(int $discord_id)
    {
        $query = $this->prepare("SELECT * FROM users WHERE discord_id = ?");
        $query->execute([$discord_id]);
        if($query->rowCount())
        {
            return $query->fetch(PDO::FETCH_ASSOC)['id'];
        }else
        {
            return false;
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addUser(array $data)
    {
        if(!$data['discord_id'])
        {
            return false;
        }else {
            $query = $this->prepare("INSERT INTO users SET discord_id = :discord_id, money = :money, register_time = :register_time");
            if($query->execute([
                "discord_id" => $data['discord_id'],
                "money" => 0,
                "register_time" => time()
            ]))
            {
                return true;
            }else {
                print_r($query->errorInfo());
                return false;
            }
        }
    }

    public function getLastDailyForUser(int $userid)
   {
       $query = $this->prepare('SELECT last_daily FROM users WHERE id = :id');
       $query->execute([
           "id" => $userid
       ]);
       return $query->fetch()[0];
   }

   public function daily(int $userid)
   {
       $query = $this->prepare('SELECT money FROM users WHERE id = :id');
       $query->execute([
           "id" => $userid
       ]);
       $usermoney = $query->fetch()[0];
       $daily = 500;
       $query = $this->prepare('UPDATE users SET money = :money, last_daily = :last_daily WHERE id = :id');
       $exec = $query->execute(["money" => $usermoney + $daily, "last_daily" => time(), "id" => $userid]);
       if($exec)
       {
           return $daily;
       }else {
           return false;
       }
   }
   
   public function setUserMoney(int $user_id, int $money)
   {
	   $query = $this->prepare('UPDATE users SET money = :money WHERE id = :id');
	   return $query->execute([
			"money" => $money,
			"id" => $user_id
	   ]);
   }

}
