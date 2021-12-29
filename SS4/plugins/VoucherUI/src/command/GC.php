<?php

namespace command;

use pocketmine\Player;
use pocketmine\command\{PluginCommand, CommandSender};
use pocketmine\utils\TextFormat;

use CUI\Main;

class GC extends PluginCommand{

	public function __construct($name, Main $plugin){
		parent::__construct($name, $plugin);
		$this->setDescription("Mở hệ thống Voucher");
		$this->setPermission("vcui.command");
		$this->setAliases(["vc"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){

		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED . "You are not In-Game.");
			return true;
		}

		$this->MainUI($sender);
		return true;
	}

	public function MainUI(Player $sender){
		$api = $this->getPlugin()->getServer()->getPluginManager()->getPlugin("FormAPI-FS");
			if($api === null || $api->isDisabled()){
						
			}
			$form = $api->createSimpleForm(function (Player $sender, array $data){
			$result = $data[0];
			if ($result === null){
			}
			switch ($result){

				case 1:
				$this->getPlugin()->loai1->init($sender);
				break;

				case 2:
				//
				$this->getPlugin()->loai2->init($sender);
				break;
				
				case 3:
				//
				$this->getPlugin()->hotro->init($sender);
				break;
			}
		});
		$form->setTitle("§d§l•§b===§e Hệ Thống Voucher§b ===§d");
		
		$form->addButton("Thoát");
		$form->addButton( 
		"§l§cVoucher §aChào Đời", 1);
		$form->addButton("§l§cVoucher §bSự Kiện", 2);
		$form->addButton("§l§cVoucher §bHỗ Trợ", 3);
		$form->setContent("§d•§e Chọn Voucher Chào Đời và nhập mã Voucher được công bố trên Fanpage để nhận 50.000VNĐ\n§d•§e Chọn Voucher Sự Kiện và nhập mã Voucher được công bố trên Fanage để nhận 1.000.000VNĐ\n§d•§e Chọn Voucher Hỗ Trợ và nhập mã Voucher được công bố trên Fanpage để nhận 5 Vàng");
		$form->sendToPlayer($sender);
	}
}