<?php

namespace HWings\ThiBangLai;

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
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent, PlayerMoveEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\level\particle\{AngryVillagerParticle,BubbleParticle,CriticalParticle,DestroyBlockParticle,DustParticle,EnchantmentTableParticle,EnchantParticle,EntityFlameParticle,LargeExplodeParticle,FlameParticle,HappyVillagerParticle,HeartParticle,InkParticle,InstantEnchantParticle,ItemBreakParticle,LavaDripParticle,LavaParticle,MobSpellParticle,PortalParticle,RainSplashParticle,RedstoneParticle,SmokeParticle,SpellParticle,SplashParticle,SporeParticle,TerrainParticle,WaterDripParticle,WaterParticle,WhiteSmokeParticle};
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Inventory;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\entity\human;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;
//use PTK\coinapi\Coin;

class ThiBangLai extends PluginBase implements Listener{
    
    public $data;
    private $config, $amount;
    
    public function onEnable(){
    if(!file_exists($this->getDataFolder() . "ThiBangLai/")){
    @mkdir($this->getDataFolder() . "ThiBangLai/");
    }
    $this->tbl = new Config($this->getDataFolder() . "ThiBangLai/" . "ThiBangLai.yml", Config::YAML);
    $this->saveDefaultConfig();
    $this->config = $this->getConfig();
    $this->config->save();
    $this->car = $this->getServer()->getPluginManager()->getPlugin("car");
    $this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    $this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer()->getName();
    if(!($this->tbl->exists(strtolower($player)))){
    $this->tbl->set(strtolower($player), 1);
    $this->tbl->save();
    return true;
    }
    }
    public function tblXEM($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vtbl = $this->tbl->get($player);
    return $vtbl;
    }
    /*public function onMove(PlayerMoveEvent $event){
    $player = $event->getPlayer();
    $x = $player->getX();
    $y = $player->getY();
    $z = $player->getZ();
    $pos = $event->getPlayer()->getSide($event->getFace());
    if($x == 47 && $y == 101 && $z == 53){
    $coords??=??[51,??101,??35];
    $player->teleport(new??Vector3(...$coords));
    $this->car->spawnCar($pos, "Car");
    return true;
    }
    if($x == 47 && $y == 101 && $z == 52){
    $coords??=??[51,??101,??35];
    $player->teleport(new??Vector3(...$coords));
    $this->car->spawnCar($pos, "Car");
    return true;
    }
    if($x == 47 && $y == 101 && $z == 51){
    $coords??=??[51,??101,??35];
    $player->teleport(new??Vector3(...$coords));
    $this->car->spawnCar($pos, "Car");
    return true;
    }
    if($x == 47 && $y == 101 && $z == 50){
    $coords??=??[51,??101,??35];
    $player->teleport(new??Vector3(...$coords));
    $this->car->spawnCar($pos, "Car");
    return true;
    }
    if($x == 52 && $y == 101 && $z == 37){
    $coords??=??[47,??101,??50]; //47 101 50
    $player->teleport(new??Vector3(...$coords));
    //$this->car->spawnCar($pos, "Car");
    $this->tblDAU($player->getName();
    $player->sendMessage("??d?????e Ch??c m???ng, b???n ???? thi l???y b???ng l??i th??nh c??ng. H??y qua ti???m b??n c???nh ????? mua xe v???i gi?? 20 V??ng nh??");
    return true;
    }
    if($x == 51 && $y == 101 && $z == 37){
    $coords??=??[47,??101,??50]; //47 101 50
    $player->teleport(new??Vector3(...$coords));
    //$this->car->spawnCar($pos, "Car");
    $this->tblDAU($player->getName();
    $player->sendMessage("??d?????e Ch??c m???ng, b???n ???? thi l???y b???ng l??i th??nh c??ng. H??y qua ti???m b??n c???nh ????? mua xe v???i gi?? 20 V??ng nh??");
    return true;
    }
    }*/
    public function tblDau($player){
    //$amount = round($amount, 2);
    //if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){													    $player = $player->getName();
    }
    //}
    $player = strtolower($player);
    //$avang = $this->vang->get($player) + $amount;
    $player = strtolower($player);
    $this->tbl->set(strtolower($player), 2);
    $this->tbl->save();
    }
    /*public function reduceVANG($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){
    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $avang = $this->vang->get($player) - $amount;
    $this->vang->set(strtolower($player), $avang);
    $this->vang->save();
    }*/
    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
	case "banglai":
	if($this->tblXEM($s->getName()) == 1){
	$s->sendMessage("??d?????e Hi???n t???i b???n ch??a c?? b???ng l??i xe");
	return true;
	}
	if($this->tblXEM($s->getName()) == 2){
	$s->sendMessage("??d?????e Hi???n t???i b???n ???? c?? b???ng l??i xe");
    return true;
	}
    break;
    return true;
    case "????":
    if($this->eco->myMoney($s->getName()) < 7000000){
    $s->sendMessage("??d?????e B???n kh??ng c?? ????? 7.000.000VN?? ????? ti???n h??nh thi l???y b???ng l??i xe");
    return true;
    }
    if($this->tblXEM($s->getName()) == 2){
    $s->sendMessage("??d?????e B???n ???? c?? b???ng l??i xe n??n kh??ng th??? thi l???i");
    return true;
    }
    $level = $s->getLevel();
    //$pos??=??new??Position(51,101,35);
	$pos = new Position(51,101,35,$level);
    $s->teleport($pos);
    $this->eco->reduceMoney($s->getName(), 7000000);
    $this->car->spawnCar($pos, "Car");
    $s->addTitle("??aB???t ?????u", "??e H??y ho??n th??nh ??o???n ???????ng n??y ????? nh???n b???ng l??i");
    break;
    return true;
    case "????":
    $s->addTitle("??a???? Ho??n Th??nh", "??eB???n ???? ???????c c???p b???ng l??i xe");
    $this->tblDAU($s->getName());
    $level1 = $s->getLevel();
   // $pos1??=??new??Position(47,101,50);
	$pos1 = new Position(47,101,50,$level1);
    $s->teleport($pos1);
    break;
    return true;
     case "muaxe":
     if($this->tblXEM($s->getName()) == 1){
     $s->sendMessage("??d?????e B???n ch??a c?? b???ng l??i n??n kh??ng th??? mua xe");
     return true;
     }
     if(!(empty($args[0]))){
     $s->sendMessage("??d?????e M???i l?????t ch??? ???????c mua 1 xe");
     return true;
     }
     /*if(!is_numeric($args[0])){
     $s->sendMessage("??d?????e Vui l??ng nh???p l???i s??? l?????ng");
     return true;
     }*/
     if($this->vang->viewVANG($s->getName()) < 40){
     $s->sendMessage("??d?????e B???n kh??ng c?? ????? 50 V??ng ????? mua xe");
     return true;
     }
     $this->vang->reduceVANG($s->getName(), 40);
     $xe = Item::get(328,0,1);
     $xe->setCustomName("??bXe M??y WolfMine");
     $s->getInventory()->addItem($xe);
     $s->sendMessage("??d?????e B???n ???? mua th??nh c??ng Xe M??y WolfMine");
     break;
     return true;
     }
	 return true;
     }
     }