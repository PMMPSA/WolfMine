<?php
namespace Hytlenz\Weapons;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

use pocketmine\level\particle\DestroyBlockParticle as BloodParticle;
use pocketmine\level\particle\FlameParticle as WeaponShootParticle;
use pocketmine\level\sound\AnvilFallSound as DropBombSound;
use pocketmine\level\sound\BlazeShootSound as WeaponShootSound;
use pocketmine\level\sound\DoorCrashSound as ExplodeSound;

use pocketmine\level\Explosion;
use pocketmine\level\Position;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\block\Air;
use pocketmine\block\Block;

use pocketmine\item\Item;
use pocketmine\item\Snowball as Bullet;

use pocketmine\entity\Entity;
use pocketmine\entity\Snowball;
use pocketmine\entity\Egg;
use pocketmine\entity\PrimedTNT;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;

use pocketmine\event\player\PlayerMoveEvent as PlayerWalkEvent;
use pocketmine\event\player\PlayerInteractEvent as PlayerUseWeaponEvent;
use pocketmine\event\player\PlayerItemHeldEvent;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\math\Vector3;

use pocketmine\inventory\Inventory;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class CrackShot implements Listener{

	public function onExplode(ExplosionPrimeEvent $event){
		$event->setBlockBreaking(false);
	}

	public function onDamage(EntityDamageEvent $event){
		if($event instanceof EntityDamageByChildEntityEvent){
			$child = $event->getChild();
			if($child instanceof Snowball){
				$event->setDamage(5);
			}
			//if($child instanceof Egg){
				//$event->setDamage(8);
			//}
			if($child->y - $event->getEntity()->y > 1.35){
				//$event->setDamage(6);
			}
		}
		
	}

	public function onHold(PlayerItemHeldEvent $event){
		$player = $event->getPlayer();
		$item = $event->getItem();
		if($item->getId() == 332){ //Snowball
		//if($item->getName() === "Snowball"){
			$item->setCustomName("§bĐạn");
		}elseif($item->getId() == 346){ //Fishing Rod
		//}elseif($item->getName() === "§bSúng 1 Nòng"){
			if($player->getInventory()->contains(new Bullet(0, 1))){
				$item->setCustomName("§bSúng 1 Nòng");
			}
	}elseif($item->getId() == 398){ //Fishing Rod Carrot
		//}elseif($item->getName() === "§bSúng 3 Nòng"){
			if($player->getInventory()->contains(new Bullet(0, 1))){
				$item->setCustomName("§bSúng 3 Nòng");
			}
		}elseif($item->getId() === 359){ //Shears
			if($player->getInventory()->contains(new Bullet(0, 100000))){
				$item->setCustomName(TextFormat::RESET . TextFormat::WHITE . "Explosive Gun");
			}
		}
	}

	public function onShoot(PlayerUseWeaponEvent $event){
		$player = $event->getPlayer();
		$level = $player->getLevel();
		$item = $event->getItem();
		$block = $player->getLevel()->getBlock($player->floor()->subtract(0, 1));
		$fdefault = 1.5;
		$nbtdefault = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x ), new DoubleTag( "", $player->y + $player->getEyeHeight () ), new DoubleTag( "", $player->z ) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
		if($item->getId() == 332){ //Snowball
		//if($item->getName() === "Snowball"){
			
			if($player->getInventory()->contains(new Bullet(0, 1))){
				$item->setCustomName("§bĐạn");
      			}
		}elseif($item->getId() == 346){ //Fishing Rod
			if($player->getInventory()->contains(new Bullet(0, 1))){
				$bullet = Entity::createEntity("Snowball", $level, $nbtdefault, $player);
				$bullet->setMotion($bullet->getMotion()->multiply($fdefault));
				$bullet->spawnToAll();
				$player->getLevel()->addSound(new WeaponShootSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
				$player->getLevel()->addParticle(new WeaponShootParticle(new Vector3($player->x + 0.4, $player->y, $player->z)));
				$player->getInventory()->removeItem(Item::get(332, 0, 1));
				$player->getInventory()->sendContents($player);
			}
		}elseif($item->getId() == 398){ //Fishing Rod Carrot
			if($player->getInventory()->contains(new Bullet(0, 1))){
				$nbt1 = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x + 1 ), new DoubleTag( "", $player->y + $player->getEyeHeight () ), new DoubleTag( "", $player->z ) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
				$nbt2 = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x - 1 ), new DoubleTag( "", $player->y + $player->getEyeHeight () ), new DoubleTag( "", $player->z ) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
				$nbt3 = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x ), new DoubleTag( "", $player->y + $player->getEyeHeight () ), new DoubleTag( "", $player->z + 1) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
				$nbt4 = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x ), new DoubleTag( "", $player->y + $player->getEyeHeight () ), new DoubleTag( "", $player->z - 1) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
				$nbt5 = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x ), new DoubleTag( "", $player->y + $player->getEyeHeight () + 1), new DoubleTag( "", $player->z ) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
				$nbt6 = new CompoundTag( "", [ "Pos" => new ListTag( "Pos", [ new DoubleTag( "", $player->x ), new DoubleTag( "", $player->y + $player->getEyeHeight () - 1), new DoubleTag( "", $player->z ) ]), "Motion" => new ListTag( "Motion", [ new DoubleTag( "", - \sin ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "", - \sin ( $player->pitch / 180 * M_PI ) ), new DoubleTag( "",\cos ( $player->yaw / 180 * M_PI ) *\cos ( $player->pitch / 180 * M_PI ) ) ]), "Rotation" => new ListTag( "Rotation", [ new FloatTag( "", $player->yaw ), new FloatTag( "", $player->pitch ) ]) ]);
				$bullet1 = Entity::createEntity("Snowball", $level, $nbt1, $player);
				$bullet2 = Entity::createEntity("Snowball", $level, $nbt2, $player);
				$bullet3 = Entity::createEntity("Snowball", $level, $nbt3, $player);
				$bullet4 = Entity::createEntity("Snowball", $level, $nbt4, $player);
				$bullet5 = Entity::createEntity("Snowball", $level, $nbt5, $player);
				$bullet6 = Entity::createEntity("Snowball", $level, $nbt6, $player);
				$bullet1->setMotion($bullet1->getMotion()->multiply($fdefault));
				$bullet2->setMotion($bullet2->getMotion()->multiply($fdefault));
				$bullet3->setMotion($bullet3->getMotion()->multiply($fdefault));
				$bullet4->setMotion($bullet4->getMotion()->multiply($fdefault));
				$bullet5->setMotion($bullet5->getMotion()->multiply($fdefault));
				$bullet6->setMotion($bullet6->getMotion()->multiply($fdefault));
				$bullet1->spawnToAll();
				$bullet2->spawnToAll();
				$bullet3->spawnToAll();
				$bullet4->spawnToAll();
				$bullet5->spawnToAll();
				$bullet6->spawnToAll();
				$player->getLevel()->addSound(new WeaponShootSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
				$player->getLevel()->addParticle(new WeaponShootParticle(new Vector3($player->x + 0.4, $player->y, $player->z)));
				$player->getInventory()->removeItem(Item::get(332, 0, 1));
				$player->getInventory()->sendContents($player);
			}
		}elseif($item->getId() == 359){ //Shears
			if($player->getInventory()->contains(new Bullet(0, 5))){
				$f = 0.1;
				$tnt = Entity::createEntity("PrimedTNT", $level, $nbtdefault, $player);
				$tnt->setMotion($tnt->getMotion()->multiply($f));
				$tnt->spawnToAll();
				$player->getLevel()->addSound(new DropBombSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
				$player->getLevel()->addParticle(new WeaponShootParticle(new Vector3($player->x + 0.4, $player->y, $player->z)));
				$player->getInventory()->removeItem(Item::get(332, 0, 5));
				$player->getInventory()->sendContents($player);
			}
		}
	}
}