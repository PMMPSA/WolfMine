<?php

namespace MDNP;

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
$this->getLogger()->info("He thong mua diem nhan pham");
$this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
$this->dnp = $this->getServer()->getPluginManager()->getPlugin("DNP");
}
public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
switch($cmd->getName()){
case "np-mua":
if(empty($args[0])){
$sender->sendMessage("§d•§e Cư Dân vui lòng thêm số lượt Khoe Vật Phẩm cần mua");
return true;
}
if(!(is_numeric($args[0]))){
$sender->sendMessage("§d•§e Điểm Nhân Phẩm cần mua phải là số");
return true;
}
if($args[0]+$this->dnp->viewDNP($sender->getName()) > 100){
$sender->sendMessage("§d•§e Điểm Nhân Phẩm không thể lớn hơn 100 điểm");
return true;
}
if($this->vang->viewVANG($sender->getName()) < $args[0]*10){
$sender->sendMessage("§d•§e Cư Dân không có đủ vàng để mua ".$args[0]." Điểm Nhân Phẩm");
return true;
}
$this->vang->reduceVANG($sender->getName(), $args[0]*10);
$this->dnp->addDNP($sender->getName(), $args[0]);
$sender->sendMessage("§d•§e Cư Dân đã mua thành công ".$args[0]." điểm Nhân Phẩm");
       break;
       return true;
       }
       return true;
       }
       }