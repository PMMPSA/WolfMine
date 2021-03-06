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
case "??l??c[C???M]??o??a C???n Sa":
$player->sendMessage("??d?????e C?? D??n ???? s??? d???ng th??nh c??ng v???t ph???m C???n Sa");
$player->addEffect($haste);
$player->addEffect($speed);
$player->addEffect($jump);
$player->addEffect($nausea);
$player->addEffect($STRENGTH);
$player->getInventory()->removeItem($player->getInventory()->getItemInHand());
break;
case "??l??c[C???M]??o??f Ma T??y":
$player->sendMessage("??d?????e C?? D??n ???? s??? d???ng th??nh c??ng v???t ph???m Ma T??y");
$player->addEffect($haste);
$player->addEffect($speed);
$player->addEffect($jump);
$player->addEffect($nausea);
$player->addEffect($STRENGTH);
$player->getInventory()->removeItem($player->getInventory()->getItemInHand());
break;
case "??l??c[C???M]??o??7 R?????u H???ng N???ng":
$player->sendMessage("??d?????e C?? D??n ???? s??? d???ng th??nh c??ng v???t ph???m R?????u H???ng N???ng");
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
case "??l??c[C???M]??o??a C???n Sa":
$player->sendMessage("??d?????e C?? D??n kh??ng ???????c ph??p qu??ng v???t ph???m C???n Sa ra ngo??i, ??i???m Nh??n Ph???m c???a C?? D??n ???? b??? tr??? 30 ??i???m");
$this->dnp->reduceDNP($player->getName(), 30);
break;
case "??l??c[C???M]??o??f Ma T??y":
$player->sendMessage("??d?????e C?? D??n kh??ng ???????c ph??p qu??ng v???t ph???m Ma T??y ra ngo??i, ??i???m Nh??n Ph???m c???a C?? D??n ???? b??? tr??? 30 ??i???m");
$this->dnp->reduceDNP($player->getName(), 30);
break;
case "??l??c[C???M]??o??7 R?????u H???ng N???ng":
$player->sendMessage("??d?????e C?? D??n kh??ng ???????c ph??p qu??ng v???t ph???m R?????u H???ng N???ng ra ngo??i, ??i???m Nh??n Ph???m c???a C?? D??n ???? b??? tr??? 30 ??i???m");
$this->dnp->reduceDNP($player->getName(), 30);
break;
}
}
public function onJoin(PlayerJoinEvent $event){
$player = $event->getPlayer();
if($player->getName() == "LonMongManh69"){
$cansa = Item::get(31, 2, 64);
$cansa->setCustomName("??l??c[C???M]??o??a C???n Sa");
$matuy = Item::get(353, 0, 64);
$matuy->setCustomName("??l??c[C???M]??o??f Ma T??y");
$matuyda = Item::get(373, 0, 5);
$matuyda->setCustomName("??l??c[C???M]??o??7 R?????u H???ng N???ng");
$player->getInventory()->addItem($cansa);
$player->getInventory()->addItem($matuy);
$player->getInventory()->addItem($matuyda);
}
}
}