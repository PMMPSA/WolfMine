<?php

namespace HWings\TichNap;

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

class TichNap extends PluginBase implements Listener{
    
    public $data;
    private $config, $amount;
    
    public function onEnable(){
    if(!file_exists($this->getDataFolder() . "TichNap/")){
    @mkdir($this->getDataFolder() . "TichNap/");
    }
    $this->tn = new Config($this->getDataFolder() . "TichNap/" . "TichNap.yml", Config::YAML);
    $this->saveDefaultConfig();
    $this->config = $this->getConfig();
    $this->config->save();
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer()->getName();
    if(!($this->tn->exists(strtolower($player)))){
    $this->tn->set(strtolower($player), 0);
    $this->tn->save();
    return true;
    }
    }
    public function getAllTichNap(){
    $s = $this->tn->getAll();
    return $s;
    }
    public function viewTichNap($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vtn = $this->tn->get($player);
    return $vtn;
    }
    public function addTichNap($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){													    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $atn = $this->tn->get($player) + $amount;
    $player = strtolower($player);
    $this->tn->set(strtolower($player), $atn);
    $this->tn->save();
    if($this->viewTichNap($player) >= 345000){
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." cape.all");
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." cape.cmd");
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." blue_creeper.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." enderman.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." energy.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." fire.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." red_creeper.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." turtle.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." kimochi.cape");
  $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." iron_golem.cape");
  return true;
    }
	if($this->viewTichNap($player) >= 899000){
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$player." firewings.command.angel");
	return true;
	}
    }
    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
	case "mocnap":
    $s->sendMessage("§d•§b===§e QUYỀN LỢI NẠP THẺ§b ===§d•\n§d•§e Tổng Nạp từ 345.000đ sẽ được mở khóa Hệ Thống Cape.\n§d•§e Tổng Nạp từ 100.000đ trở lên sẽ được nhận quyền Fly mỗi khi vào Thành Phố.\n§d•§e Tổng Nạp từ 899.000đ mỗi khi vào Thành Phố sẽ được nhận Hiệu Ứng Đặc Biệt và Chạy Nhanh cấp 2.\n§d•§b===§e QUYỀN LỢI NẠP THẺ§b ===§d•");

    break;
    return true;
     case "tongnap":
     $s->sendMessage("§d•§b Số tiền mà Cư Dân đã nạp:§e ".$this->viewTichNap($s->getName()));
     break;
     return true;
	   case "topnap":
                $max = 0;
				$max += count($this->tn->getAll());
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$s->sendMessage("§d•§b===§e TOP NẠP THÀNH PHỐ THÀNH PHỐ §b===§d•");
				$aa = $this->tn->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$a = (int)$a;
				$s->sendMessage("§d•§b Hạng ".$i1."§e: ".$b." với ".$a." VNĐ\n");
				}
			$i++;
			}
		break;
		}
	return true;
	}
}