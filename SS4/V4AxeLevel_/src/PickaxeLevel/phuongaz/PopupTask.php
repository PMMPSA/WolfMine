<?php

namespace PickaxeLevel\phuongaz;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\Player;
use PickaxeLevel\phuongaz\Main;

Class PopupTask extends Task{


    public function __construct(Main $plugin, Player $player){

        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function onRun($currentTick){
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $level = $this->plugin->getLevel($player);
            $exp = $this->plugin->getExp($player);
            $next = $this->plugin->getNextExp($player);
            $pickaxename = $this->plugin->getNamePickaxe($player);
            $i = $player->getInventory()->getItemInHand();
            $hand = $i->getCustomName();
            $it = explode(" ", $hand);
            $damage = $i->getDamage();
            if ($it[0] == "§l§c|§a") {
                if ($damage > 30) {
                    $i->setDamage(0);
                    $player->getInventory()->setItemInHand($i);
                    $player->sendMessage("§l§6⚒§e Cúp đã được sửa chữa miễn phí ");
                }
			if($this->plugin->getLevel($player) == 10){
					if(!$i->getId() == 278){
						$item = Item::get(278,0,1)->setCustomName("$pickaxename");
						$player->getInventory()->setItemInHand($item);
						$player->sendMessage("§l§c⚒§6 Cúp đã được rèn thành cúp §bkim cương");
					}
			}
                if($this->plugin->getLevel($player) == 500) {
       $player->sendPopup("  §l§e⎳§d CÚP: §cＧＯＤ §aＬＯＵＳ§c §e𒁂\n" . "§c⊱§b Kinh Nghiệm:§a " . $exp ."§l§3 /§a ".$next. "§c |§b Cấp Cúp: §a" . $level);

                }elseif($this->plugin->getLevel($player) == 300){
                    $player->sendPopup("  §l§e⎳§d CÚP: §eＫＩＮＧ §aＬＯＵＳde§c §e𒁂\n" . "§c⊱§b Kinh Nghiệm:§a " . $exp ."§l§3 /§a ".$next. "§c |§b Cấp Cúp: §a" . $level);

                }else{
                    $player->sendPopup("  §l§e⎳§d CÚP: §b ＭＩＮＥ §aＬＯＵＳ§c §e𒁂\n" . "§c⊱§b Kinh Nghiệm:§a " . $exp ."§l§3 /§a ".$next. "§c |§b Cấp Cúp: §a" . $level);

                }
                         } else {
                $this->plugin->getScheduler()->cancelTask($this->getTaskId());
            }
        }
	}
}

    