<?php

namespace dropparty;

use pocketmine\plugin\PluginBase;
use dropparty\task\DropItemsTask;
use dropparty\task\DropPartyTask;
use pocketmine\utils\Config;

class DropParty extends PluginBase {
	
	public $secs = 0;
	public $tasks = [];
	public $status;
	public $time;
	
	public function onEnable() {
		$this->getLogger()->info("Has been enabled");
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "config/");
		$this->cfg = (new Config($this->getDataFolder() . "config/" . "config.yml", Config::YAML, array(
		"World" => "LB",
		"Time" => 20,
		"Duration" => 300,
		"Message.Started" => "§bLễ hội§6 drop đã bắt đầu tại hub",
		"Message.Ended" => "§bLễ hội§6 drop đã kết thúc",
		"Message.Countdown" => "§aLễ hội §bdrop§c sẽ bắt đầu trong §a{time}§b phút",
		"Popup.Enabled" => true,
		"Popup.Message" => "§bVật phẩm§c đang rơi ra từ phía §6Beacon",
		"Coordinates" => [
		"X" => 193,
		"Y" => 139,
		"Z" => -739,
		],
		"Items" => [
		57,
		42,
		22,
		41,
		130,
		18,
		5,
		17,
		388,
		89,
		138,
		121,
		276,
		],
		)))->getAll();
		
		$this->time = $this->cfg["Time"];
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new task\DropPartyTask($this), 20 * 60);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new task\DropItemsTask($this), 20);
	}
	
	public function config() {
		return $this->cfg;
	}
	
	public function getDropPartyTask() {
		return new DropPartyTask($this);
	}
	
	public function getDropItemsTask() {
		return new DropItemsTask($this);
	}
	
	public function getRandomItem() {
	  $rand = mt_rand(0, count($this->cfg["Items"]) - 1);
		
	  return $this->cfg["Items"][$rand];
		
	}
	
}