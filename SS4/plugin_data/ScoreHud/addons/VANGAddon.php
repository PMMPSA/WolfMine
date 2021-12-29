<?php
declare(strict_types = 1);
/**
 * @name VANGAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\VANGAddon
 * @depend VANG
 */
namespace JackMD\ScoreHud\Addons
{
	use JackMD\ScoreHud\addon\AddonBase;
	use HWings\VANG\VANG;
	use pocketmine\Player;

	class VANGAddon extends AddonBase{

		/** @var VANG */
		private $VANG;

		public function onEnable(): void{
			$this->VANG = $this->getServer()->getPluginManager()->getPlugin("VANG");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{vang}" => $this->VANG->viewVANG($player)
			];
		}
	}
}