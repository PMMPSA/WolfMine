<?php

namespace HWings\DNP;

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

class DNP extends PluginBase implements Listener{
    
    public $data;
    private $config, $amount;
    
    public function onEnable(){
    if(!file_exists($this->getDataFolder() . "DNP/")){
    @mkdir($this->getDataFolder() . "DNP/");
    }
    $this->dnp = new Config($this->getDataFolder() . "DNP/" . "DNP.yml", Config::YAML);
    $this->saveDefaultConfig();
    $this->config = $this->getConfig();
    $this->config->save();
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }
    public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer()->getName();
    if(!($this->dnp->exists(strtolower($player)))){
    $this->dnp->set(strtolower($player), 100);
    $this->dnp->save();
    return true;
    }
    }
    public function getAllDNP(){
    $s = $this->dnp->getAll();
    return $s;
    }
    public function viewDNP($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vdnp = $this->dnp->get($player);
    return $vdnp;
    }
    public function addDNP($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){													
    $player = $player->getName();
    
    }
    }
    $player = strtolower($player);
    $adnp = $this->dnp->get($player) + $amount;
    if($this->viewDNP($player) == 100){
    //$player->sendMessage("§d•§b Điểm Nhân Phẩm của Cư Dân hiện đang ở mức tối đa nên sẽ không được cộng nữa");
    return true;
    }
    $player = strtolower($player);
    $this->dnp->set(strtolower($player), $adnp);
    $this->dnp->save();
    if($this->viewDNP($player) < 31 && $this->viewDNP($player) > 10){
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup ".$player." DNP30");
    return true;
	}
	if($this->viewDNP($player) > 30){
	$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup ".$player." Guest");
	return true;
	}
	if($this->viewDNP($player) <= 10){
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup ".$player." DNP10");
    return true;
	}
	}
    public function reduceDNP($player, $amount){
    $amount = round($amount, 2);
    if($amount <= 0 or !is_numeric($amount)){
    if($player instanceof Player){
    $player = $player->getName();
    }
    }
    $player = strtolower($player);
    $adnp = $this->dnp->get($player) - $amount;
    $this->dnp->set(strtolower($player), $adnp);
    $this->dnp->save();
    
     if($this->viewDNP($player) < 31 && $this->viewDNP($player) > 10){
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup ".$player." DNP30");
    return true;
	}
    if($this->viewDNP($player) <= 10){
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup ".$player." DNP10");
    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "jail ".$player." P1 5 Điểm_Nhân_Phẩm_Thấp");
    return true;
	}
	}
    public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool{
    switch($cmd->getName()){
	case "np-help":
	$max = 0;
	$max += count($this->dnp->getAll());
	$max = ceil(($max / 5));
	$page = array_shift($args);
	$page = max(1, $page);
	$page = min($max, $page);
	$page = (int)$page;
	if(!($s->isOp())){
    $s->sendMessage("§d•§b=== §eHỆ THỐNG ĐIỂM NHÂN PHẨM §b===§d•\n§d•§b /np§e: Để xem điểm Nhân Phẩm mà Cư Dân đang sở hữu.\n§d•§b /np-top <".$page."-".$max.">§e: Để xem danh sách Cư Dân Gương Mẫu mỗi tháng.\n§d•§b /np-mua <số lượng>§e Để mua thêm Điểm Nhân Phẩm bằng Vàng\n§d•§b=== §eHỆ THỐNG ĐIỂM NHÂN PHẨM §b===§d•\n\n§d•§b===§e LƯU Ý ĐIỂM NHÂN PHẨM §b===§d•\n§d•§e Nếu điểm Nhân Phẩm bằng 100 điểm thì sẽ không thể được cộng thêm điểm.\n§d•§e Nếu điểm Nhân Phẩm bằng 0 thì tuần đó Cư Dân sẽ được di chuyển đến phòng giam mỗi khi vào máy chủ.\n§d•§e Điểm Nhân Phẩm ở mức 30-10 sẽ có màu Chat nhạt hơn các Cư Dân khác.\n§d•§e Cư Dân đạt TOP 1 Gương Mẫu mỗi tuần sẽ được lựa chọn Cư Dân bất kỳ trở về 100 điểm Nhân Phẩm.\n§d•§e Nếu có hành vi trao đổi các vật phẩm có chữ §c[CẤM]§e sẽ bị trừ điểm Nhân Phẩm.\n§d•§b===§e LƯU Ý ĐIỂM NHÂN PHẨM §b===§d•");
    }else{
    $s->sendMessage("§d•§b=== §eHỆ THỐNG ĐIỂM NHÂN PHẨM §b===§d•\n§d•§b /np§e: Để xem điểm Nhân Phẩm mà Cư Dân đang sở hữu.\n§d•§b /np-default <Cư Dân>§e: Để chỉnh điểm Nhân Phẩm của Cư Dân đó về 100 điểm.\n§d•§b /np-top <".$page."-".$max.">§e: Để xem danh sách Cư Dân Gương Mẫu mỗi tháng.\n§d•§b=== §eHỆ THỐNG ĐIỂM NHÂN PHẨM §b===§d•\n\n§d•§b===§e LƯU Ý ĐIỂM NHÂN PHẨM §b===§d•\n§d•§e Nếu điểm Nhân Phẩm bằng 100 điểm thì sẽ không thể được cộng thêm điểm.\n§d•§e Nếu điểm Nhân Phẩm bằng 0 thì tuần đó Cư Dân sẽ được di chuyển đến phòng giam mỗi khi vào máy chủ.\n§d•§e Điểm Nhân Phẩm ở mức 30-10 sẽ có màu Chat nhạt hơn các Cư Dân khác.\n§d•§e Cư Dân đạt TOP 1 Gương Mẫu mỗi tuần sẽ được lựa chọn Cư Dân bất kỳ trở về 100 điểm Nhân Phẩm.\n§d•§e Nếu có hành vi trao đổi các vật phẩm có chữ §c[CẤM]§e sẽ bị trừ điểm Nhân Phẩm.\n§d•§b===§e LƯU Ý ĐIỂM NHÂN PHẨM §b===§d•");
    }
    break;
    return true;
     case "np":
     $s->sendMessage("§d•§b Điểm Nhân Phẩm hiện tại của Cư Dân:§e ".$this->viewDNP($s->getName()));
     break;
     return true;
      case "np-default":
       if(!$s->isOp()){
       $s->sendMessage("§d•§e Cư Dân không có quyền sử dụng câu lệnh này!");
       return true;
       }
       if(empty($args[0])){
       $s->sendMessage("§d•§e Cư Dân đã quên Đối Tượng mất rồi !");
       return true;
       }
       if($this->viewDNP($args[0]) == 100){
       $s->sendMessage("§d•§e Điểm Nhân Phẩm của Cư Dân này hiện đang là 100");
       return true;
       }
       $conlai = 100-$this->viewDNP($args[0]);
       $this->addDNP($args[0], $conlai);
       $s->sendMessage("§d•§b Nhân Phẩm của §e".$args[0]."§b đã trở về 100");
       break;
       return true;
	   case "np-top":
                $max = 0;
				$max += count($this->dnp->getAll());
				$max = ceil(($max / 5));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$s->sendMessage("§d•§b===§e CƯ DÂN GƯƠNG MẪU §b===§d•");
				$aa = $this->dnp->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
				$i1 = $i + 1;
				$a = (int)$a;
				$s->sendMessage("§d•§b Hạng ".$i1."§e: ".$b." với ".$a." điểm Nhân Phẩm\n");
				}
			$i++;
			}
		break;
		}
	return true;
	}
}