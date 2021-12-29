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

class Alcohol implements Listener {
	
	public function onConsume(PlayerItemConsumeEvent $event) {
		
		$player = $event->getPlayer();
		
		if($event->getItem()->getId() === 373) {
		
			$damage = $event->getItem()->getDamage();
			
			switch($damage) {
				
				case 0:
				
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 360*20, 1, false));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 360*20, 2, false));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::NAUSEA), 10*20, 1, false));
				
				$player->getInventory()->removeItem(Item::get(Item::POTION, 0, 1));
				$player->getInventory()->addItem(Item::get(Item::GLASS_BOTTLE, 0, 1));
				$player->addTitle(TF::DARK_GRAY . TF::BOLD . "(" . TF::GREEN . "!" . TF::DARK_GRAY . ") " . TF::RESET . TF::GRAY . "Consumed:", TF::RED . TF::BOLD . "Alcohol");
				return true;
				break;
				
				case 1:
				
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 360*20, 1, false));
				$player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 360*20, 1, false));
				
				$player->getInventory()->removeItem(Item::get(Item::POTION, 1, 1));
				$player->getInventory()->addItem(Item::get(Item::GLASS_BOTTLE, 0, 1));
				$player->addTitle(TF::DARK_GRAY . TF::BOLD . "(" . TF::GREEN . "!" . TF::DARK_GRAY . ") " . TF::RESET . TF::GRAY . "Consumed:", TF::AQUA . TF::BOLD . "Wine");
				return true;
				break;
				
			}
		}
	}
	
	public function onHeld(PlayerItemHeldEvent $event) {
		
		$player = $event->getPlayer();
		
		if($event->getItem()->getId() === 373) {
			
			$damage = $event->getItem()->getDamage();
			$hand = $player->getInventory()->getItemInHand();
			
			switch($damage) {
				
				case 0:
				
				$item = Item::get(Item::POTION, 0, 1);
				
				$player->getInventory()->removeItem($item);
				
				$item->setCustomName(TF::RESET . TF::RED . TF::BOLD . "Alcohol" . PHP_EOL . PHP_EOL .
									 TF::RESET . TF::DARK_GRAY . " * " . TF::GREEN . "Speed I" . TF::GRAY . " (6:00)" . PHP_EOL .
									 TF::DARK_GRAY . " * " . TF::GREEN . "Strength II" . TF::GRAY . " (6:00)" . PHP_EOL .
									 TF::DARK_GRAY . " * " . TF::GREEN . "Nausea" . TF::GRAY . " (0:10)");
				
				$player->getInventory()->addItem($item);
				return true;
				
				break;
				
				case 1:
				
				$item = Item::get(Item::POTION, 1, 1);
				
				$player->getInventory()->removeItem($item);
				
				$item->setCustomName(TF::RESET . TF::AQUA . TF::BOLD . "Wine" . PHP_EOL . PHP_EOL .
									 TF::RESET . TF::DARK_GRAY . " * " . TF::GREEN . "Night Vision " . TF::GRAY . " (6:00)" . PHP_EOL .
									 TF::DARK_GRAY . " * " . TF::GREEN . "Speed" . TF::GRAY . " (6:00)");
									 
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
