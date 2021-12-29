<?php

namespace Health;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\PlayerJoinEvent;
use pocketmine\utils\TextFormat;

class bar extends PluginBase implements Listener{
public function onJoin(PlayerJoinEvent $ev){
$player = $ev->getPlayer();
$player->setNameTag("§a".str_repeat("|", $player()->getHealth())."§7".str_repeat("|", 20 - $player()->getHealth())."§r\n§l§f".$player()->getName()."§r");
$player->setNameTagVisible(false);
}
}