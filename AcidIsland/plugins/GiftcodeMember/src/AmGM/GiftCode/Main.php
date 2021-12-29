<?php

namespace AmGM\GiftCode;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;
use PTK\coinapi\Coin;

class Main extends PluginBase {
        
     public $used;
	 public $eco;
	 public $giftcode;
	 public $instance;
	 public $coin;

	 public function onEnable() {
	    if(!is_dir($this->getDataFolder())) {
		mkdir($this->getDataFolder());
		}

		$this->eco = EconomyAPI::getInstance();
		$this->coin = Coin::getInstance();
		$this->used = new \SQLite3($this->getDataFolder() ."used-code.db");
		$this->used->exec("CREATE TABLE IF NOT EXISTS code (code);");
		
		$this->giftcode = new \SQLite3($this->getDataFolder() ."code.dn");
		$this->giftcode->exec("CREATE TABLE IF NOT EXISTS code (code);");
	 }
	 
	 public static function getInstance() {
	  return $this;
	  }
	  
	 public function generateCode() {
	     $characters = '0123456789QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm';
    $charactersLength = strlen($characters);
	$length = 15;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
	
		$this->addCode($this->giftcode, $randomString);
		
		$this->getServer()->getLogger()->info("§aDEBUG ". $randomString);
    return $randomString;
	 }
	 public function codeExists($file, $code) {
		 
		 
		 $query = $file->query("SELECT * FROM code WHERE code='$code';");
		 $ar = $query->fetchArray(SQLITE3_ASSOC);
		 
		 if(!empty($ar)) {
			 return true;
		 } else {
			 return false;
		 }
	 }
	 
	 public function addCode($file, $code) {
		 
		 $stmt = $file->prepare("INSERT OR REPLACE INTO code (code) VALUES (:code);");
		 $stmt->bindValue(":code", $code);
		 $stmt->execute();
		 
	 }
	 public function onCommand(CommandSender $s, Command $cmd, $label, array $args) {
	 if($cmd->getName() == "gencode-tanthu") {
		if($s->isOp()) {
		 $code = $this->generateCode();
		 $this->getServer()->broadcastMessage("§l§fNew code§a: ".$code." ");
		}
	 }
	 //}
	 //}
	 if($cmd->getName() == "code-tanthu") {
	    if(isset($args[0])) {
		
		
		if($this->codeExists($this->giftcode, $args[0])) {
		
		
	     if(!($this->codeExists($this->used, $args[0]))) {
		 
           $chance = mt_rand(1, 100);
		   
		   $this->addCode($this->used, $args[0]);
		   
		   $this->getServer()->getLogger()->info("§aDEBUG code:". $args[0]);
		   $this->getServer()->broadcastMessage("§l§f".$s->getName()."§a was used code§d $args[0]");
		   
		   switch($chance) {
		   case 50:
		     
			 $keys = array_rand(Item::$list, 4);
			 for($i = 0; $i <= 3; ++$i) {
			    $item = Item::get($keys[$i], 0, 1);
			   $s->addItem($item);
			   $s->sendMessage("§aBạn nhận được §c". $item->getName() ." §atừ code này");
			  
	    }
		break;
		  case 40:
		    $s->sendMessage("§aPlease, check your §finventory");
			$s->sendMessage("§aYou have received§c 5.000 Money§a with§f 64x diamond");
			$this->eco->addMoney($s->getName(), 5000);
			$s->getInventory()->addItem(Item::get(264, 0, 64));
			break;
	       default:
		    $s->sendMessage("§aPlease, check your §finventory");
			$s->sendMessage("§aYou have received§c 5.000 Money§a with§f 64x diamond");
			$this->eco->addMoney($s->getName(), 5000);
			$s->getInventory()->addItem(Item::get(264, 0, 64));
			break;
	    }
	   } else {
	   $s->sendMessage("§cThis code was used");
	   return true;
	   }
      } else {
	  $s->sendMessage("§aCode $args[0] not found");
	  return true;
	  }
	 }
    }
    }
    }