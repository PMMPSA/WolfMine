<?php
declare(strict_types = 1);

/**
 * @name PointAPIAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\PointAPIAddon
 * @depend PointAPI
 */
namespace JackMD\ScoreHud\Addons
{
	use JackMD\ScoreHud\addon\AddonBase;
	use pocketmine\Player;
	use doramine\economyapi\EconomyAPI;

	class PointAPIAddon extends AddonBase{

		/** @var PointAPI */
		private $PointAPI;

		public function onEnable(): void{
			$this->PointAPI = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{point}" => $this->PointAPI->myMoney($player)
			];
		}
	}
}