<?php
namespace AmGM;

use pocketmine\scheduler\PluginTask;
use AmGM\BarMan;
use pocketmine\utils\Config;
use pocketmine\Player;

class BarTask extends pluginTask
{
	
	public function __construct(BarMan $plugin)
	{
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}

	public function onRun($currentTicks)
	{
		$this->plugin->tip();
	}
	}