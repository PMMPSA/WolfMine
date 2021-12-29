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

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use PTK\coinapi\Coin;
use pocketmine\Player;

abstract class CoinCommand extends PluginCommand implements PluginIdentifiableCommand{
	public function __construct(Coin $plugin, $name){
		parent::__construct($name, $plugin);
	}

	public function execute(CommandSender $sender, $label, array $params){
		if(!$this->getPlugin()->isEnabled() or !$this->testPermission($sender)){
			return false;
		}

		if($this instanceof InGameCommand and !$sender instanceof Player){
			$sender->sendMessage(InGameCommand::ERROR_MESSAGE);
			return true;
		}

		return $this->exec($sender, $params);
	}

	/**
	 * @param CommandSender $sender
	 * @param array $params
	 * @return bool
	 */
	public abstract function exec(CommandSender $sender, array $params);
}