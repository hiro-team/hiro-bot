<?php

namespace hiro\commands;

use hiro\consts\RPG;
use hiro\database\Database;

class ReleaseItem extends Command
{
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "releaseitem";
        $this->description = "Releases an item from your uses.";
        $this->aliases = ["unuse", "unuseitem"];
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

        $slot = $args[0] ?? null;
        switch(strtolower($slot)) {
            case "boots":
                $slot = RPG::ITEM_ARMOR_BOOTS;
                break;
            case "gloves":
                $slot = RPG::ITEM_ARMOR_GLOVES;
                break;
            case "helmet":
                $slot = RPG::ITEM_ARMOR_HELMET;
                break;
            case "pants":
                $slot = RPG::ITEM_ARMOR_PANTS;
                break;
            case "pauldron":
                $slot = RPG::ITEM_ARMOR_PAULDRON;
                break;
            case "weapon":
                $slot = RPG::ITEM_WEAPON;
                break;
            default:
                $msg->reply('Available argument(s): boots, gloves, helmet, pants, pauldron');
                return;
                break;
        }

        $toslot = $database->findRPGEmptyInventorySlot($msg->author->id);
        if ($toslot === false) {
            $msg->reply('You inventory is full!');
            return;
        }

        if (!$database->releaseRPGUserItem($msg->author->id, $slot, $toslot)) {
            $msg->reply('Couldn\'t release item.');
            return;
        }
    }
}