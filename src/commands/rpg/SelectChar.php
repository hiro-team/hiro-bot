<?php

namespace hiro\commands;

class SelectChar extends Command {
    
    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "selectchar";
        $this->description = "Select your character type like mage, archer...";
        $this->aliases = [];
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
        
    }

}