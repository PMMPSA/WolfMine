<?php

namespace CUI;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\utils\TextFormat as __;
use pocketmine\utils\Config;
use pocketmine\event\{Listener, player\PlayerQuitEvent, server\DataPacketReceiveEvent};
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use onebone\economyapi\EconomyAPI;
use command\GC;
use pocketmine\event\player\PlayerJoinEvent;
use UIS\loai2;
use UIS\loai1;
use UIS\hotro;

class Main extends PluginBase implements Listener{

 /**
     * @var EconomyAPI
     */
	 private $players;
    private $economyapi;
	public $used;
	 public $eco;
	 public $giftcode;
	 public $instance;
	
	public function onEnable(){
		if(!is_dir($this->getDataFolder())) {
		mkdir($this->getDataFolder());
		}
		$this->eco = EconomyAPI::getInstance();
		
		$this->used = new \SQLite3($this->getDataFolder() ."used-code.daxai");
		$this->used->exec("CREATE TABLE IF NOT EXISTS code (code);");
		
		$this->giftcode = new \SQLite3($this->getDataFolder() ."code.daxai");
		$this->giftcode->exec("CREATE TABLE IF NOT EXISTS code (code);");
		
		$this->RegisterCommand();
		$this->RegisterShortcut();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->getLogger()->info(TextFormat::GREEN . "Enabled.");
	}
	
	function CreateAccount(PlayerJoinEvent $event){
    $name = mb_strtolower($event->getPlayer()->getName());
    if(!file_exists($this->getDataFolder()."data/$name.yml")){
      $this->dataBase[$name] = new Config($this->getDataFolder()."data/$name.yml", Config::YAML);
      $this->dataBase[$name]->set("codeuse", 0);
	  $this->dataBase[$name]->set("codeev", 0);
	  $this->dataBase[$name]->set("hotro", 0);
      $this->dataBase[$name]->save();
    }else{
    $this->dataBase[$name] = new Config($this->getDataFolder()."data/$name.yml", Config::YAML);
    }
    }
	#useeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
	public function useCode($player){
       $name = mb_strtolower($player->getName());
        return $this->dataBase[$name]->get("codeuse");
     }
	 
	 public function useCodeev($player){
       $name = mb_strtolower($player->getName());
	  return $this->dataBase[$name]->get("codeev");
     }
	 
	 public function useCodehotro($player){
       $name = mb_strtolower($player->getName());
	  return $this->dataBase[$name]->get("hotro");
     }
	################################################
	
	#set codeeeeeeeeeeeeeeeeeeeeeeeeeee
	 public function setCode($player, $cu){
    $name = mb_strtolower($player->getName());
    $this->dataBase[$name]->set("codeuse", $cu);
    $this->dataBase[$name]->save();
  }
  
  public function setCodeev($player, $cu){
    $name = mb_strtolower($player->getName());
	$this->dataBase[$name]->set("codeev", $cu);
    $this->dataBase[$name]->save();
  }
	
	public function setCodehotro($player, $cu){
    $name = mb_strtolower($player->getName());
	$this->dataBase[$name]->set("hotro", $cu);
    $this->dataBase[$name]->save();
  }
	###############################################
	public static function getInstance() {
	  return $this;
	  }
	  
	  public function generateCode() {
	     $characters = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm123456789';
    $charactersLength = strlen($characters);
	$length = 10;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
	
		$this->addCode($this->giftcode, $randomString);
		
		$this->getServer()->getLogger()->info($randomString);
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

	public function onDisable(){
		$this->getLogger()->info(TextFormat::RED . "Disabled.");
	}

	public function RegisterCommand(){
		$this->getServer()->getCommandMap()->register("voucher", new GC("voucher", $this));
	}

	public function RegisterShortcut(){
		$this->loai1 = new loai1($this);
		$this->loai2 = new loai2($this);
		$this->hotro = new hotro($this);
	}
	

	public function onCommand(CommandSender $s, Command $cmd, string $label, array $args): bool {
	 
	 if($cmd->getName() == "vc") {
		if($s->isOp()) {
	 		
		 $code = $this->generateCode();
		 $code1 = $this->generateCode();
		 $code2 = $this->generateCode();
		 $code3 = $this->generateCode();
		 $code4 = $this->generateCode();
		/* $this->getServer()->getLogger()->info("§c[§a--------------------------------------§c>");
		 $s->sendMessage("§a --                               --");
		 $s->sendMessage("§a  §b•-> §cCode §dID §e". $code ."");
		 $s->sendMessage("§a  §b•-> §cCode §dID §e". $code1 ."");
		 $s->sendMessage("§a  §b•-> §cCode §dID §e". $code2 ."");
		 $s->sendMessage("§a  §b•-> §cCode §dID §e". $code3 ."");
		 $s->sendMessage("§a  §b•-> §cCode §dID §e". $code4 ."");
		 $s->sendMessage("§a --                               --");
		 $s->sendMessage("§c[§a--------------------------------------§c>");*/
		}
	 }
	 return true;
 }
 
}
