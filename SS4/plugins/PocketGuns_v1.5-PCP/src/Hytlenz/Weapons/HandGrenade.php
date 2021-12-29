<?php

namespace Hytlenz\Weapons;

use pocketmine\entity\projectile\Egg;
use pocketmine\Level;
use pocketmine\level\Explosion;
use pocketmine\level\Position;
use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileHitEvent;

class HandGrenade implements Listener {

    public function onProjectileHit(ProjectileHitEvent $event) {
		$entity = $event->getEntity();
		if ($entity instanceof Egg) {
			$theX = $entity->getX();
			$theY = $entity->getY();
			$theZ = $entity->getZ();
			$level = $entity->getLevel();
			$thePosition = new Position($theX, $theY, $theZ, $level);
			$theExplosion = new Explosion($thePosition, 5, NULL);
			$theExplosion->explodeB();
            $impact = 1;
            $damage = 1;
         }
    }
}