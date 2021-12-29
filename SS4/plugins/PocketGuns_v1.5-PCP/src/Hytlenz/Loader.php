<?php

namespace Hytlenz;

//Consumables
use Hytlenz\Consume\Alcohol;
use Hytlenz\Consume\Weeds;
use Hytlenz\Consume\Cocaine;
use Hytlenz\Consume\Cigarette;
use Hytlenz\Consume\Vape;
//Weapons
use Hytlenz\Weapons\HandGrenade;
use Hytlenz\Weapons\CrackShot;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Loader extends PluginBase implements Listener {
	
	public function onEnable() {
		//Consumables (Dont say it in PDEA that we have good shits XD)
		/*$this->getServer()->getPluginManager()->registerEvents(new Alcohol($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Weeds($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Cocaine($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Cigarette($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Vape($this), $this);*/
		//Weapons (Use it Properly)
		$this->getServer()->getPluginManager()->registerEvents(new HandGrenade($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new CrackShot($this), $this);
		$this->getServer()->getLogger()->notice("PocketGuns was Enabled!");
		
	}
}	
