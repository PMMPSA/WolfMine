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
    $s->sendMessage("??d?????b===??e H??? TH???NG KHOE V???T PH???M ??b===??d???\n??d?????b /luotkhoe??e: ????? xem s??? l?????t khoe v???t ph???m m?? C?? D??n ??ang c??.\n??d?????b /luotkhoe-mua <s??? l?????ng>??e: ????? mua th??m s??? l?????t khoe v???t ph???m v???i gi?? 5 V??ng/L?????t.\n??d?????b /khoe??e: ????? khoe v???t ph???m C?? D??n ??ang c???m l??n h??? th???ng k??nh Chat.\n??d?????b===??e H??? TH???NG KHOE V???T PH???M ??b===??d???");
    break;
    return true;
     case "luotkhoe":
     $s->sendMessage("??d?????b S??? L?????t Khoe V???t Ph???m hi???n t???i C?? D??n ??ang s??? h???u:??e ".$this->viewLK($s->getName()));
     break;
     return true;
	   case "luotkhoe-mua":
	   if(empty($args[0])){
	   $s->sendMessage("??d?????e C?? D??n vui l??ng th??m s??? l?????t Khoe V???t Ph???m c???n mua");
	   return true;
	   }
	   if($args[0] > 100 || $args[0] <= 0 || !is_numeric($args[0])){
       $s->sendMessage("??d?????e S??? l?????ng L?????t Khoe V???t Ph???m m???i l?????t mua ph???i nh??? h??n 100 L?????t, l???n h??n 0 L?????t v?? ph???i l?? s???!");
       return true;
       }
       if($this->vang->viewVANG($s->getName()) < $args[0]*5){
       $s->sendMessage("??d?????e S??? V??ng c???a C?? D??n kh??ng ????? ????? mua ".$args[0]." L?????t Khoe V???t Ph???m.");
       return true;
       }
       $this->vang->reduceVANG($s->getName(), $args[0]*5);
       //$a = (int)$args[0];
       $this->addLK($s->getName(), $args[0]);
       $s->sendMessage("??d?????e C?? D??n ???? mua th??nh c??ng ".$args[0]." L?????t Khoe V???t Ph???m");
       break;
       return true;
       case "khoe":
       if($this->viewLK($s->getName()) == 0){
       $s->sendMessage("??d?????e C?? D??n kh??ng c?? ????? s??? L?????t Khoe V???t Ph???m");
       return true;
       }
       if($s->getPlayer()->getInventory()->getItemInHand()->getId() == 0){
       $s->sendMessage("??d?????e Tr??n tay C?? D??n kh??ng c?? g?? ????? khoe c???");
       return true;
       }
       $itemkhoe = $s->getPlayer()->getInventory()->getItemInHand()->getName();
       $this->getServer()->broadcastMessage("??d?????e C?? D??n ".$s->getName()." ???? khoe v???t ph???m ".$itemkhoe."??r??e l??n k??nh chat");
       $this->reduceLK($s->getName(), 1);
       break;
       return true;
       }
       return true;
       }
       }