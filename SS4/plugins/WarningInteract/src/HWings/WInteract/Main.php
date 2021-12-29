<?php

namespace HWings\WInteract;

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
$this->dnp = $this->getServer()->getPluginManager()->getPlugin("DNP");
}
public function onInteract(PlayerInteractEvent $ev){
 $player = $ev->getPlayer();
 $item = $ev->getItem();
$haste = new EffectInstance(Effect::getEffect(Effect::HASTE), 1200, 2, true);
$speed = new EffectInstance(Effect::getEffect(Effect::SPEED), 1200, 1, true);
$nausea = new EffectInstance(Effect::getEffect(Effect::NAUSEA), 1200, 1, true);
$STRENGTH = new EffectInstance(Effect::getEffect(Effect::STRENGTH), 1200, 1, true);
$jump = new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 1200, 1, true);
switch($item->getCustomName()){
case "§l§c[CẤM]§o§a Cần Sa":
$player->sendMessage("§d•§e Cư Dân đã sử dụng thành công vật phẩm Cần Sa");
$player->addEffect($haste);
$player->addEffect($speed);
$player->addEffect($jump);
$player->addEffect($nausea);
$player->addEffect($STRENGTH);
$player->getInventory()->removeItem($player->getInventory()->getItemInHand());
break;
case "§l§c[CẤM]§o§f Ma Túy":
$player->sendMessage("§d•§e Cư Dân đã sử dụng thành công vật phẩm Ma Túy");
$player->addEffect($haste);
$player->addEffect($speed);
$player->addEffect($jump);
$player->addEffect($nausea);
$player->addEffect($STRENGTH);
$player->getInventory()->removeItem($player->getInventory()->getItemInHand());
break;
case "§l§c[CẤM]§o§7 Rượu Hạng Nặng":
$player->sendMessage("§d•§e Cư Dân đã sử dụng thành công vật phẩm Rượu Hạng Nặng");
$player->addEffect($haste);
$player->addEffect($speed);
$player->addEffect($jump);
$player->addEffect($nausea);
$player->addEffect($STRENGTH);
$player->getInventory()->removeItem($player->getInventory()->getItemInHand());
break;
}
}
public function onDrop(PlayerDropItemEvent $event){
$player = $event->getPlayer();
$item = $event->getItem();
switch($item->getCustomName()){
case "§l§c[CẤM]§o§a Cần Sa":
$player->sendMessage("§d•§e Cư Dân không được phép quăng vật phẩm Cần Sa ra ngoài, điểm Nhân Phẩm của Cư Dân đã bị trừ 30 điểm");
$this->dnp->reduceDNP($player->getName(), 30);
break;
case "§l§c[CẤM]§o§f Ma Túy":
$player->sendMessage("§d•§e Cư Dân không được phép quăng vật phẩm Ma Túy ra ngoài, điểm Nhân Phẩm của Cư Dân đã bị trừ 30 điểm");
$this->dnp->reduceDNP($player->getName(), 30);
break;
case "§l§c[CẤM]§o§7 Rượu Hạng Nặng":
$player->sendMessage("§d•§e Cư Dân không được phép quăng vật phẩm Rượu Hạng Nặng ra ngoài, điểm Nhân Phẩm của Cư Dân đã bị trừ 30 điểm");
$this->dnp->reduceDNP($player->getName(), 30);
break;
}
}
public function onJoin(PlayerJoinEvent $event){
$player = $event->getPlayer();
if($player->getName() == "LonMongManh69"){
$cansa = Item::get(31, 2, 64);
$cansa->setCustomName("§l§c[CẤM]§o§a Cần Sa");
$matuy = Item::get(353, 0, 64);
$matuy->setCustomName("§l§c[CẤM]§o§f Ma Túy");
$matuyda = Item::get(373, 0, 5);
$matuyda->setCustomName("§l§c[CẤM]§o§7 Rượu Hạng Nặng");
$player->getInventory()->addItem($cansa);
$player->getInventory()->addItem($matuy);
$player->getInventory()->addItem($matuyda);
}
}
}