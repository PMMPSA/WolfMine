<?php

namespace MV;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use PTK\coinapi\Coin;
use _64FF00\PurePerms\PurePerms;

class C extends PluginBase implements Listener{
public $coin;
public function onEnable(){
$this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->getLogger()->info("MuaVIpAPI is enabled!");
}
public function onCommand(CommandSender $s, Command $cmd, $label, array $args){
$this->coin = $this->getServer()->getPluginManager()->getPlugin("Coin");
$this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
if($cmd->getName() == "muavip"){
if(isset($args[0])){
switch(strtolower($args[0])){
case "help":
$s->sendMessage("§c<§a=====§b♦§6 ＷＯＬＦＭＩＮＥ §b♦§a=====§c>\n§c•§a /muavip §e<§a1-8§e>§c để mua vip\n§c<§a=====§b♦§6 ＷＯＬＦＭＩＮＥ §b♦§a=====§c>");
break;
return true;
case "1":
if($this->coin->reduceCoin($s->getName(), 5)){
$this->coin->reduceCoin($s->getName(), 5);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V1 10");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV1");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV1");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "2":
if($this->coin->reduceCoin($s->getName(), 10)){
$this->coin->reduceCoin($s->getName(), 10);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V2 30");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV2");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV2");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "3":
if($this->coin->reduceCoin($s->getName(), 25)){
$this->coin->reduceCoin($s->getName(), 25);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V3 80");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV3");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV3");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "4":
if($this->coin->reduceCoin($s->getName(), 50)){
$this->coin->reduceCoin($s->getName(), 50);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V4 140");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV4");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV4");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "5":
if($this->coin->reduceCoin($s->getName(), 100)){
$this->coin->reduceCoin($s->getName(), 100);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V5 250");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV5");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV5");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "6":
if($this->coin->reduceCoin($s->getName(), 250)){
$this->coin->reduceCoin($s->getName(), 250);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V6 600");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV6");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV6");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "7":
if($this->coin->reduceCoin($s->getName(), 500)){
$this->coin->reduceCoin($s->getName(), 500);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V7 1000");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV7");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV7");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
case "8":
if($this->coin->reduceCoin($s->getName(), 750)){
$this->coin->reduceCoin($s->getName(), 750);
$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".$s->getName()." V8 1800");
$s->sendMessage("§c•§a Bạn đã mua thành công gói §dV8");
$this->getServer()->broadcastMessage("§a>>§b ".$s->getName()."§c đã mua thành công gói §dV8");
}else{
$s->sendMessage("§c•§a Bạn không có đủ §cWolfMine§d để mua gói này");
}
break;
return true;
}
}
}
}
}
#§c<§a=====§b♦§6 W O L F M I N E §b♦§a=====§c>\n§c•§a /muavip §e<§a1-8§e>§c để mua vip\n§c<§a=====§b♦§6 W O L F M I N E §b♦§a=====§c>