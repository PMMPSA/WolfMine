<?php

namespace PTK\CustomEnchants\Entities;

use pocketmine\block\Block;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityCombustByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\MovingObjectPosition;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

/**
 * Class Fireball
 * @package PTK\CustomEnchants\Entities
 */
class Fireball extends Projectile
{
    const NETWORK_ID = 94;

    /**
     * @param int $tickDiff
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1): bool
    {
        if ($this->closed) {
            return false;
        }
        if ($this->isAlive()) {
            if ($this->isCollided) {
                if (!$this->hadCollision) {
                    $this->hadCollision = true;
                    $this->motionX = 0;
                    $this->motionY = 0;
                    $this->motionZ = 0;
                    $this->server->getPluginManager()->callEvent(new ProjectileHitEvent($this));
                    if ($this->isCollidedHorizontally || $this->isCollidedVertically) {
                        if ($this->getLevel()->getBlock($this)->canBeFlowedInto()) {
                            $this->getLevel()->setBlock($this, Block::get(Block::FIRE));
                        }
                    }
                } else {
                    $this->close();
                }
            }
        }
        $hasUpdate = parent::entityBaseTick($tickDiff);
        return $hasUpdate;
    }
}