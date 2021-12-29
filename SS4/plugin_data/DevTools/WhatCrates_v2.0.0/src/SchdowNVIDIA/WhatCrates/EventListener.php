<?php

/*
    WhatCrates:

    Copyright (C) 2019 SchdowNVIDIA
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types = 1);

namespace SchdowNVIDIA\WhatCrates;

use pocketmine\block\Block;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\Player;

class EventListener implements Listener {

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event) {
        //$this->plugin->sendFloatingText($event->getPlayer());
        if(in_array($event->getPlayer()->getLevel()->getName(), $this->plugin->worldsWithWhatCrates)) {
            $this->plugin->sendFloatingText($event->getPlayer(), false);
        };
    }

    public function onLevelChange(EntityLevelChangeEvent $event) {
        $player = $event->getEntity();
        if($player instanceof Player) {
            if(in_array($event->getTarget()->getName(), $this->plugin->worldsWithWhatCrates)) {
                $this->plugin->sendFloatingText($player, false);
            } else {
                $this->plugin->sendFloatingText($player, true);
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $block = $event->getBlock();
        if (in_array($block->getLevel()->getName(), $this->plugin->worldsWithWhatCrates)) {
            foreach ($this->plugin->crates as $whatcrate) {
                if ($whatcrate instanceof WhatCrate) {
                        $coords = $block->getLevel()->getName() . ":" . $block->getX() . ":" . $block->getY() . ":" . $block->getZ();
                        if ($coords === $whatcrate->getCompactPosition()) {
                            $this->plugin->WhatCrateRaffle($whatcrate, $event->getPlayer());
                        }

                }
            }
        }
    }

}