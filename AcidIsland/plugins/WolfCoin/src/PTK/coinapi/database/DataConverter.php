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

namespace PTK\coinapi\database;

use pocketmine\utils\Config;

class DataConverter{
	private $coinData, $version, $coinFile;
	
	const VERSION_1 = 0x01;
	const VERSION_2 = 0x02;
	
	public function __construct($coinFile){
		$this->parseData($coinFile);
	}
	
	private function parseData($coinFile){
		$coinCfg = new Config($coinFile, Config::YAML);
		$this->coinFile = $coinCfg;
		
		if($coinCfg->exists("version")){
			$this->version = $coinCfg->get("version");
		}else{
			$this->version = self::VERSION_1;
		}
		
		if($this->version === self::VERSION_1){
			$this->coinData = $coinCfg->get("coin");
		}else{
			switch($this->version){
				case self::VERSION_2:
				$coin = [];
				foreach($coinCfg->get("coin") as $player => $m){
					$coin[strtolower($player)] = $m;
				}
				$this->coinData = $coin;
				break;
			}
		}
	}
	
	public function convertData($targetVersion){
		switch($this->version){
			case self::VERSION_1:
			switch($targetVersion){
				case self::VERSION_1:
				return true;
				
				case self::VERSION_2:
				$this->coinFile->set("version", self::VERSION_2);
				
				$coin = [];
				foreach($this->coinData as $player => $m){
					$coin[strtolower($player)] = $m;
				}
				
				$this->coinFile->set("coin", $coin);
				
				$this->coinFile->save();
				return true;
			}
			break;
			case self::VERSION_2:
			
			break;
		}
		return false;
	}
	
	public function getCoinData(){
		return $this->coinData;
	}
	
	public function getVersion(){
		return $this->version;
	}
}