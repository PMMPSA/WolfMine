<?php

namespace UIS;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use CUI\Main;
use pocketmine\item\Item;

class hotro{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

	public function init(Player $sender){
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
		$eco = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->vang = $this->plugin->getServer()->getPluginManager()->getPlugin("VANG");
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
		$form = $api->createCustomForm(function (Player $sender, array $data){
			$result = $data[0];
			$oneuse = $this->plugin->useCodehotro($sender);
			 $inv = $sender->getInventory();
            if($result === "HOTROCUDAN"){
				if($oneuse === 0){
					$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
		   $form->addLabel("§d•§e Sử dụng Voucher thành công");
		   $form->addLabel("§d•§b Cư Dân nhận được§e:");
		   $form->addLabel("§d•§b 5§e Vàng");
$this->vang->addVANG($sender->getName(), 5);
		   $form->sendToPlayer($sender);
	   $this->plugin->setCodehotro($sender, $this->plugin->useCodehotro($sender) + 1);
	   return true;
		}else{
			$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
			 $form->addLabel("§d•§e Mỗi Cư Dân chỉ có thể nhận một lần duy nhất");
			 $form->sendToPlayer($sender);
	   return true;
		}
			}else{
				$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
			 $form->addLabel("§d•§e Voucher Cư Dân nhập không tồn tại trên hệ thống");
			 $form->sendToPlayer($sender);
	   return true;
		}
        });
        $form->setTitle("§l§d•§b===§e Voucher Hỗ Trợ §b===§d•");
        $form->addInput("§d•§b Nhập mã Voucher Hỗ Trợ của Cư Dân§e:");
		$form->addLabel("§d•§b Voucher Hỗ Trợ sẽ được công bố tại§e: https://bit.ly/HWF");
		$form->sendToPlayer($sender);
	}
}
