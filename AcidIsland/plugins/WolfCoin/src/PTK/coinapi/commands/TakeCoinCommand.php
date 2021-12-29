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
use pocketmine\Player;
use pocketmine\Server;

use PTK\coinapi\Coin;

class TakeCoinCommand extends CoinCommand{
	public function __construct(Coin $plugin, $cmd = "takecoin"){
		parent::__construct($plugin, $cmd);
		$this->setUsage("/$cmd <tên người chơi> <số lưọng coin>");
		$this->setPermission("coinapi.command.takecoin");
		$this->setDescription("Lấy lại coin từ ai đó");
	}
	
	public function exec(CommandSender $sender, array $params){
		$player = array_shift($params);
		$amount = array_shift($params);
		
		if(trim($player) === "" or trim($amount) === "" or !is_numeric($amount)){
			$sender->sendMessage("Sử dụng: /".$this->getUsage());
			return true;
		}
		
		if($amount <= 0){
			$sender->sendMessage($this->getPlugin()->getMessage("takecoin-invalid-number", $sender->getName()));
			return true;
		}
		
		/*$p = $this->getPlugin()->getServer()->getPlayer($player);
		if($p instanceof Player){
			$player = $p->getName();
		}else{
			$result = $this->getPlugin()->accountExists($player);
			if($result === false){
				$sender->sendMessage($this->getPlugin()->getMessage("player-never-connected", $sender->getName(), array($player, "%2", "%3", "%4")));
				return true;
			}
		}*/

		//  Player finder  //
		$server = Server::getInstance();
		$p = $server->getPlayer($player);
		if($p instanceof Player){
			$player = $p->getName();
		}
		// END //
		
		$result = $this->getPlugin()->reduceCoin($player, $amount, false, "TakeCoinCommand");
		$output = "";
		switch($result){
			case Coin::RET_SUCCESS:
			$output .= $this->getPlugin()->getMessage("takecoin-took-coin", $sender->getName(), array($player, $amount, "%3", "%4"));
			break;
			case Coin::RET_INVALID:
			$output .= $this->getPlugin()->getMessage("takecoin-player-lack-of-coin", $sender->getName(), array($player, $amount, $this->getPlugin()->myCoin($player), "%4"));
			break;
			default:
			$output .= $this->getPlugin()->getMessage("takecoin-failed", $sender->getName());
		}
		$sender->sendMessage($output);
		return true;
	}
}