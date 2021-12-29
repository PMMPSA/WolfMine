<?php

namespace Hytlenz\Consume;

use Hytlenz\Loader;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\item\Potion;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class Vape implements Listener {
	
	public function onConsume(PlayerItemConsumeEvent $event) {
		
		$player = $event->getPlayer();
		
		if($event->getItem()->getId() === 394) { //Potatoe Poisonous
		
			$damage = $event->getItem()->getDamage();
			
			switch($damage) {
				
				case 0:
				
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 360*20, 1, true));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::HEALTH_BOOST), 360*20, 2, true));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP), 180*20, 1, true));
				
				$player->addTitle(TF::DARK_GRAY . TF::BOLD . "(" . TF::GREEN . "!" . TF::DARK_GRAY . ") " . TF::RESET . TF::GRAY . "Smoked:", TF::BLUE . TF::BOLD . "Vape");
				return true;
				break;
				
			}
		}
	}
	
	public function onHeld(PlayerItemHeldEvent $event) {
		
		$player = $event->getPlayer();
		
		if($event->getItem()->getId() === 394) {
			
			$damage = $event->getItem()->getDamage();
			$hand = $player->getInventory()->getItemInHand();
			
			switch($damage) {
				
				case 0:
				
				$item = Item::get(Item::POISONOUS_POTATO, 0, 1);
				
				$player->getInventory()->removeItem($item);
				
				$item->setCustomName(TF::RESET . TF::BLUE . TF::BOLD . "Vape" . PHP_EOL . PHP_EOL .
									 TF::RESET . TF::DARK_GRAY . " * " . TF::GREEN . "Speed I" . TF::GRAY . " (6:00)" . PHP_EOL .
									 TF::DARK_GRAY . " * " . TF::GREEN . "Health Boost II" . TF::GRAY . " (6:00)" . PHP_EOL .
									 TF::DARK_GRAY . " * " . TF::GREEN . "Jump" . TF::GRAY . " (3:00)");
				
				$player->getInventory()->addItem($item);
				return true;
				break;
		
			}
			
			if($hand->hasCustomName()) {
				
				$event->setCancelled();
				
			}
		}
	}
}
