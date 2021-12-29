<?php

namespace Clan;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerDeathEvent, PlayerChatEvent};
use pocketmine\utils\Config;
use pocketmine\entity\Effect;
use PTK\coinapi\Coin;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\LevelException;

class War extends PluginBase implements Listener{
public $eco;
public function onEnable(){
$this->getLogger()->info("Plugin GiaoTranh is enabled");
}
public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
$this->eco = EconomyAPI::getInstance();
if($cmd->getName() == "join"){
if(isset($args[0])){
switch(strtolower($args[0])){
case "war":
$player = $sender->getServer()->getPlayer($sender->getName());    
$wname = "DownloadMap";
$level = $this->getServer()->getLevelByName($wname);
if($level === null) {
}else{
$player->teleport($level->getSafeSpawn());
$sender->sendMessage("§c•§b Bạn đã tham gia giao chiến bang hội");
}
break;
return true;
case "deny":
$sender->sendMessage("§a•§b Bạn đã hủy tham gia giao chiến");
break;
return true;
case "tb":
$this->getServer()->broadcastMessage("§c->§b Giao chiến bang hội đang diễn ra,§a hãy dùng /join war để tham gia");
break;
return true;
}
}
}
}
public function onPlayerDeath(PlayerDeathEvent $event) {
$ev = $event->getEntity()->getLastDamageCause();
if($ev instanceof EntityDamageByEntityEvent) {
$killer = $ev->getDamager();
if($killer instanceof Player){
$this->getServer()->broadcastMessage("§c•§a ".$killer->getName()."§b đã giết được một người, toàn server nhận 5.000 xu");
foreach($this->getServer()->getOnlinePlayers() as $p){
$this->eco = EconomyAPI::getInstance();
$this->eco->addMoney($p->getName(), 5000);
}
}
}
}
}