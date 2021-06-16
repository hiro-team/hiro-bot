<?php


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
            return $query->fetch(PDO::FETCH_CLASS);
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
            return $user->money;
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
            return $query->fetch(PDO::FETCH_ASSOC)->id;
        else
            return false;
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
            try {
                $query->execute([
                    "discord_id" => $data['discord_id'],
                    "money" => 0,
                    "register_time" => time()
                ]);
            }catch (PDOException $e) {
                echo $e . PHP_EOL;
            }
        }
    }

}