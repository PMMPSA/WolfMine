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

namespace PTK\coinapi\event\coin;

use PTK\coinapi\Coin;
use PTK\coinapi\event\CoinEvent;

class CoinChangedEvent extends CoinEvent{
	private $username, $coin;
	public static $handlerList;

	public function __construct(Coin $plugin, $username, $coin, $issuer){
		parent::__construct($plugin, $issuer);
		$this->username = $username;
		$this->coin = $coin;
	}

	/**
	 * @return string
	 */
	public function getUsername(){
		return $this->username;
	}

	/**
	 * @return float
	 */
	public function getCoin(){
		return $this->coin;
	}
}