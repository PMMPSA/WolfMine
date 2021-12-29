<?php

namespace MS;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use onebone\economyapi\EconomyAPI;
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

class main extends PluginBase implements Listener{

public function onEnable(){
$this->getLogger()->info("Hệ Thống Mua Súng");
$this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
$this->tuoi = $this->getServer()->getPluginManager()->getPlugin("OnlineRank");
}
public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
switch($cmd->getName()){
case "hangcam":
if(empty($args[0])){
$sender->sendMessage("§d•§b===§e HỆ THỐNG HÀNG CẤM §b===§d•\n§d•§b Đạn - <§edan§b>, 3 Viên/500.000VNĐ\n§d•§b Súng 1 Nòng <§es1n§b>, 1 Khẩu/5.000.000VNĐ\n§d•§b Súng 3 Nòng <§es3n§b>, 1 Khẩu/50 Vàng\n§d•§b Gõ /hangcam <§emã tùy theo món hàng§b> để mua hàng cấm tương ứng!\n§l§c• Lưu ý: Chỉ từ 5 Tuổi trở lên mới có thể mua.§r\n§d•§b===§e HỆ THỐNG HÀNG CẤM §b===§d•");
return true;
}
if(!($args[0] === "dan" or $args[0] === "s1n" or $args[0] === "s3n")){
$sender->sendMessage("§d•§e Có vẻ như mặt hàng bạn nhập chúng tôi không hỗ trợ, thử lại nhé!");
return true;
}
switch(strtolower($args[0])){
case "dan":
if($this->tuoi->getTuoi($sender->getName()) < 5){
$sender->sendMessage("§d•§e Nhóc chưa đủ tuổi để sử dụng hàng cấm đâu!");
return true;
}
if($this->eco->myMoney($sender->getName()) < 500000){
$sender->sendMessage("§d•§e Bạn không có đủ 500.000VNĐ để mua 3 Viên Đạn");
return true;
}
$dan = Item::get(332,0,3);
$dan->setCustomName("§bĐạn");
$sender->sendMessage("§d•§e Bạn đã mua thành công x3 Đạn");
$sender->getInventory()->addItem($dan);
break;
return true;
case "s1n":
if($this->tuoi->getTuoi($sender->getName()) < 5){
$sender->sendMessage("§d•§e Nhóc chưa đủ tuổi để sử dụng hàng cấm đâu!");
return true;
}
if($this->eco->myMoney($sender->getName()) < 5000000){
$sender->sendMessage("§d•§e Bạn không có đủ 5.000.000VNĐ để mua Súng 1 Nòng");
return true;
}
$s1n = Item::get(346,0,1);
$s1n->setCustomName("§bSúng 1 Nòng");
$sender->getInventory()->addItem($s1n);
$sender->sendMessage("§d•§e Bạn đã mua thành công x1 Súng 1 Nòng");
break;
return true;
case "s3n":
if($this->tuoi->getTuoi($sender->getName()) < 5){
$sender->sendMessage("§d•§e Nhóc chưa đủ tuổi để sử dụng hàng cấm đâu!");
return true;
}
if($this->vang->viewVANG($sender->getName()) < 50){
$sender->sendMessage("§d•§e Bạn không có đủ 50 Vàng để mua Súng 3 Nòng");
return true;
}
$s3n = Item::get(398,0,1);
$s3n->setCustomName("§bSúng 3 Nòng");
$sender->getInventory()->addItem($s3n);
$sender->sendMessage("§d•§e Bạn đã mua thành công x1 Súng 3 Nòng");
break;
return true;
}
return true;
}
}
}