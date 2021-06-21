<?php

/**
* Coded by bariscodefx with GNU License
*/

namespace hiro;

use Discord\DiscordCommandClient;

/**
* CommandLoader
*/
class CommandLoader
{
    
    /**
     * Command categories
     */
    protected $categories = [];

    /**
     * CommandLoader $version
     */
    protected $version = "1.1";
    
    /**
     * Commands Directory
     */
    protected $dir = "";

    /**
    * CommandLoader Constructor
    */
    public function __construct(DiscordCommandClient $client) {
        $this->dir = __DIR__ . "/commands";
        $this->loadAllCommands($client);
    }
    
    public function loadCommand(DiscordCommandClient $client, string $commandName)
    {
        
    }
    
    /**
     * Loads all commands
     */
    public function loadAllCommands(DiscordCommandClient $client)
    {
        $dir = __DIR__ . '/commands';
        $this->clearConsole();
        $this->loaderInfo();
        print "Loading Commands" . PHP_EOL;
        foreach (scandir($dir) as $file) {
            $extension = substr($file, -4);
            if ($file != '.' && $file != '..' && $extension == '.php') {
                $class = substr($file, 0, -4);
                $classnamespace = 'hiro\commands\\' . $class;
                $cmd = new $classnamespace($client, $this);
                print "====================" . PHP_EOL;
                print "Loaded : $class" . PHP_EOL;
                print "====================" . PHP_EOL;
                if(!isset($this->categories))
                {
                    $this->categories = [];
                }
                if(!isset($this->categories[$cmd->category]))
                {
                    $this->categories[$cmd->category] = [];
                }
                $kategori = $this->categories[$cmd->category];
                array_push($kategori, $client->commands[strtolower($class)]->command);
                $this->categories[$cmd->category] = $kategori;
            }
        }
        print "All Commands Are Loaded." . PHP_EOL;
    }
    
    private function clearConsole()
    {
        for ($i = 0; $i < 100; $i++)
        {
            print PHP_EOL;
        }
    }
    private function loaderInfo()
    {
        exec("figlet \"Command\nLoader\nv$this->version\"", $figlet);
        foreach($figlet as $line)
        {
            print $line . PHP_EOL;
        }
        print PHP_EOL . PHP_EOL;
    }
    
    public function __get(string $var)
    {
        return $this->{$var};
    }

}
