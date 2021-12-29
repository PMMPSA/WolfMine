<?php

namespace MF;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\Inventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

Class JI extends PluginBase implements Listener{

public function onEnable(){
   $this->getServer()->getPluginManager()->registerEvents($this, $this);
}
public function JoinItems(PlayerJoinEvent $event){
   $sender = $event->getPlayer();
   $name = $sender->getName();
      if($sender->hasPlayedBefore() == false){
     $item1 = Item::get(347,0,1);
     $item1->setCustomName("§r§c❖§a Tiểu sử Server §c❖\n§a•§6 Tap vào vật phẩm để xem");
     $item1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
     $item2 = Item::get(345,0,1);
     $item2->setCustomName("§r§c Phương thức nạp thẻ \n§c•§b Tap vào vật phẩm để xem");
     $item2->addEnchantment(Enchantment::getEnchantment(0)->setLevel(10));
     $sender->getInventory()->addItem($item1);
     $sender->getInventory()->addItem($item2);
}
}
public function onDisable(){}
}