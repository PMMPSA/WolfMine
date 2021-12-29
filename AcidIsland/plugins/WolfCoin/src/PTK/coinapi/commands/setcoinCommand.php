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

class SetCoinCommand extends CoinCommand{
	public function __construct(Coin $plugin, $cmd = "setcoin"){
		parent::__construct($plugin, $cmd);
		$this->setUsage("/$cmd <player> <coin>");
		$this->setDescription("Sets player's coin");
		$this->setPermission("coinapi.command.setcoin");
	}

	public function exec(CommandSender $sender, array $args){
		$player = array_shift($args);
		$coin = array_shift($args);

		if(trim($player) === "" or trim($coin) === ""){
			$sender->sendMessage("Sử dụng: /".$this->getName()." <tên ngwời chơi> <số lượng coin>");
			return true;
		}

		//  Player finder  //
		$server = Server::getInstance();
		$p = $server->getPlayer($player);
		if($p instanceof Player){
			$player = $p->getName();
		}
		// END //

		$result = $this->getPlugin()->setCoin($player, $coin, "SetCoinCommand");
		$output = "";
		switch($result){
			case -2:
			$output .= $this->getPlugin()->getMessage("setcoin-failed", $sender->getName());
			break;
			case -1:
			$output .= $this->getPlugin()->getMessage("player-never-connected", $sender->getName(), array($player, "%2", "%3", "%4"));
			break;
			case 0:
			$output .= $this->getPlugin()->getMessage("setcoin-invalid-number", $sender->getName(), array($coin, "%2", "%3", "%4"));
			break;
			case 1:
			$output .= $this->getPlugin()->getMessage("setcoin-setcoin", $sender->getName(), array($player, $coin, "%3", "%4"));
			if($p instanceof Player){
				$p->sendMessage($this->getPlugin()->getMessage("setcoin-set", $p->getName(), array($coin, $sender->getName(), "%3", "%4")));
			}
			break;
		}
		$sender->sendMessage($output);
		return true;
	}
}
