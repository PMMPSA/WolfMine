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

use PTK\coinapi\Coin;

class MyCoinCommand extends CoinCommand implements InGameCommand{
	public function __construct(Coin $plugin, $cmd = "mycoin"){
		parent::__construct($plugin, $cmd);
		$this->setUsage("/$cmd");
		$this->setDescription("Xem số lượng coin mình có");
		$this->setPermission("coinapi.command.mycoin");
	}
	
	public function exec(CommandSender $sender, array $args){
		$username = $sender->getName();
		$result = $this->getPlugin()->myCoin($username);
		$sender->sendMessage($this->getPlugin()->getMessage("mycoin-mycoin", $sender->getName(), array($result, "%2", "%3", "%4")));
		return true;
	}
}