<?php

namespace tungst_tradePP;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\inventories\ChestInventory;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use tungst_tradePP\TradeClass;
use tungst_tradePP\CheckTask;
use pocketmine\scheduler\TaskScheduler;
class Main extends PluginBase implements Listener {

    public $task;
	public $request = [];
	public function onEnable(){
		$this->getLogger()->info("Trade enable");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onJoin(PlayerJoinEvent $e){
		$p = $e->getPlayer();$n = $p->getName();
		if(null != $this->getConfig()->getNested("delayTrade.$n")){
			foreach($this->getConfig()->getNested("delayTrade.$n") as $item){
			   $itemtoadd = unserialize(utf8_decode($item));	
			   if($p->getInventory()->canAddItem($itemtoadd)){
			      	$p->getInventory()->addItem($itemtoadd);
				 	 $a = $item;
				     $array = $this->getConfig()->getNested("delayTrade.$n");				 
				     unset($array[array_search($a,$array)]);            
				     $this->getConfig()->setNested("delayTrade.$n",$array);
				     $this->getConfig()->setAll($this->getConfig()->getAll());
				     $this->getConfig()->save();	
				    $p->sendMessage("\n§d•§e Bạn đã nhận được vật phẩm từ lần mở mục Bưu Điện gần đây\n");
			   }else{
                $p->sendMessage("\n§d•§e Dọn dẹp rương của bạn, sau đó vào lại để nhận vật phẩm\n");
			   }
			}
		}
	}
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        //string $minname; 
		if($sender instanceof Player){
		   switch(strtolower($command->getName())){
               case "buudien":
			    if(!isset($args[0])){
					$sender->sendMessage("§d•§b Vui lòng sử dụng§e: /buudien help (h)");
					return false;
				}else{
			     switch(strtolower($args[0])){   
					case "h":
				 case "help":
				  $sender->sendMessage("§d•§b /buudien <Cư Dân>§e: Để tiến hành mở mục Bưu Điện đối với Cư Dân đó");
				  $sender->sendMessage("§d•§b /buudien xacnhan (x)§e: Để xác nhận lời mở mục Bưu Điện");
				  $sender->sendMessage("§d•§b /buudien tuchoi (t)§e: Để hủy lời mở mục Bưu Điện §b(§eTự động hủy sau 10 giaay§b)");
				  break;
				  case "x":
				 case "xacnhan":
				  if(in_array($sender->getName(),$this->request)){			
					  var_dump(array_search($sender->getName(),$this->request));
						if($this->getServer()->getPlayer(array_search($sender->getName(),$this->request)) instanceof Player){
							$a = new TradeClass($this,$this->getServer()->getPlayer(array_search($sender->getName(),$this->request)),$sender);
							$this->getServer()->getPluginManager()->registerEvents($a, $this);		
						    unset($this->request[array_search($sender->getName(),$this->request)]);
						}
				  }else{
					$sender->sendMessage("§d•§e Bạn không có lời mời mở Bưu Điện nào từ người chơi khác"); 
				  }
				  break;
				  case "h":
				 case "huy":
				   if(in_array($sender,$this->request)){
				    unset($this->request[array_search($sender,$this->request)]);
					$sender->sendMessage("§d•§e Từ chối thành công");
				   }else{
					$sender->sendMessage("§d•§e Bạn không có lời mời mở Bưu Điện nào từ người chơi khác");
				   }
				  break;			
				 default:		    
                         if($this->getServer()->getPlayer($args[0]) != null && $this->getServer()->getPlayer($args[0]) != $sender){
				   	       $this->request[$sender->getName()] = $this->getServer()->getPlayer($args[0])->getName();
							  $this->getServer()->getPlayer($args[0])->sendMessage("§d•§e ".$sender->getName(). " muốn mở mục Bưu Điện với bạn, gõ §e/buudien xacnhan (x) để xác nhận §b(§eTự động hủy sau 10 giây§b)");
						   $sender->sendMessage("§d•§b Đã gửi yêu cầu đến§e: ".$this->getServer()->getPlayer($args[0])->getName());
							  $this->getScheduler()->scheduleDelayedTask(new CheckTask($this,$sender->getName(),$this->getServer()->getPlayer($args[0])->getName()), 1200);				  
						 }else{
					       $sender->sendMessage("§d•§e Không thể tìm thấy người chơi này!");
						 }
							      
                   break;
				 }
				}
		   }
		
		
		}
	return true;
	}
}