<?php

namespace Tungst_jobui;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
class Main extends PluginBase implements Listener {


	public function onEnable(){
		$this->getLogger()->info("JobUI \n\n\n aloooooooooooooo\n");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$a = new Task_jb($this);	
        $this->getScheduler()->scheduleRepeatingTask($a,100);
		date_default_timezone_set('Asia/Ho_Chi_Minh');
	}
	
	public function taskRunner(){
	  
	   if(in_array(date("H",time()),$this->getConfig()->getNested("CheckTime"))){
		   if(!$this->getConfig()->getNested("isCheckItem")){
			  $this->getConfig()->setNested("isCheckItem",true);
			  $this->getConfig()->setAll($this->getConfig()->getAll());
              $this->getConfig()->save();
			  foreach($this->getConfig()->getNested("playerjobs") as $p){
				$a = $p["isCheck"]; $name = $p["name"];
				$this->getConfig()->setNested("playerjobs.$name.isCheck",false);
			  }
			  $this->getConfig()->setAll($this->getConfig()->getAll());
              $this->getConfig()->save();

			   foreach($this->getServer()->getOnlinePlayers() as $a){
					$n = $a->getName();
					$inv = $a->getInventory();
				    $p = $this->getConfig()->getNested("playerjobs.$n");
					if(null == $p){goto lol;}
				    $name = $p["name"];			    
					foreach($this->getConfig()->getNested("job")[$p["idjob"]]["item"] as $item){
				      $exp = explode(":", $item);
                      $it = Item::get($exp[0],$exp[1],$exp[2]);
				      $amount = $exp[2];
                      if($inv->contains($it)){
                        foreach($this->getConfig()->getNested("CommandForDoneJob") as $a){
					       $this->getServer()->dispatchCommand(new ConsoleCommandSender(),str_replace(["{name}"],[$name],$a));	
						   $this->getServer()->getOnlinePlayers()->sendMessage("§d•§e Chúc mừng! Bạn đã hoàn thành tiêu chuẩn nghề vào hôm nay & được cộng 500.000VNĐ");
						}
						$inv->removeItem($it);
					  }else{
						foreach($this->getConfig()->getNested("CommandForUnDoneJob") as $a){
					       $this->getServer()->dispatchCommand(new ConsoleCommandSender(),str_replace(["{name}"],[$name],$a));
						    $this->getServer()->getPluginManager()->getPlugin("DNP")->reduceDNP($name, 10);
                             $this->getServer()->getOnlinePlayers()->sendMessage("§d•§e Thông Báo: Vì bạn không hoàn thành tiêu chuẩn nghề vào hôm nay nên đã bị trừ 10 điểm Nhân Phẩm");
						}  
					  }
					}		 
					$this->getConfig()->setNested("playerjobs.$n.isCheck",true);
					$this->getConfig()->setAll($this->getConfig()->getAll());
                    $this->getConfig()->save();
					lol:
			   }
		   }
		   return false;
	   }else{
		if($this->getConfig()->getNested("isCheckItem")){
			$this->getConfig()->setNested("isCheckItem",false);
			$this->getConfig()->setAll($this->getConfig()->getAll());
			$this->getConfig()->save();
		}
	   }
	  
	}
	
	public function onJoin(PlayerJoinEvent $e){
	  $n = $e->getPlayer()->getName();
	  if(null == $this->getConfig()->getNested("playerjobs.$n")){
	    $this->mainform($e->getPlayer());
	  }else{
		//sao lai comment cho nay
		//$e->getPlayer()->setDisplayName($this->getConfig()->getNested("playerjobs.$n.job")." $n"); 
		$this->getLogger()->info("abc");
	  }
	  if(null !== $this->getConfig()->getNested("playerjobs.$n")){
      if(!$this->getConfig()->getNested("playerjobs.$n.isCheck")){
		$inv = $e->getPlayer()->getInventory();
		$p = $this->getConfig()->getNested("playerjobs.$n");
		$name = $n;			    
		foreach($this->getConfig()->getNested("job")[$p["idjob"]]["item"] as $item){
		  $exp = explode(":", $item);
		  $it = Item::get($exp[0],$exp[1],$exp[2]);
		  $amount = $exp[2];
		  if($inv->contains($it)){
			foreach($this->getConfig()->getNested("CommandForDoneJob") as $a){
			   $this->getServer()->dispatchCommand(new ConsoleCommandSender(),str_replace(["{name}"],[$name],$a));	
			   $e->getPlayer()->sendMessage("§d•§e Chúc mừng! Bạn đã hoàn thành tiêu chuẩn nghề vào hôm nay & được cộng 500.000VNĐ");
			}
			$inv->removeItem($it);
		  }else{
			foreach($this->getConfig()->getNested("CommandForUnDoneJob") as $a){
			   $this->getServer()->dispatchCommand(new ConsoleCommandSender(),str_replace(["{name}"],[$name],$a));	
			   $this->getServer()->getPluginManager()->getPlugin("DNP")->reduceDNP($name, 10);
			   $e->getPlayer()->sendMessage("§d•§e Thông Báo: Vì bạn không hoàn thành tiêu chuẩn nghề vào hôm nay nên đã bị trừ 10 điểm Nhân Phẩm");
			}  
		  }
		}		 
		$this->getConfig()->setNested("playerjobs.$n.isCheck",true);
		$this->getConfig()->setAll($this->getConfig()->getAll());
		$this->getConfig()->save();
	  }}

	}
	public function jobinfo($player){
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return false;
			}else{
				return false;
			}
			});			
			$form->setTitle("§d•§e HỆ THỐNG NGHỀ NGHIỆP§d •");
			$txt = "";
			foreach($this->getConfig()->getNested('job') as $object){
				$n = $object["name"];
				$txt = $txt."+$n cần (một ngày):\n";
				foreach($object["item"] as $item){
				  $exp = explode(":", $item);
                  $name = Item::get($exp[0],$exp[1])->getName();
				  $amount = $exp[2];
				  $txt = $txt."  -$name : $amount\n";					
				  			  
				}
			}
			$n = $player->getName();
			$p = $this->getConfig()->getNested("playerjobs.$n");
			$job = $this->getConfig()->getNested("job")[$p["idjob"]]["name"];
			$form->setContent(
			"§d•§b Nghề của bạn§e: $job §7\n".
			"§d•§b Thông tin nghề nghiệp§e: \n".
			"§d•§b $txt"
			);
			$form->addButton("Đã Hiểu");			
			$form->sendToPlayer($player);
			return $form;
	}
	public function mainform($player){
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			print("RESULT");
			var_dump($result);
			if($result === null){
				$this->mainform($player);
			}else{
				print("b");
				$this->setJob($player,$result);
			}
			});			
			$form->setTitle("§d•§e HỆ THỐNG NGHỀ NGHIỆP§d •");
			$txt = "";
			foreach($this->getConfig()->getNested('job') as $object){
				$jbname = $object["name"];
				$txt = $txt."$jbname cần (mỗi ngày):\n";
				foreach($object["item"] as $item){
				  $exp = explode(":", $item);
                  $name = Item::get($exp[0],$exp[1])->getName();
				  $amount = $exp[2];
				  $txt = $txt."  -$name : $amount\n";					
				  			  
				}
			}
			$form->setContent(
			"§d•§b Vui lòng chọn nghề của bạn §b(§eHành động này chỉ được thực hiện một lần duy nhất§b)§e:\n".
			"§d•§b Thông tin nghề nghiệp§e: \n".
			"§d•§b $txt"
			);
			foreach($this->getConfig()->getNested('job') as $name){	   
               $form->addButton($name['name'],0,$name['png']);			
			}
			$form->sendToPlayer($player);
			return $form;
	   
	}
	public function setJob($p,$id){
		print("a");
		$n = $p->getName();
		$this->getConfig()->setNested("playerjobs.$n.job", $this->getConfig()->getNested('job')[$id]["prefix"]);
		$this->getConfig()->setNested("playerjobs.$n.name", $n);
		$this->getConfig()->setNested("playerjobs.$n.idjob", $id);
		$this->getConfig()->setNested("playerjobs.$n.isCheck",true);
	    $this->getConfig()->setAll($this->getConfig()->getAll());
        $this->getConfig()->save();
		//$p->setDisplayName($this->getConfig()->getNested('job')[$id]["prefix"]." $n");
		$p->sendMessage("§d•§b Cư Dân đã lựa chọn thành công nghề§e: §c".$this->getConfig()->getNested('job')[$id]["name"]);
	}
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        if($sender instanceof Player){
		switch(strtolower($command->getName())){
            case "nghe":             
				 $this->mainform($sender);			  
			break;
			case "nghe-tt":             
				 $this->jobinfo($sender);			  
			break;
        }
		}else{	
		};
		return true;
	}
}