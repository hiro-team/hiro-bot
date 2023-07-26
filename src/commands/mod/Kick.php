<?php

/**
 * Copyright 2023 bariscodefx
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

use Discord\Parts\Embed\Embed;

class Kick extends Command
{

    /**
     * configure
     *
     * @return void
     */
    public function configure(): void
    {
        $this->command = "kick";
        $this->description = "Kicks mentioned user.";
        $this->aliases = [];
        $this->category = "mod";
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
        if(@$msg->member->getPermissions()['kick_members'])
        {
            $user = @$msg->mentions->first();
            if($user)
            {
                $kicker = $msg->author;
                if(!isset($msg->channel->guild->members[$user->id])) 
                {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("User couldn't found.");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                    return;
                }
                $roles_men = $this->rolePositionsMap($msg->channel->guild->members[$user->id]->roles);
                $roles_self = $this->rolePositionsMap($msg->member->roles);
                if( $roles_men )
                {
                    $roles_men = max($roles_men);
                } else {
                    $roles_men = 0;
                }
                if( $roles_self )
                {
                    $roles_self = max($roles_self);
                } else {
                    $roles_men = 0;
                }
                if($kicker->id == $user->id)
                {
                    $embed = new Embed($this->discord);
                    $embed->setColor('#ff0000');
                    $embed->setDescription("You can't kick yourself");
                    $embed->setTimestamp();
                    $msg->channel->sendEmbed($embed);
                    return;
                }else {
                    if( ($roles_self < $roles_men) && !($msg->channel->guild->owner_id == $msg->member->id) )
                    {
                        $embed = new Embed($this->discord);
                        $embed->setColor('#ff0000');
                        $embed->setDescription("Your role position too low!");
                        $embed->setTimestamp();
                        $msg->channel->sendEmbed($embed);
                    } else {
                        $msg->channel->guild->members->delete($msg->channel->guild->members[$user->id])
                            ->then(function() use ( $msg, $user, $kicker ) {
                                $embed = new Embed($this->discord);
                                $embed->setColor('#ff0000');
                                $embed->setDescription("$user kicked by $kicker.");
                                $embed->setTimestamp();
                                $msg->channel->sendEmbed($embed);
                            }, function (\Throwable $reason) use ( $msg ) {
                                $msg->reply($reason->getCode() === 50013 ? "I don't have permission to kick users. Check my role position or permission." : "Unknown error excepted.");
                            });
                    }
                }
            }else {
                $embed = new Embed($this->discord);
                $embed->setColor('#ff0000');
                $embed->setDescription("If you want kick a user you must mention a user.");
                $embed->setTimestamp();
                $msg->channel->sendEmbed($embed);
            }
        }else {
            $embed = new Embed($this->discord);
            $embed->setColor('#ff0000');
            $embed->setDescription("If you want kick a user, you must have `kick_members` permission.");
            $embed->setTimestamp();
            $msg->channel->sendEmbed($embed);
        }
    }

    /**
     * rolePositionsMap
     * 
     * This function returns descending list role positions of server for user.
     *
     * @param [type] $rolesCollision
     * @return void
     */
    protected function rolePositionsMap($rolesCollision)
    {
        $rolesArray = $rolesCollision->toArray();
        $new = [];
        foreach($rolesArray as $role)
        {
            $new[] = $role->position;
        }
        return $new;
    }
}
