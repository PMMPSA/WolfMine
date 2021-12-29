<?php
declare(strict_types = 1);

/**
 * @name ClanAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\ClanAddon
 * @depend BedrockClans
 */
namespace JackMD\ScoreHud\Addons
{

	use JackMD\ScoreHud\addon\AddonBase;
	use Wertzui123\BedrockClans\Main;
	use pocketmine\Player;

	class ClanAddon extends AddonBase{

		/** @var ClanMain */
		private $clan;

		public function onEnable(): void{
			$this->clan = $this->getServer()->getPluginManager()->getPlugin("BedrockClans");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{clan}"       => $this->getClan($player),
			];
		}

		/**
		 * @param Player $player
		 * @return string
		 */
		public function getClan(Player $player): string{
			$clan = $this->clan;
			$clanName = $clan->getPlayerss($player);
			if(!($clan->isInClan($player))){
				return "Chưa Có Clan";
			}
			return $clanName;
		}
	}
}