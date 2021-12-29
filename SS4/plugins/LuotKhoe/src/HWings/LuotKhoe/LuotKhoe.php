<?php

namespace HWings\LuotKhoe;

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

class LuotKhoe extends PluginBase implements Listener{
    
    public $data;
    private $config, $amount;
    
    public function onEnable(){
    $this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
    if(!file_exists($this->getDataFolder() . "LuotKhoe/")){
    @mkdir($this->getDataFolder() . "LuotKhoe/");
    }
    $this->lk = new Config($this->getDataFolder() . "LuotKhoe/" . "LuotKhoe.yml", Config::YAML);
    $this->saveDefaultConfig();
    $this->config = $this->getConfig();
    $this->config->save();
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer()->getName();
    if(!($this->lk->exists(strtolower($player)))){
    $this->lk->set(strtolower($player), 0);
    $this->lk->save();
    return true;
    }
    }
    public function getAllLK(){
    $s = $this->lk->getAll();
    return $s;
    }
    public function viewLK($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vlk = $this->lk->get($player);
    return $vlk;
    }
    public function addLK($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){													    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $alk = $this->lk->get($player) + $amount;
    $player = strtolower($player);
    $this->lk->set(strtolower($player), $alk);
    $this->lk->save();
    }
   //}
    public function reduceLK($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){
    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $alk = $this->lk->get($player) - $amount;
    $this->lk->set(strtolower($player), $alk);
    $this->lk->save();
    }
   //}
    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
	case "khoe-help":
    $s->sendMessage("§d•§b===§e HỆ THỐNG KHOE VẬT PHẨM §b===§d•\n§d•§b /luotkhoe§e: Để xem số lượt khoe vật phẩm mà Cư Dân đang có.\n§d•§b /luotkhoe-mua <số lượng>§e: Để mua thêm số lượt khoe vật phẩm với giá 5 Vàng/Lượt.\n§d•§b /khoe§e: Để khoe vật phẩm Cư Dân đang cầm lên hệ thống kênh Chat.\n§d•§b===§e HỆ THỐNG KHOE VẬT PHẨM §b===§d•");
    break;
    return true;
     case "luotkhoe":
     $s->sendMessage("§d•§b Số Lượt Khoe Vật Phẩm hiện tại Cư Dân đang sở hữu:§e ".$this->viewLK($s->getName()));
     break;
     return true;
	   case "luotkhoe-mua":
	   if(empty($args[0])){
	   $s->sendMessage("§d•§e Cư Dân vui lòng thêm số lượt Khoe Vật Phẩm cần mua");
	   return true;
	   }
	   if($args[0] > 100 || $args[0] <= 0 || !is_numeric($args[0])){
       $s->sendMessage("§d•§e Số lượng Lượt Khoe Vật Phẩm mỗi lượt mua phải nhỏ hơn 100 Lượt, lớn hơn 0 Lượt và phải là số!");
       return true;
       }
       if($this->vang->viewVANG($s->getName()) < $args[0]*5){
       $s->sendMessage("§d•§e Số Vàng của Cư Dân không đủ để mua ".$args[0]." Lượt Khoe Vật Phẩm.");
       return true;
       }
       $this->vang->reduceVANG($s->getName(), $args[0]*5);
       //$a = (int)$args[0];
       $this->addLK($s->getName(), $args[0]);
       $s->sendMessage("§d•§e Cư Dân đã mua thành công ".$args[0]." Lượt Khoe Vật Phẩm");
       break;
       return true;
       case "khoe":
       if($this->viewLK($s->getName()) == 0){
       $s->sendMessage("§d•§e Cư Dân không có đủ số Lượt Khoe Vật Phẩm");
       return true;
       }
       if($s->getPlayer()->getInventory()->getItemInHand()->getId() == 0){
       $s->sendMessage("§d•§e Trên tay Cư Dân không có gì để khoe cả");
       return true;
       }
       $itemkhoe = $s->getPlayer()->getInventory()->getItemInHand()->getName();
       $this->getServer()->broadcastMessage("§d•§e Cư Dân ".$s->getName()." đã khoe vật phẩm ".$itemkhoe."§r§e lên kênh chat");
       $this->reduceLK($s->getName(), 1);
       break;
       return true;
       }
       return true;
       }
       }