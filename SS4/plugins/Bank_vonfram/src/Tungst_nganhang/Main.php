<?php

namespace Tungst_nganhang;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
class Main extends PluginBase implements Listener {

	public $rate =  0.00000165343;//per sec
	
	//you add the thief rate like example, x/y with y is the maxpercent,
	//1/200 means for every 200 people joining in the server, there are 1 chances that a bank will be robbed
	public $maxpercent = 200000;
	public $percent = 
	[
		"15/200" => 0.1,
		"20/200" => 0.1,
		"80/200" => 0.3
	];
	public function onEnable(){
		$this->getLogger()->info("Bank enable");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		//$this->saveDefaultConfig();
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$this->ready();
	}
	public function onThief(){
		$rand = rand(0,$this->maxpercent);
		if(isset($this->checkpercent[$rand])){
			$this->thief($this->checkpercent[$rand]);
		}
	}
	public $checkpercent = [];
	public function ready(){
		$id = 1;
		foreach($this->percent as $key => $val){
		   $num = explode('/',$key)[0];
		 //  print($num."\n");
		   if(!is_numeric($num)){print("\n\nTHERE IS STH WRONG WITH THE PERCENT\n\n");return false;}
           for($i = 0;$i< $num;$i++){
			  $this->checkpercent[$id] = $val;
			  $id++;
		   }
		}
		//var_dump($this->checkpercent);
	}
    public function onJoin(PlayerJoinEvent $e){
		$p = $e->getPlayer();$n = $p->getName();
	 if(null === $this->getConfig()->getNested("bank.$n.money")){	
		$this->getConfig()->setNested("bank.$n.name", $n);
		$this->getConfig()->setNested("bank.$n.money", 0);
		$this->getConfig()->setNested("bank.$n.profit", 0);
		$this->getConfig()->setNested("bank.$n.checktime", time());
		$this->getConfig()->setAll($this->getConfig()->getAll());
		$this->getConfig()->save();		  
	 }
	 $this->onThief();
	  if(null !== $this->getConfig()->getNested("delay.$n")){
		 $p->sendMessage($this->getConfig()->getNested("delay.$n"));
		 $a = $this->getConfig()->getNested("delay.$n");
		 $array = $this->getConfig()->getNested("delay");				 
		 unset($array[array_search($a,$array)]);            
		 $this->getConfig()->setNested("delay",$array);
		 $this->getConfig()->setAll($this->getConfig()->getAll());
		 $this->getConfig()->save();	
	 }
	}
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        if($sender instanceof Player){
		switch(strtolower($command->getName())){
            case "nganhang":
            $this->mainform($sender,"");
            break;
			case "rob":
            if (!isset($args[0])){$sender->sendMessage("Use /rob <percent> (percent ex: 0.5,1,0.2)");return false;}
			if(!is_numeric($args[0])){$sender->sendMessage("percent must be like: 0.5,1,0.2");return false;}
			$this->thief($args[0]);
			$sender->sendMessage("Successful rob ".$args[0]."% in the bank");
			break;
        }
		}else{
			switch(strtolower($command->getName())){
				case "rob":
				if (!isset($args[0])){$sender->sendMessage("Use /rob <percent> (percent ex: 0.5,1,0.2)");return false;}
				if(!is_numeric($args[0])){$sender->sendMessage("percent must be like: 0.5,1,0.2");return false;}
				$this->thief($args[0]);
				$sender->sendMessage("Successful rob ".$args[0]."% in the bank");
				break;
			}
		};
		return true;
	}
	
	public function profit($player,$err){
		
		 $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		 $form = $api->createSimpleForm(function (Player $player, int $data = null){
			$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			 $result = $data;
			 if($result === null){
				$this->mainform($player,"");
				 return true;
			 }
			    if($result == 0){
				$n = $player->getName();

				 $a = time() - $this->getConfig()->getNested("bank.$n.checktime");
				 $profit = $this->getConfig()->getNested("bank.$n.profit") + $this->getConfig()->getNested("bank.$n.money")*$this->rate*$a;
				 $api2->addMoney($player,$profit);
				 $this->getConfig()->setNested("bank.$n.profit", 0);
				 $this->getConfig()->setNested("bank.$n.checktime", time());
				 $this->getConfig()->setAll($this->getConfig()->getAll());
				 $this->getConfig()->save();	
				 $this->mainform($player,"??d?????b Nh???n l??i xu???t th??nh c??ng:??e \n");
				}else{
					$this->mainform($player,"");
				}
				return false;
			 });
			 $n = $player->getName();
			 $profit = $this->getConfig()->getNested("bank.$n.profit"); 
			 $form->setTitle("??l??d?????b===??e L??i Xu???t ??b===??d???");
			 $form->setContent(
				 "??d?????b T???ng l??i xu???t nh???n ???????c??e: $profit \n".
				 "??d?????b Nh???p (??eNh???n??b) ????? nh???n l??i xu???t"
			 );
			 $form->addButton("??b(??eNh???n??b)",0,"textures/ui/generic_face_button_down");
			 $form->addButton("??b(??cL??c Kh??c??b)",0,"textures/ui/permissions_visitor_hand_hover");
			 $form->sendToPlayer($player);
			 return $form;
		
	}

	public function withdraw($player,$err){ 
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			$form = $api->createCustomForm(function (Player $player,$data){
				$result = $data;
				$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
				if($result === null){
					$this->mainform($player,"");
					return false;
				}else{
					if(!isset($data[1]) || !is_numeric($data[1])){
						$this->withdraw($player,"??d?????e S??? ti???n nh???p v??o ph???i l?? s???\n");		
					    return false;
					}
					if($data[1] > $this->getPlayerBankMoney($player)){
						$this->withdraw($player,"??d?????e B???n kh??ng c?? ????? s??? ti???n ????\n");		
					    return false;
					}
					$n = $player->getName();
					$this->getConfig()->setNested("bank.$n.money",$this->getPlayerBankMoney($player) -$data[1]);
					$this->getConfig()->setAll($this->getConfig()->getAll());
					$this->getConfig()->save();
					$api2->addMoney($player,$data[1]);
					$this->mainform($player,"??d?????e R??t ti???n th??nh c??ng\n");	
					return false;	
				}			
				});	
				$money = $api2->myMoney($player);	
				$bankmoney = $this->getPlayerBankMoney($player);
				$profit = round($this->getPlayerBankMoney($player)*$this->rate,6);
				$form->setTitle("??l??d?????b===??e R??t Ti???n ??b===??d???");	
				$form->addLabel(
					"$err".
					"??d?????b S??? ti???n hi???n t???i c???a b???n??e: $money VN??\n". 
				"??d?????b S??? ti???n trong t??i kho???n ng??n h??ng??e: $bankmoney VN??\n". 
				"??d?????b L??i xu???t nh???n ???????c qua m???i gi??y??e: $profit VN??"
				);
				$form->addInput("??d?????b Vui l??ng nh???p s??? ti???n b???n mu???n r??t??e:","5000000");
				$form->sendToPlayer($player);
				return $form;
	   }

	public function deposit($player,$err){ 
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			$form = $api->createCustomForm(function (Player $player,$data){
				$result = $data;
				$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
				if($result === null){
					$this->mainform($player,"");
					return false;
				}else{
					if(!isset($data[1]) || !is_numeric($data[1])){
						$this->deposit($player,"??d?????e S??? ti???n ph???i l?? s???\n");		
					    return false;
					}
					if($data[1] > $api2->myMoney($player)){
						$this->deposit($player,"??d?????b B???n kh??ng c?? ????? s??? ti???n ????\n");		
					    return false;
					}
					$n = $player->getName();
					$this->getConfig()->setNested("bank.$n.money",$data[1] + $this->getPlayerBankMoney($player));
					$this->getConfig()->setAll($this->getConfig()->getAll());
					$this->getConfig()->save();
					$api2->reduceMoney($player,$data[1]);
					$this->mainform($player,"??d?????b G???i ti???n v??o ng??n h??ng th??nh c??ng\n");	
					return false;	
				}			
				});	
				$money = $api2->myMoney($player);	
				$bankmoney = $this->getPlayerBankMoney($player);
				$profit = round($this->getPlayerBankMoney($player)*$this->rate,6);
				$form->setTitle("??l??d?????b===??e G???i Ti???n ??b===??d???");	
				$form->addLabel(
					"$err".
					"??d?????b S??? ti???n hi???n t???i c???a b???n??e: $money VN??\n". 
				"??d?????b S??? ti???n trong t??i kho???n ng??n h??ng??e: $bankmoney VN??\n". 
				"??d?????b L??i xu???t nh???n ???????c qua m???i gi??y??e: $profit VN??"
				);
				$form->addInput("??d?????b Vui l??ng nh???p s??? ti???n b???n mu???n g???i??e:","5000000");
				$form->sendToPlayer($player);
				return $form;
	   }

   public function getPlayerBankMoney($p){
	   $n = $p->getName();
	if(null !== $this->getConfig()->getNested("bank.$n.money")){	
	  return $this->getConfig()->getNested("bank.$n.money");
	}else{
		return 0;
	}
   }
   public function thief($rate){
	   if(null === $this->getConfig()->getNested("bank")){return false;}
	   foreach($this->getConfig()->getNested("bank") as $bank){
		   $n = $bank["name"];$amount = $bank["money"];$rob = $amount *$rate;$left = $amount - $rob;
		$this->getConfig()->setNested("bank.$n.money",$amount - $rob);
		$player = $this->getServer()->getPlayer($n);
		$percent = $rate*100;
		$msg = "??d?????b B???n ???? b??? c?????p ??e$percent%??b ti???n trong t??i kho???n. T????ng ??????ng v???i ??e$rob VN????b, s??? ti???n c??n l???i??e: $left VN??";
		//S??? ti???n c??n l???i
		if(null !== $player){
			
		//	$msg = "??cYou have been thief ??l$percent%??r??c money in your bank,equal to ??l$rob ??r??c,Money left:??l $left";
			$player->sendMessage($msg);

		}else{
			if(null !== $this->getConfig()->getNested("delay.$n")){
				$this->getConfig()->setNested("delay.$n",$this->getConfig()->getNested("delay.$n")."\n".$msg);
			}else{
				$this->getConfig()->setNested("delay.$n",$msg);
			}
		}
	   }
	   $this->getConfig()->setAll($this->getConfig()->getAll());
		$this->getConfig()->save();		
   }
   public function mainform($player,$err){
	   $n = $player->getName();
	   if(null === $this->getConfig()->getNested("bank.$n.money")){$player->sendMessage("There is sth wrong");return false;}
	   if($this->getConfig()->getNested("bank.$n.checktime") < time()){
		$a = time() - $this->getConfig()->getNested("bank.$n.checktime");
		$profit = $this->getConfig()->getNested("bank.$n.money")*$this->rate*$a;
		$this->getConfig()->setNested("bank.$n.profit", $this->getConfig()->getNested("bank.$n.profit")+$profit);
		$this->getConfig()->setNested("bank.$n.checktime", time());
		$this->getConfig()->setAll($this->getConfig()->getAll());
		$this->getConfig()->save();		
	   }
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$api2 = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
			$result = $data;
			if($result === null){
				return true;
			}
			switch($result){				
					case "0";
                     $this->deposit($player,"");					
					break;	
					case "1";				
					 $this->withdraw($player,"");
					break;
					case "2";		
					$this->profit($player,"");		
					break;
					default:
					break;
			}
			});	
			$mon = $api2->myMoney($player);		
			$n = $player->getName();
			$money = $this->getConfig()->getNested("bank.$n.money"); 
			$profit = $this->getConfig()->getNested("bank.;$n.profit"); 
			$rate2 = round($this->getPlayerBankMoney($player)*$this->rate,6);
			$rate = round(604800 * $this->rate,6)*100;
			$form->setTitle("??l??d?????b===??e NG??N H??NG WOLFBANK ??b===??d???");
			$a = " % m???i tu???n";
			$form->setContent(
				"$err".
				"??d?????b S??? ti???n hi???n t???i c???a b???n??e: $mon VN??\n". 
				"??d?????b S??? ti???n trong t??i kho???n ng??n h??ng??e: $money VN??\n". 
				"??d?????b L??i xu???t nh???n ???????c qua m???i gi??y??e: $profit VN??\n".
				"??d?????b T??? l??? ng??n h??ng??e: ".$rate."$a\n". 
				"??d?????b L??i xu???t nh???n ???????c m???i gi??y??e: $rate2 VN??"
			);
			$form->addButton("??e(??cG???i Ti???n??e)",0,"textures/ui/generic_face_button_down");
			$form->addButton("??e(??cR??t Ti???n??e)",0,"textures/ui/generic_face_button_right");
			$form->addButton("??e(??cR??t L??i Xu???t??e)",0,"textures/ui/generic_face_button_down");
			$form->addButton("??e(??cTho??t??e)",0,"textures/ui/permissions_visitor_hand_hover");
			$form->sendToPlayer($player);
			return $form;
	   
   }
}