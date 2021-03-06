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
		$player->setNameTag("??d[??eC?? D??n??d] ??d[??e".$this->messQUAN($player)."??d] ??r".$player);
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
    $mess = "Qu???n $a";
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
		$s->setNameTag("??d[??eC?? D??n??d] ??d[??e".$this->messQUAN($s->getName())."??d] ??r".$s->getName());
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
    $s->sendMessage("??d?????b===??e H??? TH???NG QU???N??b ===??d???\n??d?????b /quan??e: ????? xem Qu???n m?? b???n ???? ????ng k??.\n??d?????b /quan-dangky??e: ????? ????ng k?? tr??? th??nh C?? D??n c???a Qu???n m?? b???n mu???n (Qu???n 1 -> Qu???n 12).\n??d?????b /quan-check??e: ??i???u tra d??n s??? c???a t???ng Qu???n (Ch??? C???nh S??t ???????c s??? d???ng).\n??d?????b===??e H??? TH???NG QU???N??b ===??d???");
    break;
    return true;
     case "quan":
	 if($this->viewQUAN($s->getName()) == 0){
		 $s->sendMessage("??d?????e B???n hi???n kh??ng ph???i l?? C?? D??n c???a b???t c??? qu???n n??o");
		 return true;
	 }
     $s->sendMessage("??d?????b B???n l?? C?? D??n c???a ??e".$this->messQUAN($s->getName()));
     break;
     return true;
	   case "quan-dangky":
	   if($this->viewQUAN($s->getName()) !== 0){
	   $s->sendMessage("??d?????e Sau khi ki???m tra, h??? th???ng nh???n th???y b???n ???? ti???n h??nh ????ng k?? ??? ?????t tr?????c, n??n kh??ng th??? ????ng k?? l???i!");
	   return true;
	   }
	   if(empty($args[0])){
		 $s->sendMessage("??d?????e Vui l??ng th??m s??? Qu???n ????? ti???n h??nh ????ng k??");
		 return true;
	   }
	   if($args[0] > 12 || $args[0] <= 0 || !is_numeric($args[0])){
       $s->sendMessage("??d?????e Hi???n t???i th??nh ph??? ch??? h??? tr??? t???i Qu???n 12. S??? b???n nh???p kh??ng ph???i l?? s??? d????ng ho???c b???ng 0, ho???c kh??ng ph???i l?? s???");
       return true;
       }
       $this->addQUAN($s->getName(), $args[0]);
       //$nt = "
       $s->setNameTag("??d[??eC?? D??n??d] ??d[??e".$this->messQUAN($s->getName())."??d] ??r".$s->getName());
       $s->sendMessage("??d?????b Ch??c M???ng! B???n ???? tr??? th??nh C?? D??n c???a qu???n??e: ".$this->messQUAN($s->getName()));
       break;
       return true;
case "quan-check":
if(!$s->hasPermission("quan.check")){
$s->sendMessage("??d?????e B???n kh??ng c?? quy???n th???c hi???n l???nh n??y");
return true;
}
                $max = 0;
				$max += count($this->q->getAll());
				$max = ceil(($max / 15));
				$page = array_shift($args);
				$page = max(1, $page);
				$page = min($max, $page);
				$page = (int)$page;
				$s->sendMessage("??aD??n s??? theo qu???n");
				$aa = $this->q->getAll();
				arsort($aa);
				$i = 0;
				foreach($aa as $b=>$a){
				if(($page - 1) * 15 <= $i && $i <= ($page - 1) * 15 + 4){
				$i1 = $i + 1;
				$a = (int)$a;
				//$s->sendMessage("??d?????b H???ng ".$i1."??e: ".$b." v???i ".$a." V??ng\n");
				$s->sendMessage("??a".$i1.". ".$b.": Qu???n ".$a."\n");
				}
			$i++;
			}
		break;
		}
	return true;
	}
}