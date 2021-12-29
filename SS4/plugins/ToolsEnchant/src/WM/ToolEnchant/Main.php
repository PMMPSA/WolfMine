<?php

namespace WM\ToolEnchant;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use onebone\economyapi\EconomyAPI;
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\level\particle\{AngryVillagerParticle,BubbleParticle,CriticalParticle,DestroyBlockParticle,DustParticle,EnchantmentTableParticle,EnchantParticle,EntityFlameParticle,LargeExplodeParticle,FlameParticle,HappyVillagerParticle,HeartParticle,InkParticle,InstantEnchantParticle,ItemBreakParticle,LavaDripParticle,LavaParticle,MobSpellParticle,PortalParticle,RainSplashParticle,RedstoneParticle,SmokeParticle,SpellParticle,SplashParticle,SporeParticle,TerrainParticle,WaterDripParticle,WaterParticle,WhiteSmokeParticle};
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Inventory;
use pocketmine\level\Level;
use pocketmine\entity\human;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;
//use PTK\coinapi\Coin;

class Main extends PluginBase implements Listener{

public function onEnable(){
$this->getLogger()->info("Hệ Thống Mua Tools Enchant");
$this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
$this->tuoi = $this->getServer()->getPluginManager()->getPlugin("OnlineRank");
}
/*Rd: 275
Rs: 258
Rkc: 279
Cd: 274
Cs: 257
Ccc: 278
X: 277*/
/*$item->addEnchantment(new EnchantmentInstance($ench,  $name_level)); }*/
public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
switch($cmd->getName()){
case "tools":
if(empty($args[0])){
$sender->sendMessage("§l§c•§e===§b HỆ THỐNG VẬT PHẨM ENCHANT §e===§c•§r\n§c•§e Rìu Loại 1 <§brl1§e> (§bRìu Đá, Đào Nhanh: 2, Lâu Hư: 2§e)§c: 400.000VNĐ\n§c•§e Rìu Loại 2 <§brl2§e> (§bRìu Sắt, Đào Nhanh: 5, Lâu Hư: 3§e)§c: 800.000VNĐ\n§c•§e Rìu Loại 3 <§brl3§e> (§bRìu Kim Cương, Đào Nhanh: 10, Lâu Hư: 5)§c: 1.500.000VNĐ\n§c•§e Cúp Loại 1 <§bcl1§e> (§bCúp Đá, Đào Nhanh: 2, Lâu Hư: 2§e)§c: 400.000VNĐ\n§c•§e Cúp Loại 2 <§bcl2§e> (§bCúp Sắt, Đào Nhanh: 5, Lâu Hư: 3§e)§c: 800.000VNĐ\n§c•§e Cúp Loại 3 <§bcl3§e> (§bCúp Kim Cương, Đào Nhanh: 10, Lâu Hư: 5)§c: 1.500.000VNĐ\n§l§c•§e===§b HỆ THỐNG VẬT PHẨM ENCHANT §e===§c•§r>");
return true;
}
if(!($args[0] === "cl1" || $args[0] === "cl2" || $args[0] === "cl3" || $args[0] === "rl1" || $args[0] === "rl2" || $args[0] === "rl3" || $args[0] === "bar" || $args[0] === "bar1")){
$sender->sendMessage("§d•§e Có vẻ như mặt hàng bạn nhập chúng tôi không hỗ trợ, thử lại nhé!");
return true;
}
switch(strtolower($args[0])){
case "rl1":
if($this->eco->myMoney($sender->getName()) < 400000){
$sender->sendMessage("§d•§e Bạn không có đủ 400.000VNĐ để mua Rìu Loại 1");
return true;
}
$rl1 = Item::get(275,0,1);
$rl1->setCustomName("§l§o§c<§eRìu Loại 1§c>");
//$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(id), level));
$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 2));
$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
$sender->getInventory()->addItem($rl1);
$sender->sendMessage("§d•§e Chúc mừng! Bạn đã mua thành công Rìu Loại 1");
break;
return true;
case "rl2":
if($this->eco->myMoney($sender->getName()) < 800000){
$sender->sendMessage("§d•§e Bạn không có đủ 800.000VNĐ để mua Rìu Loại 2");
return true;
}
$rl2 = Item::get(258,0,1);
$rl2->setCustomName("§l§o§c<§eRìu Loại 2§c>");
//$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(id), level));
$rl2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 5));
$rl2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 3));
$sender->getInventory()->addItem($rl2);
$sender->sendMessage("§d•§e Chúc mừng! Bạn đã mua thành công Rìu Loại 2");
break;
return true;
case "rl3":
if($this->eco->myMoney($sender->getName()) < 1500000){
$sender->sendMessage("§d•§e Bạn không có đủ 1.500.000VNĐ để mua Rìu Loại 3");
return true;
}
$rl3 = Item::get(279,0,1);
$rl3->setCustomName("§l§o§c<§eRìu Loại 3§c>");
//$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(id), level));
$rl3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 10));
$rl3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5));
$sender->getInventory()->addItem($rl3);
$sender->sendMessage("§d•§e Chúc mừng! Bạn đã mua thành công Rìu Loại 3");
break;
return true;
case "cl1":
if($this->eco->myMoney($sender->getName()) < 400000){
$sender->sendMessage("§d•§e Bạn không có đủ 400.000VNĐ để mua Cúp Loại 1");
/*Rd: 275
Rs: 258
Rkc: 279
Cd: 274
Cs: 257
Ccc: 278
X: 277*/
return true;
}
$cl1 = Item::get(274,0,1);
$cl1->setCustomName("§l§o§c<§eCúp Loại 1§c>");
//$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(id), level));
$cl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 2));
$cl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 2));
$sender->getInventory()->addItem($cl1);
$sender->sendMessage("§d•§e Chúc mừng! Bạn đã mua thành công Cúp Loại 3");
break;
return true;
case "cl2":
if($this->eco->myMoney($sender->getName()) < 800000){
$sender->sendMessage("§d•§e Bạn không có đủ 800.000VNĐ để mua Cúp Loại 2");
return true;
}
$cl2 = Item::get(257,0,1);
$cl2->setCustomName("§l§o§c<§eCúp Loại 2§c>");
//$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(id), level));
$cl2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 5));
$cl2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 3));
$sender->getInventory()->addItem($cl2);
$sender->sendMessage("§d•§e Chúc mừng! Bạn đã mua thành công Cúp Loại 2");
break;
return true;
case "bar":
$this->getServer()->getCommandMap()->dispatch($sender, "spawn");
$sender->sendMessage("§d•§e Rời Bar thành công!");
break;
return true;
case "bar1":
if($this->tuoi->getTuoi($sender->getName()) < 5){
$sender->sendMessage("§d•§e Mày chưa đủ tuổi để vào Bar đâu thằng nhóc!");
return true;
}
$level1 = $sender->getLevel();
	$pos1 = new Position(-78,95,31,$level1);
    $sender->teleport($pos1);
	$sender->sendMessage("§d•§e Bạn hiện đang ở trong Bar");
break;
return true;
case "cl3":
if($this->eco->myMoney($sender->getName()) < 1500000){
$sender->sendMessage("§d•§e Bạn không có đủ 1.500.000VNĐ để mua Cúp Loại 3");
return true;
}
$cl3 = Item::get(278,0,1);
$cl3->setCustomName("§l§o§c<§eCúp Loại 3§c>");
//$rl1->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(id), level));
$cl3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), 10));
$cl3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 5));
$sender->getInventory()->addItem($cl3);
$sender->sendMessage("§d•§e Chúc mừng! Bạn đã mua thành công Cúp Loại 3");
break;
return true;
}
return true;
}
}
}