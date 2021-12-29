<?php
declare(strict_types = 1);
/**
 * @name DNPAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\DNPAddon
 * @depend DNP
 */
namespace JackMD\ScoreHud\Addons
{
	use JackMD\ScoreHud\addon\AddonBase;
	use HWings\DNP\DNP;
	use pocketmine\Player;

	class DNPAddon extends AddonBase{

		/** @var DNP */
		private $DNP;

		public function onEnable(): void{
			$this->DNP = $this->getServer()->getPluginManager()->getPlugin("DNP");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{dnp}" => $this->DNP->viewDNP($player)
			];
		}
	}
}