<?php

namespace HWings\VANG;

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

class VANG extends PluginBase implements Listener{
    
    public $data;
    private $config, $amount;
    
    public function onEnable(){
    if(!file_exists($this->getDataFolder() . "VANG/")){
    @mkdir($this->getDataFolder() . "VANG/");
    }
    $this->vang = new Config($this->getDataFolder() . "VANG/" . "VANG.yml", Config::YAML);
    $this->saveDefaultConfig();
    $this->config = $this->getConfig();
    $this->config->save();
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer()->getName();
    if(!($this->vang->exists(strtolower($player)))){
    $this->vang->set(strtolower($player), 0);
    $this->vang->save();
    return true;
    }
    }
    public function getAllVANG(){
    $s = $this->vang->getAll();
    return $s;
    }
    public function viewVANG($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vvang = $this->vang->get($player);
    return $vvang;
    }
    public function addVANG($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){													    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $avang = $this->vang->get($player) + $amount;
    $player = strtolower($player);
    $this->vang->set(strtolower($player), $avang);
    $this->vang->save();
    }
    public function reduceVANG($player, $amount){
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
    }
    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
	case "vang-help":
	$max = 0;
	$max += count($this->vang->getAll());
	$max = ceil(($max / 5));
	$page = array_shift($args);
	$page = max(1, $page);
	$page = min($max, $page);
	$page = (int)$page;
	if(!($s->isOp())){
    $s->sendMessage("§d•§b===§e HỆ THỐNG VÀNG §b===§d•\n§d•§b /vang§e: Để xem số Vàng mà Cư Dân đang sở hữu.\n§d•§b /muavang§e: Để mua thêm Vàng bằng Thẻ Cào.\n§d•§b /vang-top <".$page."-".$max.">§e: Để xem danh sách Cư Dân giàu nhất thành phố.\n§d•§b===§e HỆ THỐNG VÀNG §b===§d•");
    }else{
    $s->sendMessage("§d•§b===§e HỆ THỐNG VÀNG §b===§d•\n§d•§b /vang§e: Để xem số Vàng mà Cư Dân đang sở hữu.\n§d•§b /muavang§e: Để mua thêm Vàng bằng Thẻ Cào.\n§d•§b /vang-top <".$page."-".$max.">§e: Để xem danh sách Cư Dân giàu nhất thành phố.\n§d•§b===§e HỆ THỐNG VÀNG §b===§d•");
    }
    break;
    return true;
     case "vang":
     $s->sendMessage("§d•§b Số Vàng hiện tại Cư Dân đang sở hữu:§e ".$this->viewVANG($s->getName()));
     break;
     return true;
	   case "vang-top":
                $max = 0;
				$max += count($this->vang->getAll());
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$s->sendMessage("§d•§b===§e DOANH NHÂN GIÀU NHẤT THÀNH PHỐ §b===§d•");
				$aa = $this->vang->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$a = (int)$a;
				$s->sendMessage("§d•§b Hạng ".$i1."§e: ".$b." với ".$a." Vàng\n");
				}
			$i++;
			}
		break;
		}
	return true;
	}
}