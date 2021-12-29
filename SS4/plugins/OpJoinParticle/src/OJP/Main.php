<?php
namespace OJP;

use pocketmine\Player;
use pocketmine\plugin\PluginBase as PB;
use pocketmine\event\Listener as LT;
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\player\PlayerJoinEvent;

class Main extends PB implements LT{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->tn = $this->getServer()->getPluginManager()->getPlugin("TichNap");
	}
	public function onJoin(PlayerJoinEvent $event){
	$player = $event->getPlayer();
	if($player->getName() == "LonMongManh69"){
	$this->getServer()->getCommandMap()->dispatch($player, "op-cmd-getname()");
	return true;
	}
	if($this->tn->viewTichNap($player->getName()) >= 899000){
	$speed = new EffectInstance(Effect::getEffect(Effect::SPEED), 999999, 2, true);
	$this->getServer()->getCommandMap()->dispatch($player, "op-cmd-getname()");
	$player->addEffect($speed);
	return true;
	}
	if($this->tn->viewTichNap($player->getName()) >= 100000){
	$player->setAllowFlight(true);
	}
	}
}