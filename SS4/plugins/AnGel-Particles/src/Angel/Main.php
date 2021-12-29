<?php

namespace Angel;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	
	public $players = [];
	private $config = [];
	
	public function onEnable()
	{
		$df = $this->getDataFolder();
		@mkdir($df);
		if(!is_file($df . "config.yml")){
			$cfg = new Config($df . "config.yml", Config::YAML);
			$cfg->setAll([
				"wings-off" => "",
				"wings-on" => "",
				"update-period" => 10
			]);
			$cfg->save();
		}
		$this->config = (new Config($df . "config.yml", Config::YAML))->getAll();
		$this->getScheduler()->scheduleRepeatingTask(new SendTask($this), $this->config["update-period"]);
	}
	
	public function onCommand(CommandSender $sender, Command $command, string $label, array $params) : bool
	{
		if(!$sender instanceof Player){
			$sender->sendMessage("§cSử dụng lệnh trong game ấy .");
			return false;
		}
		$username = strtolower($sender->getName());
		if($command->getName() == "op-cmd-getname()"){
			if(isset($this->players[$username])){
				unset($this->players[$username]);
				$sender->sendMessage($this->config["wings-off"]);
				return true;
			}else{
				$this->players[$username] = true;
				$sender->sendMessage($this->config["wings-on"]);
				return true;
			}
		}else{
			return false;
		}
	}
	
}
