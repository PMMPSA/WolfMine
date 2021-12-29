<?php

declare(strict_types=1);

namespace MrDinoDuck\DeathNPC;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use MrDinoDuck\DeathNPC\NPC;
use pocketmine\Server;
use pocketmine\nbt\tag\{
    CompoundTag, ListTag, DoubleTag, FloatTag
};
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\entity\Human;

class Main extends PluginBase implements Listener{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		Entity::registerEntity(NPC::class, true);
	}

	public function onDeath(PlayerDeathEvent $event) : void{
		$player = $event->getPlayer();
		$this->createDeathNPC($player);
	}
	
	public function createDeathNPC(Player $player){
		$nbt = new CompoundTag("", [
			new ListTag("Pos", [
				new DoubleTag("", $player->getX()),
				new DoubleTag("", $player->getY()),
				new DoubleTag("", $player->getZ())]),
            new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)]),
			new ListTag("Rotation", [
				new FloatTag("", 2),
				new FloatTag("", 2)
				])
			]);
		$nbt->setTag($player->namedtag->getTag("Skin"));
		$entity = Entity::createEntity("NPC", $player->getLevel(), $nbt);
		$entity->setNameTag("§l§cXÁC CHẾT " . $player->getName());
		$entity->setNameTagAlwaysVisible();
		$entity->spawnToAll();
	}
	
	public function onEntityDamage(EntityDamageEvent $event): void {
		$entity = $event->getEntity();
		if($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if($damager instanceof Player){
				if($entity instanceof NPC){
					$dnp = Server::getInstance()->getPluginManager()->getPlugin("DNP");
					$event->setCancelled();
					$damager = $event->getDamager();
					//$this->dnp->addDNP($damager->getName(), 1);
					$entity->close();
					$damager->sendMessage("§c(§eDọn Xác§c)§e:§b Bạn đã dọn xác thành công và được nhận 1 điểm Nhân Phẩm");
					$dnp->addDNP($damager->getName(), 1);
				}
            }
        }
    }

	public function onDisable() : void{
		$this->getLogger()->info("Bye");
	}
}
