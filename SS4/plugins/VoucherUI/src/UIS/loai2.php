<?php

namespace UIS;

use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use TCoin\TCoin;
use onebone\economyapi\EconomyAPI;
use CUI\Main;
use pocketmine\item\Item;

class loai2{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

	public function init(Player $sender){
		$eco = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
  /*$tcoin = $this->plugin->getServer()->getPluginManager()->getPlugin("TCoin");*/
  $money = $eco->myMoney($sender->getName());
  /*$coin = $tcoin->getMoney($sender->getName());*/
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
		$form = $api->createCustomForm(function (Player $sender, array $data){
			$result = $data[0];
			$oneuse = $this->plugin->useCodeev($sender);
            if($result != null){
				if($oneuse === 0){
				if($this->plugin->codeExists($this->plugin->giftcode, $result)) {
	     if(!($this->plugin->codeExists($this->plugin->used, $result))) {
		   $this->plugin->addCode($this->plugin->used, $result);
		   $api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
				 $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI")->addMoney($sender->getName(), 1000000);
		   $form->addLabel("§d•§e Sử dụng Voucher thành công");
		   $form->addLabel("§d•§b Cư Dân nhận được§e:");
		   $form->addLabel("§d•§b 1.000.000§eVNĐ");
		   $form->sendToPlayer($sender);
		   $this->plugin->setCodeev($sender, $this->plugin->useCodeev($sender) + 1);
		    
	   } else {
	   $api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
			 $form->addLabel("§d•§e Voucher này đã được sử dụng!");
			 $form->sendToPlayer($sender);
	   return true;
	   }
      } else {
	  $api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
			 $form->addLabel("§d•§e Voucher Cư Dân nhập không tồn tại trên hệ thống");
			 $form->sendToPlayer($sender);
	  return true;
	  }
	  }else{
			$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
						$form = $api->createCustomForm(function (Player $player, array $data){
                });
			 $form->addLabel("§d•§e Mỗi Cư Dân chỉ có thể nhận một lần duy nhất");
			 $form->sendToPlayer($sender);
	   return true;
		}
			   }
        });
        $form->setTitle("§l§d•§b===§e Voucher Sự Kiện §b===§d•");
        $form->addInput("§d•§b Nhập mã Voucher Sự Kiện của Cư Dân§e:");
		$form->addLabel("§d•§b Voucher Sự Kiện sẽ được công bố tại§e: https://bit.ly/HWF");
		$form->sendToPlayer($sender);
	}
}