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

namespace hiro\consts;

/**
 * RPG - Constants of RPG
 */
class RPG
{
    /**
     * Character Types
     */
    public const WARRIOR_CHAR                      = (1 << 1);
    public const RANGER_CHAR                       = (1 << 2);
    public const MAGE_CHAR                         = (1 << 3);
    public const HEALER_CHAR                       = (1 << 4);

    /**
     * Race Types
     */
    public const HUMAN_RACE                        = (1 << 1);
    public const BEARHUMAN_RACE                    = (1 << 2);
    public const BEASTHUMAN_RACE                   = (1 << 3);
    public const DEERHUMAN_RACE                    = (1 << 4);
    public const DEMONBEAST_RACE                   = (1 << 5);
    public const DRAGON_RACE                       = (1 << 6);
    public const DRAGONKIN_RACE                    = (1 << 7);
    public const DWARF_RACE                        = (1 << 8);
    public const EARTHDRAGON_RACE                  = (1 << 9);
    public const ELF_RACE                          = (1 << 10);
    public const EVILEYETRIBE_RACE                 = (1 << 11);
    public const FOXHUMAN_RACE                     = (1 << 12);
    public const GIANT_RACE                        = (1 << 13);
    public const HALFONI_RACE                      = (1 << 14);
    public const HALFELF_RACE                      = (1 << 15);
    public const HYENAHUMAN_RACE                   = (1 << 16);
    public const LIZARDHUMAN_RACE                  = (1 << 17);
    public const ONI_RACE                          = (1 << 18);
    public const PHANTOM_RACE                      = (1 << 19);
    public const RABBITHUMAN_RACE                  = (1 << 20);
    public const SHUDRAK_RACE                      = (1 << 21);
    public const SNAKEHUMAN_RACE                   = (1 << 22);
    public const SPIRIT_RACE                       = (1 << 23);
    public const TANUKIHUMAN_RACE                  = (1 << 24);
    public const WOLFHUMAN_RACE                    = (1 << 25);

    /**
     * Gender Types
     */
    public const MALE_GENDER                       = (1 << 1);
    public const FEMALE_GENDER                     = (1 << 2);

    /**
     * Weapons
     */
    public const SWORD_WEAPON                      = 1;
    public const BOW_WEAPON                        = 2;
    public const STAFF_WEAPON                      = 3;
    public const MORNINGSTAR_WEAPON                = 4;

    /**
     * Weapon Types
     */
    public const LONG_WEAPON                       = (1 << 1);
    public const SHORT_WEAPON                      = (1 << 2);

    /**
     * getRacesAsArray
     *
     * @param boolean $swap
     * @return array
     */
    public static function getRacesAsArray(bool $swap = false): array
    {
        $array = [
            "human" => self::HUMAN_RACE,
            "bearhuman" => self::BEARHUMAN_RACE,
            "beasthuman" => self::BEASTHUMAN_RACE,
            "deerhuman" => self::DEERHUMAN_RACE,
            "demonbeast" => self::DEMONBEAST_RACE,
            "dragon" => self::DRAGON_RACE,
            "dragonkin" => self::DRAGONKIN_RACE,
            "dwarf" => self::DWARF_RACE,
            "earthdragon" => self::EARTHDRAGON_RACE,
            "elf" => self::ELF_RACE,
            "evileyetribe" => self::EVILEYETRIBE_RACE,
            "foxhuman" => self::FOXHUMAN_RACE,
            "giant" => self::GIANT_RACE,
            "halfoni" => self::HALFONI_RACE,
            "halfelf" => self::HALFELF_RACE,
            "hyenahuman" => self::HYENAHUMAN_RACE,
            "lizardhuman" => self::LIZARDHUMAN_RACE,
            "oni" => self::ONI_RACE,
            "phantom" => self::PHANTOM_RACE,
            "rabbithuman" => self::RABBITHUMAN_RACE,
            "shudrak" => self::SHUDRAK_RACE,
            "snakehuman" => self::SNAKEHUMAN_RACE,
            "spirit" => self::SPIRIT_RACE,
            "tanukihuman" => self::TANUKIHUMAN_RACE,
            "wolfhuman" => self::WOLFHUMAN_RACE,
        ];

        if ($swap) {
            array_flip($array);
        }

        return $array;
    }
}
