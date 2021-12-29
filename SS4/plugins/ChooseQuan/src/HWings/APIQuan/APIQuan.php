<?php

namespace HWings\APIQuan;

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
use pocketmine\event\player\{PlayerMoveEvent, PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent};
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

class APIQuan extends PluginBase implements Listener{
    
    public $data;
    private $config, $amount;
    
    public function onEnable(){
    //$this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
    if(!file_exists($this->getDataFolder() . "APIQuan/")){
    @mkdir($this->getDataFolder() . "APIQuan/");
    }
    $this->q = new Config($this->getDataFolder() . "APIQuan/" . "APIQuan.yml", Config::YAML);
    $this->saveDefaultConfig();
    $this->config = $this->getConfig();
    $this->config->save();
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer()->getName();
    if(!($this->q->exists(strtolower($player)))){
    $this->q->set(strtolower($player), 0);
    $this->q->save();
    return true;
    }
	/*if($this->viewQUAN($player) !== 0){
		$player->setNameTag("§d[§eCư Dân§d] §d[§e".$this->messQUAN($player)."§d] §r".$player);
		return true;
    }*/
	}
    public function getAllQ(){
    $s = $this->q->getAll();
    return $s;
    }
    public function viewQUAN($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vq = $this->q->get($player);
    return $vq;
    }
    public function messQUAN($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $a = $this->q->get($player);
    $mess = "Quận $a";
    return $mess;
    }
    public function addQUAN($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){													    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $aq = $amount;
    $player = strtolower($player);
    $this->q->set(strtolower($player), $aq);
    $this->q->save();
    }
	public function onMove(PlayerMoveEvent $evt){
		$s = $evt->getPlayer();
		if($this->viewQUAN($s->getName()) !== 0){
		$s->setNameTag("§d[§eCư Dân§d] §d[§e".$this->messQUAN($s->getName())."§d] §r".$s->getName());
	}
	}
	/*public function onJoin(PlayerJoinEvent $evt){
	$s = $evt->getPlayer();
   
	}	
	}*/
   //}
    /*public function reduceLK($player, $amount){
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
    }*/
   //}
    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
	case "quan-help":
    $s->sendMessage("§d•§b===§e HỆ THỐNG QUẬN§b ===§d•\n§d•§b /quan§e: Để xem Quận mà bạn đã đăng ký.\n§d•§b /quan-dangky§e: Để đăng ký trở thành Cư Dân của Quận mà bạn muốn (Quận 1 -> Quận 12).\n§d•§b /quan-check§e: Điều tra dân số của từng Quận (Chỉ Cảnh Sát được sử dụng).\n§d•§b===§e HỆ THỐNG QUẬN§b ===§d•");
    break;
    return true;
     case "quan":
	 if($this->viewQUAN($s->getName()) == 0){
		 $s->sendMessage("§d•§e Bạn hiện không phải là Cư Dân của bất cứ quận nào");
		 return true;
	 }
     $s->sendMessage("§d•§b Bạn là Cư Dân của §e".$this->messQUAN($s->getName()));
     break;
     return true;
	   case "quan-dangky":
	   if($this->viewQUAN($s->getName()) !== 0){
	   $s->sendMessage("§d•§e Sau khi kiểm tra, hệ thống nhận thấy bạn đã tiến hành đăng ký ở đợt trước, nên không thể đăng ký lại!");
	   return true;
	   }
	   if(empty($args[0])){
		 $s->sendMessage("§d•§e Vui lòng thêm số Quận để tiến hành đăng ký");
		 return true;
	   }
	   if($args[0] > 12 || $args[0] <= 0 || !is_numeric($args[0])){
       $s->sendMessage("§d•§e Hiện tại thành phố chỉ hỗ trợ tới Quận 12. Số bạn nhập không phải là số dương hoặc bằng 0, hoặc không phải là số");
       return true;
       }
       $this->addQUAN($s->getName(), $args[0]);
       //$nt = "
       $s->setNameTag("§d[§eCư Dân§d] §d[§e".$this->messQUAN($s->getName())."§d] §r".$s->getName());
       $s->sendMessage("§d•§b Chúc Mừng! Bạn đã trở thành Cư Dân của quận§e: ".$this->messQUAN($s->getName()));
       break;
       return true;
case "quan-check":
if(!$s->hasPermission("quan.check")){
$s->sendMessage("§d•§e Bạn không có quyền thực hiện lệnh này");
return true;
}
                $max = 0;
				$max += count($this->q->getAll());
				$max = ceil(($max / 15));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$s->sendMessage("§aDân số theo quận");
				$aa = $this->q->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 15 <= $i && $i <= ($page - 1) * 15 + 4){
				$i1 = $i + 1;
				$a = (int)$a;
				//$s->sendMessage("§d•§b Hạng ".$i1."§e: ".$b." với ".$a." Vàng\n");
				$s->sendMessage("§a".$i1.". ".$b.": Quận ".$a."\n");
				}
			$i++;
			}
		break;
		}
	return true;
	}
}