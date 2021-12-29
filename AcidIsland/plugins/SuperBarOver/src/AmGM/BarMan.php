<?php

namespace AmGM;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use AmGM\BarTask;
use onebone\economyapi\EconomyAPI;
use PTK\coinapi\Coin;
use pocketmine\item\Item;
use _64FF00\PurePerms\PurePerms;
use MyPlot\EventListener;
use MyPlot\PlotLevelSettings;
use MyPlot\Plot;
use MyPlot\subcommand\InfoSubCommand;
use MyPlot\MyPlot;
use MyPlot\subcommand;
use FactionsPro\FactionMain;

class BarMan extends PluginBase{
public $eco;
public $coin;
public $level;
    public function onEnable(){
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new BarTask($this), 20);
		$this->getLogger()->info("§c♦§7 Plugin Superbar Code By AmGM");
		$this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
 		$this->PurePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
 		$this->plot = $this->getServer()->getPluginManager()->getPlugin("AacidIslandacutexxvide.com");
 		$this->FactionPro = $this->getServer()->getPluginManager()->getPlugin("FactionsPro");
 		$this->Coin = $this->getServer()->getPluginManager()->getPlugin("Coin");
 		$this->Level = $this->getServer()->getPluginManager()->getPlugin("LevelToMine");
    }
    public function tip(){
        foreach($this->getServer()->getOnlinePlayers() as $player){
            if($player->isOnline()){
	            $name = $player->getName();
	$money = $this->EconomyAPI->myMoney($player);
	$coin = $this->Coin->myCoin($player);
    $plot = $this->plot->getPlotByPosition($player->getPosition());
	$item = $player->getInventory()->getItemInHand();					
    $id = $item->getId();					
    $meta = $item->getDamage();
    $level = $this->Level->getCurrentLevel($name);
    #$rank = $this->PurePerms->UserDataManager->getGroup($player->getPlayer());
            $fac = $this->FactionPro->getPlayerFaction($name);
                $t = $this->getServer()->getTicksPerSecond(); 
 			   $l = $this->getServer()->getTickUsageAverage();
$tt = $this->getServer()->getMaxPlayers();
$ol = count($this->getServer()->getOnlinePlayers());
	             $message = "\n                                                                          §c|§b❃§a Thông tin: §6".$name." §c❃\n                                                                          §c|\n                                                                          §c|§6➲§b Số người Online: §a".$ol."/".$tt."\n                                                                          §c|\n                                                                          §c|§6➲§b Số tiền hiện tại: §a".$money."\n                                                                          §c|§6➲§b Số WC hiện tại: §a".$coin."\n                                                                          §c|§6➲§b Bang hội gia nhập: §a".$fac."\n                                                                          §c|§6➲§b Mã vật phẩm: §a".$id.":".$meta."\n                                                                          §c|§6➲§b Level hiện tại: §a".$level."\n                                                                          §c|§6➲§b Hòn đảo đang đứng:§a ".$plot."\n                                                                          §c|§6➲§b Tên chủ đảo: §a".$plot->owner."\n                                                                          §c|\n                                                                          §c|§b❃§a Thông tin: §6".$name." §c❃\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
          	   $player->sendTip($message);      
            }
        }
    } 
}