<?php

namespace hiro\commands;

use Discord\Builders\MessageBuilder;
use hiro\database\Database;
use hiro\helpers\RPGUI;
use hiro\consts\RPG;

/**
 * Inventory
 */
class Inventory extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "inventory";
        $this->description = "Opens your inventory.";
        $this->aliases = ["inv", "i"];
        $this->category = "rpg";
    }

    /**
     * handle
     *
     * @param [type] $msg
     * @param [type] $args
     * @return void
     */
    public function handle($msg, $args): void
    {
        $database = new Database();
        if (!$database->isConnected) {
            $msg->channel->sendMessage("Couldn't connect to database.");
            return;
        }

        $user = $msg->member;
        $user_id = $database->getUserIdByDiscordId($user->id);
        $money =  $database->getUserMoney($user_id);
        $gender = $database->getRPGCharGenderAsText($user_id);
        $race = $database->getRPGCharRaceAsText($user_id);
        $type = $database->getRPGCharTypeAsText($user_id);
        $items = $database->getRPGUserItems($user->id);
        $character = $gender . "_" . $race . "_" . $type;

        $msg->reply(MessageBuilder::new()->addFile($filepath = RPGUI::drawRPGInventoryUI($user->nick ?? $user->username, $character, $items, $money)))->then(function () use ($filepath) {
            unlink($filepath);
        });
    }
}
