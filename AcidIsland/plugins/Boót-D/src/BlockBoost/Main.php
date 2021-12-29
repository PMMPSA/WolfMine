<?php

namespace BlockBoost;

use pocketmine\plugin\PluginBase as Plugin;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\entity\PrimedTNT;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\level\sound\GhastSound;

class Main extends Plugin implements Listener 

{
	public function onEnable() 
	
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info("§aBlockBoost Activate v1.0.0");
	}
	
	public function onDisable() 
	
	{
		$this->getServer()->getLogger()->info("§4BlockBoost Desactivate v1.0.0");
	}
 	
	public function onMove(PlayerMoveEvent $event)
	
	{
		$player = $event->getPlayer();
 		$from = $event->getFrom();
 		$to = $event->getTo();
 		if($from->getLevel()->getBlockIdAt($from->x, $from->y - 1, $from->z) === Block::BEACON)
		
			{
				$player->setMotion((new Vector3($to->x - $from->x, $to->y - $from->y, $to->z - $from->z))->multiply(5)); /* 5 is the power, You can change it if you want */
			}
 	}
}

 


