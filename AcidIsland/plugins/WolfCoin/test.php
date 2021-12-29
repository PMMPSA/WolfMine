	public function reduceLevel($player, $amount, $force = false, $issuer = "external"){
		if($amount <= 0 or !is_numeric($amount)){
			return self::RET_INVALID;
		}

		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		$amount = round($amount, 2);
		if(isset($this->level["level"][$player])){
			if($this->level["level"][$player] - $amount < 0){
				return self::RET_INVALID;
			}
			$event = new ReduceLevelEvent($this, $player, $amount, $issuer);
			$this->getServer()->getPluginManager()->callEvent($event);
			if($force === false and $event->isCancelled()){
				return self::RET_CANCELLED;
			}
			$this->level["level"][$player] -= $amount;
			$this->getServer()->getPluginManager()->callEvent(new LevelChangedEvent($this, $player, $this->level["level"][$player], $issuer));
			return self::RET_SUCCESS;
		}else{
			return self::RET_NOT_FOUND;
		}
	}