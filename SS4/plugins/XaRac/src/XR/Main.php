<?php

namespace XR;

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
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent, PlayerDropItemEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\level\particle\{AngryVillagerParticle,BubbleParticle,CriticalParticle,DestroyBlockParticle,DustParticle,EnchantmentTableParticle,EnchantParticle,EntityFlameParticle,LargeExplodeParticle,FlameParticle,HappyVillagerParticle,HeartParticle,InkParticle,InstantEnchantParticle,ItemBreakParticle,LavaDripParticle,LavaParticle,MobSpellParticle,PortalParticle,RainSplashParticle,RedstoneParticle,SmokeParticle,SpellParticle,SplashParticle,SporeParticle,TerrainParticle,WaterDripParticle,WaterParticle,WhiteSmokeParticle};
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\Inventory;
use pocketmine\level\Level;
use pocketmine\entity\human;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;

class Main extends PluginBase implements Listener{
public function onEnable(){
$this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->plot = $this->getServer()->getPluginManager()->getPlugin("MyPlot");
$this->dnp = $this->getServer()->getPluginManager()->getPlugin("DNP");
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
}
public function onDrop(PlayerDropItemEvent $event){
$player = $event->getPlayer();
$plot = $this->plot->getPlotByPosition($player);
if($plot === null) {
$player->sendMessage("§d•§e Cư Dân không thể xả rác ở nơi công cộng được!\n§d•§e Cư Dân đã bị trừ 10 Điểm Nhân Phẩm\n§d•§e Hãy đến bỏ vào thùng rác để được nhận lại 10 Điểm Nhân Phẩm nhé Cư Dân!");
$this->dnp->reduceDNP($player->getName(), 10);
return true;
}
if($plot->owner !== $player->getName()){
$player->sendMessage("§d•§e Cư Dân không thể xả rác ở đất của Cư Dân khác được!\n§d•§e Cư Dân đã bị trừ 10 Điểm Nhân Phẩm\n§d•§e Hãy đến bỏ vào thùng rác để được nhận lại 10 Điểm Nhân Phẩm nhé Cư Dân!");
$this->dnp->reduceDNP($player->getName(), 10);
return true;
}
}
public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
if($sender instanceof Player){
switch(strtolower($command->getName())){
case "q":
//var_dump($sender->getInventory()->getItemInHand()->getId());
if($sender->getInventory()->getItemInHand()->getId() == 0){
$sender->sendMessage("");
return true;
}
if($this->dnp->viewDNP($sender->getName()) > 90){
$sender->sendMessage("§d•§e Cảm ơn Cư Dân, Cư Dân đã thực hiện một hành động vô cùng đẹp. Hy vọng ở ngoài đời thực Cư Dân vẫn luôn giữ ý thức như thế này!\n§d•§e Vì không thể cộng thêm Điểm Nhân Phẩm cho Cư Dân nên hệ thống xin tặng bù Cư Dân 800 VNĐ");
$this->eco->addMoney($sender->getName(), 800);
$sender->getInventory()->removeItem($sender->getInventory()->getItemInHand());
return true;
}
$sender->getInventory()->removeItem($sender->getInventory()->getItemInHand());
$sender->sendMessage("§d•§e Cảm ơn Cư Dân, Cư Dân đã thực hiện một hành động vô cùng đẹp. Hy vọng ở ngoài đời thực Cư Dân vẫn luôn giữ ý thức như thế này!\n§d•§e Cư Dân đã được tặng 10 Điểm Nhân Phẩm");
$this->dnp->addDNP($sender->getName(), 10);
break;
case "id":
$id = $sender->getInventory()->getItemInHand()->getId();
$damage = $sender->getInventory()->getItemInHand()->getDamage();
$sender->sendMessage("Id: ".$id."\nMeta: ".$damage);
break;
case "thoai":
$message = trim(implode(" ", $args));
$sender->sendMessage("".$message."");
break;
case "tdnp":
$this->dnp->reduceDNP($args[0], $args[1]);
$sender->sendMessage("Tru thanh cong");
}
}
return true;
}
}