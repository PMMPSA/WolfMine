<?php

declare(strict_types=1);

namespace MrDinoDuck\DeathNPC;

use pocketmine\entity\Human;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\SetActorDataPacket as SetEntityDataPacket;
use pocketmine\Player;

class NPC extends Human {

    public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
    }
}