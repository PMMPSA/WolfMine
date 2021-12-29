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

use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;

use PTK\coinapi\Coin;

class XemCoinCommand extends CoinCommand{
	public function __construct(Coin $plugin, $cmd = "seecoin"){
		parent::__construct($plugin, $cmd);
		$this->setUsage("/$cmd <tên người chơi>");
		$this->setDescription("Xem số lưọng coin mà ai đó có");
		$this->setPermission("coinapi.command.seecoin");
	}
	
	public function exec(CommandSender $sender, array $args){
		$player = array_shift($args);
		if(trim($player) === ""){
			$sender->sendMessage("Sử dụng: /".$this->getName()." <tên người chơi>");
			return true;
		}
		
		//  Player finder  //
		$server = Server::getInstance();
		$p = $server->getPlayer($player);
		if($p instanceof Player){
			$player = $p->getName();
		}
		// END //
		$result = $this->getPlugin()->myCoin($player);
		if($result === false){
			$sender->sendMessage($this->getPlugin()->getMessage("player-never-connected", $sender->getName(), array($player, "%2", "%3", "%4")));
			return true;
		}else{
			$sender->sendMessage($this->getPlugin()->getMessage("seecoin-seecoin", $sender->getName(), array($player, $result, "%3", "%4")));
			return true;
		}
	}
}