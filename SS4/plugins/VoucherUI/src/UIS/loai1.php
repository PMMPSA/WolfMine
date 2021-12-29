<?php

namespace UIS;

use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use CUI\Main;
use pocketmine\item\Item;

class loai1{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

	public function init(Player $sender){
		$eco = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	    $api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
		$form = $api->createCustomForm(function (Player $sender, array $data){
			$result = $data[0];
			$oneuse = $this->plugin->useCode($sender);
			 $inv = $sender->getInventory();
            if($result === "CUDANCHAODOI"){
				if($oneuse === 0){
					$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data) use ($inv){
                });
		   $form->addLabel("§d•§e Sử dụng Voucher thành công");
		   $form->addLabel("§d•§b Cư Dân nhận được§e:");
		   $form->addLabel("§d•§b 50.000§eVNĐ");
		   /*$form->addLabel("§b•§6 2 Iron");
		   $inv->addItem(Item::get(298,0,1));
		   $inv->addItem(Item::get(299,0,1));
		   $inv->addItem(Item::get(300,0,1));
		   $inv->addItem(Item::get(301,0,1));
		   $inv->addItem(Item::get(265,0,2));*/
		   $form->sendToPlayer($sender);
	   $this->plugin->setCode($sender, $this->plugin->useCode($sender) + 1);
	   $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($sender->getName(), 500000);
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
        $form->setTitle("§l§d•§b===§e Voucher Chào Đời §b===§d•");
        $form->addInput("§d•§b Nhập mã Voucher Chào Đời của Cư Dân§e:");
		$form->addLabel("§d•§b Voucher Chào Đời sẽ được công bố tại§e: https://bit.ly/HWF");
		$form->sendToPlayer($sender);
	}
}
