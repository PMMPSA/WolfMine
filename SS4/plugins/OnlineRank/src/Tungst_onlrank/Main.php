<?php

namespace Tungst_onlrank;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
class Main extends PluginBase implements Listener {

	public function onEnable(){
		$this->getLogger()->info("Online rank enable");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$a = new Task_OLR($this);	
        $this->getScheduler()->scheduleRepeatingTask($a,60);
	}
	public function taskRunner(){
	   foreach($this->getServer()->getOnlinePlayers() as $p){
		$name = $p->getName();
	    if(isset($this->getConfig()->getAll()["OnlineTimeInADay"][$name])){ 
			$this->getConfig()->setNested("OnlineTimeInADay.$name", $this->getConfig()->getAll()["OnlineTimeInADay"][$name] +3);
			$this->getConfig()->setNested("TotalOnlineTime.$name", $this->getConfig()->getAll()["TotalOnlineTime"][$name] +3);
	        $this->getConfig()->setAll($this->getConfig()->getAll());
            $this->getConfig()->save();
		}
		
	   }
	}
    public function onJoin(PlayerJoinEvent $e){
      $name = $e->getPlayer()->getName();
	  if(!isset($this->getConfig()->getAll()["FirstTimeJoin"][$name])){
		  $this->getConfig()->setNested("FirstTimeJoin.$name", time());
		  $this->getConfig()->setNested("TimeForChecking.$name", time());
		  $this->getConfig()->setNested("TotalOnlineTime.$name", 0);
		  $this->getConfig()->setNested("OnlineTimeInADay.$name", 0);
		  $this->getConfig()->setNested("NewOnlineDay.$name", time());		  
		  $this->getConfig()->setNested("Rank.$name", 1);
		
		  $e->getPlayer()->setDisplayName("§d[§b1 TUỔI§d]§e $name");
          $e->getPlayer()->setScale($this->getConfig()->getAll()["1"]);
		  $this->getConfig()->setAll($this->getConfig()->getAll());
          $this->getConfig()->save();
		  $this->getServer()->broadcastMessage("§d•§b Chúc mừng, Cư Dân Nhí§e $name §bđã chào đời tại thành phố của chúng ta.");
	  }else{
		  if(date('d/m/Y',$this->getConfig()->getAll()["NewOnlineDay"][$name]) != date('d/m/Y',time())){
			  $this->getConfig()->setNested("NewOnlineDay.$name", time());
			  $this->getConfig()->setNested("OnlineTimeInADay.$name", 0);			  		 
		  }

//Rank level:
	      if(time() - $this->getConfig()->getAll()["TimeForChecking"][$name] >= $this->getConfig()->getAll()["TimeForRankUp"]*24*60*60){
			  $this->getConfig()->setNested("Rank.$name", $this->getConfig()->getNested("Rank.$name") +1);
			  $this->getConfig()->setNested("TimeForChecking.$name", time());	   			  
		  }
		   $this->getConfig()->setAll($this->getConfig()->getAll());
           $this->getConfig()->save();
		   
		   $lv = $this->getConfig()->getNested("Rank.$name");
		   $size = 1;
           $e->getPlayer()->setDisplayName("§d[§b$lv TUỔI§d]§e $name");
		   if($lv <= 10){
			 $size = $this->getConfig()->getAll()["$lv"];
		   }else{
			  $size = $this->getConfig()->getAll()["10"]; 
		   }
           $e->getPlayer()->setScale($size);		   
	  }
	}
	/*public function viewDNP($player){
    if($player instanceof Player){
    $player = $player->getName();
    }
    $player = strtolower($player);
    $vdnp = $this->dnp->get($player);
    return $vdnp;
    }*/
	public function getTuoi($player){
	if($player instanceof Player){
	$player = $player->getName();
	}
	$gettuoi = $this->getConfig()->getNested("Rank.$player");
	return $gettuoi;
	}
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        if($sender instanceof Player){
		switch(strtolower($command->getName())){
            case "checkplay":
              if(!isset($args[0])){
				 $this->mainform($sender);
              }else{
				 if($this->getServer()->getPlayer($args[0]) != null){
					 $this->mainform2($this->getServer()->getPlayer($args[0]));
				 } 
			  }	 
			  break;
        }
		}else{	
		};
		return true;
	}
   public function mainform($player){
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){				
					case "0";	                                      				 
					break;			
                    default:
                    break;					
			}
			});		  
			$name = $player->getName();			
			$totalday = date('d/m/Y - H:i:s',$this->getConfig()->getAll()["FirstTimeJoin"][$name]);
			$FirstTimeJoin = round($this->getConfig()->getAll()["TotalOnlineTime"][$name] /60,2);
		    $onlinetimeinaday = round($this->getConfig()->getAll()["OnlineTimeInADay"][$name] /60,2);
			$rank = $this->getConfig()->getAll()["Rank"][$name];
			$time = $this->getConfig()->getAll()["TimeForChecking"][$name] + $this->getConfig()->getAll()["TimeForRankUp"]*24*60*60;
			$day = date('d/m/Y',$time);
			$rank2 = (int) $rank +1;
			if($rank <= 10){
			$size = $this->getConfig()->getAll()["$rank"];
			}else{
			$size = $this->getConfig()->getAll()["10"];
			}
			
			$form->setTitle("§cOnl§7ine Time Info");	
			$form->setContent(
		    "§7You have played in this server since §a$totalday.\n".
			"§7Your total online time: §a$FirstTimeJoin minutes\n\n".
			"§7You have played in this server for §a$onlinetimeinaday minutes today\n\n".
			"§7Your online level: $rank\n".
			"§7You will get level $rank2 on $day\n".
			"§7Your size: $size"
			
			);
			$form->addButton("Ok,Im good",0,"textures/ui/accessibility_glyph_color");
			$form->sendToPlayer($player);
			return $form;
   }
   public function mainform2($player){
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){				
					case "0";	                                      				 
					break;			
                    default:
                    break;					
			}
			});
			$name = $player->getName();
			$totalday = date('d/m/Y - H:i:s',$this->getConfig()->getAll()["FirstTimeJoin"][$name]);
			$FirstTimeJoin = round($this->getConfig()->getAll()["TotalOnlineTime"][$name] /60,2);
		    $onlinetimeinaday = round($this->getConfig()->getAll()["OnlineTimeInADay"][$name] /60,2);
			$rank = $this->getConfig()->getAll()["Rank"][$name];
			$form->setTitle("§cOnl§7ine Time Info");	
			$form->setContent(
			"§7Player §a$name's info:\n".
		    "§7Have played in this server since §a$totalday.\n".
			"§7Total online time: §a$FirstTimeJoin minutes\n\n".
			"§7Have onlined in this server for §a$onlinetimeinaday minutes today\n\n".
			"§7Online level: $rank" 
			);
			$form->addButton("Ok,Im good",0,"textures/ui/accessibility_glyph_color");
			$form->sendToPlayer($player);
			return $form;
   }
}