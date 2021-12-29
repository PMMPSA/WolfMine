<?php

/*
 * Coins, the massive coin plugin with many features for PocketMine-MP
 * Copyright (C) 2017-2018  PTK-KienPham <kien192837@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace PTK\coinapi\commands;

use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use PTK\coinapi\Coin;
use PTK\coinapi\event\coin\TraCoinEvent;

class TraCommand extends CoinCommand implements InGameCommand{
	public function __construct(Coin $plugin, $cmd = "pay"){
		parent::__construct($plugin, $cmd);
		$this->setUsage("/$cmd <tên người chơi> <số lưọng coin>");
		$this->setPermission("coinapi.command.pay");
		$this->setDescription("Trả coin cho ngưòi khác");
	}
	
	public function exec(CommandSender $sender, array $params){
		$player = array_shift($params);
		$amount = array_shift($params);
		
		if(trim($player) === "" or trim($amount) === "" or !is_numeric($amount)){
			$sender->sendMessage("Sử dụng: ".$this->getUsage());
			return true;
		}
		
		$server = Server::getInstance();
		
		$p = $server->getPlayer($player);
		if($p instanceof Player){
			$player = $p->getName();
		}
		
		if($player === $sender->getName()){
			$sender->sendMessage($this->getPlugin()->getMessage("pay-failed"));
			return true;
		}
		
		$result = $this->getPlugin()->reduceCoin($sender, $amount, false, "payment");
		if($result !== Coin::RET_SUCCESS){
			$sender->sendMessage($this->getPlugin()->getMessage("pay-failed", $sender));
			return true;
		}
		$result = $this->getPlugin()->addCoin($player, $amount, false, "payment");
		if($result !== Coin::RET_SUCCESS){
			$sender->sendMessage($this->getPlugin()->getMessage("request-cancelled", $sender));
			$this->getPlugin()->addCoin($sender, $amount, true, "payment-rollback");
			return true;
		}
		$this->getPlugin()->getServer()->getPluginManager()->callEvent(new TraCoinEvent($this->getPlugin(), $sender->getName(), $player, $amount));
		$sender->sendMessage($this->getPlugin()->getMessage("pay-success", $sender, [$amount, $player, "%3", "%4"]));
		if($p instanceof Player){
			$p->sendMessage($this->getPlugin()->getMessage("coin-paid", $p, [$sender->getName(), $amount, "%3", "%4"]));
		}
		return true;
	}
}