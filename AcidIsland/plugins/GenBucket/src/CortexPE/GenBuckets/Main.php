<?php

/*
   ____           _            ____  _____ 
  / ___|___  _ __| |_ _____  _|  _ \| ____|
 | |   / _ \| '__| __/ _ \ \/ / |_) |  _|  
 | |__| (_) | |  | ||  __/>  <|  __/| |___ 
  \____\___/|_|   \__\___/_/\_\_|   |_____|
  
 * Copyright (C) CortexPE - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Ralph B <mcpe4life62@gmail.com>, ///////////////////DATE (February 24 2017)///////////////
 */

namespace CortexPE\GenBuckets;

use PTK\coinapi\Coin;
use pocketmine\block\Solid;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
	/** @var Config */
	private $conf;

	/** @var array */
	private $buckets = [];

	/** @var EconomyAPI */
	private $coin;


	public function onLoad(){
		$this->getLogger()->info('Loading Configuration...');
		@mkdir($this->getDataFolder());
		$this->conf = new Config($this->getDataFolder() . "config.yml",Config::YAML,[
			"buckets" => [
				"cobble" => [
					"blockId" => 4,
					"bucketDamage" => 4,
					"bucketPrice" => 100,
					"bucketName" => "§cCobblestone Bucket",
				],
				"sand" => [
					"blockId" => 12,
					"bucketDamage" => 12,
					"bucketPrice" => 100,
					"bucketName" => "§eSand Bucket",
				],
				"obsidian" => [
					"blockId" => 49,
					"bucketDamage" => 49,
					"bucketPrice" => 150,
					"bucketName" => "§1Obsidian Bucket",
				],
				"bedrock" => [
					"blockId" => 7,
					"bucketDamage" => 7,
					"bucketPrice" => 200,
					"bucketName" => "§7Bedrock Bucket",
				],
			]
		]);

		$this->buckets = $this->conf->get("buckets",[]);
	}

	public function onEnable(){
        $this->getLogger()->info('GenBuckets Loaded');

        $this->coin = Coin::getInstance();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
	
	public function onDisable(){
        $this->getLogger()->info('GenBuckets Disabled');
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if($command->getName() === "genbucket"){
			if(isset($args[0])){
				if($sender instanceof Player){
					if(isset($this->buckets[strtolower($args[0])])){
						$arr = $this->buckets[strtolower($args[0])];
						$p = $sender->getName();
						if($this->coin->myCoin($p) >= $arr["bucketPrice"]){
							$this->coin->reduceCoin($p, $arr["bucketPrice"]);

							$bucket = Item::get(Item::BUCKET);
							$bucket->setDamage($arr["bucketDamage"]);
							$bucket->setCustomName($arr["bucketName"]);

							$sender->getInventory()->addItem($bucket);
							$sender->sendMessage("§aMua thành công");
						} else {
							$sender->sendMessage("§bBạn không có đủ WC để mua");
						}
					} else {
						$sender->sendMessage("§cBucket không tồn tại");
					}
				}
			} else {
				$i = 0;
				foreach($this->buckets as $bucket){
					$sender->sendMessage("§c♦§a ".$bucket["bucketName"]."§b giá: §d".$bucket["bucketPrice"]."§l§c WC");
					$i++;
				}
			}
		}
		return true;
	}

	/*public function onHeld(PlayerItemHeldEvent $event){
		$player = $event->getPlayer();
		$i = $player->getInventory()->getItemInHand();

		if($i->getId() === Item::BUCKET){
			foreach($this->buckets as $bucket){
				if($i->getdamage() === $bucket["bucketDamage"]){
					$player->sendPopup($bucket["bucketName"]); // #BlameMojang
				}
			}
		}
	}*/

	public function onTap(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$b = $event->getBlock();
		$i = $player->getInventory()->getItemInHand();
		$face = $event->getFace();

		if($i->getId() === Item::BUCKET){
			foreach($this->buckets as $bucket){
				if($i->getdamage() === $bucket["bucketDamage"]){
					$event->setCancelled(true);
					$player->getInventory()->setItemInHand(Item::get(0));
					$x = $b->getX();
					$evY = $b->getY();
					$y = $evY;
					$z = $b->getZ();
					$evLEVEL = $event->getBlock()->getLevel();

					switch($face){
						case 2:
							$z--;
							break;
						case 3:
							$z++;
							break;

						case 4:
							$x--;
							break;
						case 5:
							$x++;
							break;

						case 1:
							$y++;
							break;
						case 0:
							$y--;
							break;
					}

					while($y > 1) {
						if(!($evLEVEL->getBlock(new Vector3($x, $y, $z)) instanceof Solid)){
							$evLEVEL->setBlock(new Vector3($x, $y, $z), Block::get($bucket["blockId"]), false, false);
							$y--;
						} else {
							break;
						}
					}
				}
			}
		}
	}
}