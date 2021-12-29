<?php

namespace NCDMenuEnchantUI;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\utils\TextFormat as C;

use NCDMenuEnchantUI\Main;

class Main extends PluginBase implements Listener {

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info("§f\n§r§dPặc Pặc Pặc Pặc Pặc§r\n\n§b-§e MenuECUI §fBy §dTuiDepTraii §r\n§b-§a Dành Cho Máy Chủ Lands Of Anime§r\n\n§dPặc Pặc Pặc Pặc Pặc");
	}

	public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool {
		switch($command->getName()){
			case "buyec":
			if($player instanceof Player){
			    $this->openMyForm($player);
			} else {
				$player->sendMessage("You can use this command only in-game.");
					return true;
			}
			break;
		}
	    return true;
	}

	public function openMyForm(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
		$result = $data;
		if($result === null){
			return;
		    }
			switch($result){
				case 0:
			    $cmd = "§l";
				$this->getServer()->getCommandMap()->dispatch($sender, $cmd);
				break;
				case 1:
				$cmd = "§l§l";
				$this->getServer()->getCommandMap()->dispatch($sender, $cmd);
				break;
			}
		});
		$form->setTitle("§l§b♦ §cHệ Thống Enchant §b♦");
		#$form->setContent("§aChọn 1 Ô Để Vào");
		$form->addButton("§e• §cEnchant §e•");
		$form->addButton("§e• §cCustom Enchant §e•");
		$form->sendToPlayer($sender);
	}
}