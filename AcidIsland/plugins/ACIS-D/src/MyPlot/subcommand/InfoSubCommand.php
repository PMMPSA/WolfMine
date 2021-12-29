<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use AmGM\LVTM\Mine;
use pocketmine\utils\TextFormat;

class InfoSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.info");
    }

    public function getAliases() {
        return [];
    }

    public function execute(CommandSender $sender, $label, array $args) {
        if (!empty($args)) {
            return false;
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        $this->Level = $sender->getServer()->getPluginManager()->getPlugin("LevelToMine");
        $level = $this->Level->getCurrentLevel($plot->owner);
        $sender->sendMessage($this->translateString("info.about", [TextFormat::GREEN . $plot]));
        $sender->sendMessage($this->translateString("info.owner", [TextFormat::GREEN . $plot->owner]));
        $sender->sendMessage($this->translateString("lv", [TextFormat::GREEN . $level]));
        $helpers = implode(", ", $plot->helpers);
        $sender->sendMessage($this->translateString("info.helpers", [TextFormat::GREEN . $helpers]));
        return true;
    }
}