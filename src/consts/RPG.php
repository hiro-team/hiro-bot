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
class RPG {

    /**
     * Character Types
     */
    const UNSELECTED_CHAR                   = (0 << 0);
    const WARRIOR_CHAR                      = (1 << 0);
    const RANGER_CHAR                       = (1 << 1);
    const MAGE_CHAR                         = (3 << 0);
    const HEALER_CHAR                       = (2 << 1);

    /**
     * Race Types
     */
    const HUMAN_RACE                        = (0 << 0);
    const BEARHUMAN_RACE                    = (1 << 0);
    const BEASTHUMAN_RACE                   = (1 << 1);
    const DEERHUMAN_RACE                    = (3 << 0);
    const DEMONBEAST_RACE                   = (2 << 1);
    const DRAGON_RACE                       = (5 << 0);
    const DRAGONKIN_RACE                    = (3 << 1);
    const DWARF_RACE                        = (7 << 0);
    const EARTHDRAGON_RACE                  = (4 << 1);
    const ELF_RACE                          = (9 << 0);
    const EVILEYETRIBE_RACE                 = (5 << 1);
    const FOXHUMAN_RACE                     = (11 << 0);
    const GIANT_RACE                        = (6 << 1);
    const HALFONI_RACE                      = (13 << 0);
    const HALFELF_RACE                      = (7 << 1);
    const HYENAHUMAN_RACE                   = (15 << 0);
    const LIZARDHUMAN_RACE                  = (8 << 1);
    const ONI_RACE                          = (17 << 0);
    const PHANTOM_RACE                      = (9 << 1);
    const RABBITHUMAN_RACE                  = (19 << 0);
    const SHUDRAK_RACE                      = (10 << 1);
    const SNAKEHUMAN_RACE                   = (21 << 0);
    const SPIRIT_RACE                       = (11 << 1);
    const TANUKIHUMAN_RACE                  = (23 << 0);
    const WOLFHUMAN_RACE                    = (12 << 1);

}