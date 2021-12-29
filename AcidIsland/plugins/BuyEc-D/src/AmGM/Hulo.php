<?php

namespace AmGM;

use pocketmine\event\player\{PlayerInteractEvent, PlayerJoinEvent};
use pocketmine\utils\TextFormat as __;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\Player;
use pocketmine\level\sound\ExpPickupSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use PTK\coinapi\Coin;
use onebone\economyapi\EconomyAPI;

class Hulo extends PluginBase implements Listener{
public $eco;
public $coin;
public function onEnable(){
$this->getLogger()->info("\n\nPlugin BuyEc NoLimited Code By AmGM\n");
}
public function onCommand(CommandSender $s, Command $cmd, $label, array $args){
$this->eco = EconomyAPI::getInstance();
$this->coin = Coin::getInstance();
if($cmd->getName() == "muaec"){
switch(strtolower($args[1])){
case "1":
if($this->eco->reduceMoney($s->getName(), 1000)){
$item1 = $s->getInventory()->getItemInHand();
$item1->addEnchantment(Enchantment::getEnchantment($args[0])->setLevel(1));
$s->getInventory()->setItemInHand($item1);
$s->sendMessage("§aEnchant§c thành công");
}else{
$s->sendMessage("§c•§a Tài khoản của bạn không đủ §cCoin/Money§6 để enchant");
}
break;
return true;
case "2":
if($this->eco->reduceMoney($s->getName(), 2000)){
$item2 = $s->getInventory()->getItemInHand();
$item2->addEnchantment(Enchantment::getEnchantment($args[0])->setLevel(2));
$s->getInventory()->setItemInHand($item2);
$s->sendMessage("§aEnchant§c thành công");
}else{
$s->sendMessage("§c•§a Tài khoản của bạn không đủ §cCoin/Money§6 để enchant");
}
break;
return true;
case "3":
if($this->eco->reduceMoney($s->getName(), 3000)){
$item3 = $s->getInventory()->getItemInHand();
$item3->addEnchantment(Enchantment::getEnchantment($args[0])->setLevel(3));
$s->getInventory()->setItemInHand($item3);
$s->sendMessage("§aEnchant§c thành công");
}else{
$s->sendMessage("§c•§a Tài khoản của bạn không đủ §cCoin/Money§6 để enchant");
}
break;
return true;
case "4":
if($this->coin->reduceCoin($s->getName(), 20)){
$this->coin->reduceCoin($s->getName(), 20);
$item4 = $s->getInventory()->getItemInHand();
$item4->addEnchantment(Enchantment::getEnchantment($args[0])->setLevel(4));
$s->getInventory()->setItemInHand($item4);
$s->sendMessage("§aEnchant§c thành công");
}else{
$s->sendMessage("§c•§a Tài khoản của bạn không đủ §cCoin/Money§6 để enchant");
}
break;
return true;
case "5":
if($this->coin->reduceCoin($s->getName(), 25)){
$this->coin->reduceCoin($s->getName(), 25);
$item5 = $s->getInventory()->getItemInHand();
$item5->addEnchantment(Enchantment::getEnchantment($args[0])->setLevel(5));
$s->getInventory()->setItemInHand($item5);
          $s->sendMessage("§aEnchant§c thành công");
}else{
$s->sendMessage("§c•§a Tài khoản của bạn không đủ §cCoin/Money§6 để enchant");
}
     break;
     return true;
case "concac":
$item9 = $s->getInventory()->getItemInHand();
$item9->addEnchantment(Enchantment::getEnchantment($args[0])->setLevel($args[2]));
$s->getInventory()->setItemInHand($item9);
          $s->sendMessage("§aEnchant§c thành công");
break;
return true;
case "help":
 $s->sendMessage("§a-§7|§a=/_§7][§cB U Y E N C H A N T§7][§a_\=§7|§a-\n§6➼§a Hệ thống enchant vật phẩm:\n§6➼§a /muaec <id> <1-5>\n§c❃§e Giá trị:\n§a❂§b Level 1: §d1.000Xu\n§a❂§b Level 2: §d2.000Xu\n§a❂§b Level 3: §d3.000Xu\n§a❂§b Level 4: §d40WC\n§a❂§b Level 5: §d50WC");
}
}
}
}
#muaec 0 1 2 3