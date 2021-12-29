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

class SetLangCommand extends CoinCommand{
	public function __construct(Coin $plugin, $cmd = "setlang"){
		parent::__construct($plugin, $cmd);
		$this->setUsage("/$cmd <lang>");
		$this->setPermission("coinapi.command.setlang");
		$this->setDescription("Sets language resource");
	}
	
	public function exec(CommandSender $sender, array $params){
		$lang = implode(" ", $params);
		
		if(trim($lang) === ""){
			$sender->sendMessage("Usage : /".$this->getName()." <lang>");
			return true;
		}
		
		$result = $this->getPlugin()->setLang($lang, $sender->getName());
		if($result === false){
			$sender->sendMessage("Requested language does not exist");
		}else{
			$sender->sendMessage("Your language have been set to ".$result);
		}
		return true;
	}
}