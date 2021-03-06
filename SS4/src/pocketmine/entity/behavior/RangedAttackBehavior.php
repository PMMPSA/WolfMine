<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\entity\behavior;

use pocketmine\entity\Mob;
use pocketmine\entity\RangedAttackerMob;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use function floor;
use function sqrt;

class RangedAttackBehavior extends Behavior{

	/** @var float */
	protected $speedMultiplier = 1.0;
	/** @var int */
	protected $minAttackTime, $maxAttackTime;
	/** @var float */
	protected $maxAttackDistanceIn, $maxAttackDistance;
	/** @var int */
	protected $rangedAttackTime = 0;
	/** @var int */
	protected $targetSeenTicks = 0;

	public function __construct(Mob $mob, float $speedMultiplier, int $minAttackTime, int $maxAttackTime, float $maxAttackDistanceIn){
		parent::__construct($mob);

		$this->speedMultiplier = $speedMultiplier;
		$this->minAttackTime = $minAttackTime;
		$this->maxAttackTime = $maxAttackTime;
		$this->maxAttackDistanceIn = $maxAttackDistanceIn;
		$this->maxAttackDistance = $maxAttackDistanceIn ** 2;
		$this->rangedAttackTime = -1;
		$this->mutexBits = 3;
	}

	public function canStart() : bool{
		return $this->mob->getTargetEntityId() !== null;
	}

	public function canContinue() : bool{
		return $this->canStart() or $this->mob->getNavigator()->isBusy();
	}

	public function onEnd() : void{
		$this->targetSeenTicks = 0;
		$this->rangedAttackTime = -1;
	}

	public function onTick() : void{
		if(!$this->canStart()) return;
		$targetEntity = $this->mob->getTargetEntity();
		if($targetEntity === null) return;
		$dist = $this->mob->distanceSquared($targetEntity);

		if($flag = $this->mob->canSeeEntity($targetEntity)){
			++$this->targetSeenTicks;
		}else{
			$this->targetSeenTicks = 0;
		}

		if($dist <= $this->maxAttackDistance and $this->targetSeenTicks >= 20){
			$this->mob->getNavigator()->clearPath();
		}else{
			$this->mob->getNavigator()->tryMoveTo($targetEntity, $this->speedMultiplier);
		}

		$this->mob->getLookHelper()->setLookPositionWithEntity($targetEntity, 30, 30);

		if(--$this->rangedAttackTime <= 0){
			if($dist > $this->maxAttackDistance or !$flag){
				return;
			}

			$f = sqrt($dist) / $this->maxAttackDistanceIn;
			if($f > 1) $f = 1;
			if($f < 0.1) $f = 0.1;

			if($this->mob instanceof RangedAttackerMob){
				$this->mob->onRangedAttackToTarget($this->mob->getTargetEntity(), $f);
			}

			if($dist < 1){
				$targetEntity->attack(new EntityDamageByEntityEvent($this->mob, $targetEntity, EntityDamageEvent::CAUSE_CUSTOM, $this->mob->getAttackDamage()));
			}

			$this->rangedAttackTime = floor($f * ($this->maxAttackTime - $this->minAttackTime) + $this->minAttackTime);
		}
	}
}