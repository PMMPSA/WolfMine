<?php

namespace Tungst_onlrank;

use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;


class Task_OLR extends Task{

	private $owner;
	public function __construct(Plugin $owner){
		$this->owner = $owner;
	}


	public function onRun($tick){	
		$this->owner->taskRunner();
	}
}