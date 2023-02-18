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
 * Database
 */
class Database extends PDO
{
    /**
     * isConnected
     *
     * @var boolean
     */
    public $isConnected = false;

    /**
     * __construct
     */
    public function __construct()
    {
        if (!file_exists(dirname(__DIR__, 2) . "/db-settings.inc")) {
            return false;
        }
        include dirname(__DIR__, 2) . "/db-settings.inc";
        if (!isset($db_user) || !isset($db_pass) || !isset($db_host) || !isset($db_dbname)) {
            return false;
        }
        try {
            parent::__construct("mysql:host=$db_host;dbname=$db_dbname;charset=utf8", $db_user, $db_pass);
        } catch (PDOException $e) {
            echo $e . PHP_EOL;
            return false;
        }
        $this->isConnected = true;
        $this->createTables();
    }

    /**
     * getUser
     *
     * @param integer $user_id
     * @return boolean|array
     */
    public function getUser(int $user_id): bool|array
    {
        $query = $this->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$user_id]);
        if ($query->rowCount()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * getUserMoney
     *
     * @param integer $user_id
     * @return boolean|integer
     */
    public function getUserMoney(int $user_id): bool|int
    {
        if ($this->getUser($user_id)) {
            $user = $this->getUser($user_id);
            return $user['money'];
        } else {
            return false;
        }
    }

    /**
     * getUserIdByDiscordId
     *
     * @param integer $discord_id
     * @return boolean|integer
     */
    public function getUserIdByDiscordId(int $discord_id): bool|int
    {
        $query = $this->prepare("SELECT * FROM users WHERE discord_id = ?");
        $query->execute([$discord_id]);
        if ($query->rowCount()) {
            return $query->fetch(PDO::FETCH_ASSOC)['id'];
        } else {
            $this->addUser(['discord_id' => $discord_id]);
            return $this->getUserIdByDiscordId($discord_id);
        }
    }

    /**
     * addUser
     *
     * @param array $data
     * @return boolean
     */
    public function addUser(array $data): bool
    {
        if (!$data['discord_id']) {
            return false;
        } else {
            $query = $this->prepare("INSERT INTO users SET discord_id = :discord_id, money = :money, register_time = :register_time, last_daily = :last_daily");
            if ($query->execute([
                "discord_id" => $data['discord_id'],
                "money" => 0,
                "register_time" => time(),
                "last_daily" => 0,
            ])) {
                return true;
            } else {
                print_r($query->errorInfo());
                return false;
            }
        }
    }

    /**
     * getLastDailyForUser
     *
     * @param integer $userid
     * @return boolean|integer
     */
    public function getLastDailyForUser(int $userid): bool|int
    {
        $query = $this->prepare('SELECT last_daily FROM users WHERE id = :id');
        $query->execute([
            "id" => $userid
        ]);
        return $query->fetch()[0];
    }

    /**
     * daily
     *
     * @param integer $userid
     * @return boolean|integer
     */
    public function daily(int $userid): bool|int
    {
        $query = $this->prepare('SELECT money FROM users WHERE id = :id');
        $query->execute([
            "id" => $userid
        ]);
        $usermoney = $query->fetch()[0];
        $daily = 500;
        $query = $this->prepare('UPDATE users SET money = :money, last_daily = :last_daily WHERE id = :id');
        $exec = $query->execute(["money" => $usermoney + $daily, "last_daily" => time(), "id" => $userid]);
        if ($exec) {
            return $daily;
        } else {
            return false;
        }
    }

    /**
     * setUserMoney
     *
     * @param integer $user_id
     * @param integer $money
     * @return boolean
     */
    public function setUserMoney(int $user_id, int $money): bool
    {
        $query = $this->prepare('UPDATE users SET money = :money WHERE id = :id');
        return $query->execute([
            "money" => $money,
            "id" => $user_id
        ]);
    }

    /**
     * pay
     *
     * @param integer $user1
     * @param integer $user2
     * @param integer $payamount
     * @return boolean
     */
    public function pay(int $user1, int $user2, int $payamount): bool
    {
        $user1_money = $this->getUserMoney($user1);
        $user2_money = $this->getUserMoney($user2);
        if ($payamount > $user1_money) {
            return false;
        }
        if ($payamount < 1) {
            return false;
        }
        if (!$this->setUserMoney($user1, $user1_money - $payamount)) {
            return false;
        }
        if (!$this->setUserMoney($user2, $user2_money + $payamount)) {
            return false;
        }
        return true;
    }

    /**
     * getRPGCharType
     *
     * @param integer $user_id
     * @return integer|null
     */
    public function getRPGCharType(int $user_id): ?int
    {
        $user = $this->getUser($user_id);

        if ($user) {
            return $user['rpg_chartype'];
        }

        return null;
    }

    /**
     * setRPGCharType
     *
     * @param integer $user_id
     * @param integer $type
     * @return boolean
     */
    public function setRPGCharType(int $user_id, int $type): bool
    {
        $query = $this->prepare("UPDATE users SET rpg_chartype = ? WHERE id = ?");

        return $query->execute([$type, $user_id]);
    }

    /**
     * getRPGCharRace
     *
     * @param integer $user_id
     * @return integer|null
     */
    public function getRPGCharRace(int $user_id): ?int
    {
        $user = $this->getUser($user_id);

        if ($user) {
            return $user['rpg_charrace'];
        }

        return null;
    }

    /**
     * setRPGCharRace
     *
     * @param integer $user_id
     * @param integer $race
     * @return boolean
     */
    public function setRPGCharRace(int $user_id, int $race): bool
    {
        $query = $this->prepare("UPDATE users SET rpg_charrace = ? WHERE id = ?");

        return $query->execute([$race, $user_id]);
    }

    /**
     * getRPGCharGender
     *
     * @param integer $user_id
     * @return integer|null
     */
    public function getRPGCharGender(int $user_id): ?int
    {
        $user = $this->getUser($user_id);

        if ($user) {
            return $user['rpg_chargender'];
        }

        return null;
    }

    /**
     * setRPGCharGender
     *
     * @param integer $user_id
     * @param integer $gender
     * @return boolean
     */
    public function setRPGCharGender(int $user_id, int $gender): bool
    {
        $query = $this->prepare("UPDATE users SET rpg_chargender = ? WHERE id = ?");

        return $query->execute([$gender, $user_id]);
    }

    /**
     * createTables
     *
     * @return void
     */
    public function createTables(): void
    {
        $this->exec(<<<EOF
        CREATE TABLE IF NOT EXISTS `users` (
            `id` bigint(21) NOT NULL AUTO_INCREMENT PRIMARY KEY
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        EOF);

        $columns = [
            "users" => [
                "discord_id" => "bigint(21) NOT NULL",
                "money" => "int(11) DEFAULT '0'",
                "last_daily" => "int(11) DEFAULT NULL",
                "register_time" => "int(11) DEFAULT NULL",
                "rpg_chartype" => "int(11) DEFAULT NULL",
                "rpg_charrace" => "int(11) DEFAULT NULL",
                "rpg_chargender" => "int(11) DEFAULT NULL",
            ]
        ];

        foreach ($columns as $table_key => $table) {
            foreach ($table as $column_key => $column) {
                try {
                    $col = $this->query("SELECT $column_key FROM $table_key");
                } catch (\Exception $e) {
                    $this->exec("ALTER TABLE `$table_key` ADD $column_key $column;");
                }
            }
        }
    }
}
