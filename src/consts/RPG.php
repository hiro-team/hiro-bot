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
    public const DRAGON_RACE                       = (1 << 2);
    public const ELF_RACE                          = (1 << 3);
    public const EVILEYETRIBE_RACE                 = (1 << 4);
    public const HALFONI_RACE                      = (1 << 5);
    public const HALFELF_RACE                      = (1 << 6);
    public const ONI_RACE                          = (1 << 7);
    public const WOLFHUMAN_RACE                    = (1 << 8);

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
     * Armor Types
     */
    public const ARMOR_HELMET                      = (1 << 1);
    public const ARMOR_PAULDRON                    = (1 << 2);
    public const ARMOR_LEGGINGS                    = (1 << 3);
    public const ARMOR_SHOES                       = (1 << 4);
    public const ARMOR_GAUNTLETS                   = (1 << 5);

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
            "dragon" => self::DRAGON_RACE,
            "elf" => self::ELF_RACE,
            "evileyetribe" => self::EVILEYETRIBE_RACE,
            "halfoni" => self::HALFONI_RACE,
            "halfelf" => self::HALFELF_RACE,
            "oni" => self::ONI_RACE,
            "wolfhuman" => self::WOLFHUMAN_RACE,
        ];

        if ($swap) {
            $array = array_flip($array);
        }

        return $array;
    }
}
