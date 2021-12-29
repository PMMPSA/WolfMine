<?php
namespace nlog1\Police1;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Utils;
class Main1 extends PluginBase implements Listener{
	
public $pre = "§l§a[§6 POLICE1§a ]§r ";
 	 public function onEnable(){
    	$this->getServer()->getPluginManager()->registerEvents($this, $this);
       
		$this->getLogger()->notice("§l§bPlugin Police");
		$this->getLogger()->info("§l§eEnable");
    	
    	
    	//Config
    	@mkdir($this->getDataFolder(), 0744, true);
    	$this->police = new Config($this->getDataFolder() . "office1.yml", Config::YAML); //Config 생성
 	 }
 	 
 	 
 	 //경찰 API
 	 public function getPolice() {
 	 	/*
 	 	 * 경찰의 목록을 Config에서 가져옵니다.
 	 	 */
 	 	return $this->police->getAll(true);
 	 }
 	 
 	 public function isPolice($name) {
 	 	/*
 	 	 * Config에 $name이 있으면 true를 없으면 false를 반환합니다.
 	 	 */
 	 	return $this->police->exists($name);
 	 }
 	 
 	 public function setPolice($name) {
 	 	/*
 	 	 * 경찰을 Config에 추가합니다.
 	 	 */
 	 	$this->police->set($name, "police1");
 	 	$this->police->save();
 	 	return true;
 	 }
 	 
 	 public function removePolice($name) {
 	 	/*
 	 	 * 경찰을 Config에서 제거합니다.
 	 	 */
 	 	$this->police->remove($name, "police1");
 	 	$this->police->save();
 	 	return true;
 	 }
 	 
 	 public function onCommand(CommandSender $sender,Command $cmd,string $label,array $args) :bool{
 	 	
 	 	$msg = "§7/police1 <add | remove> <Username> \n §b§o[ Police1 ] §7/police1 list";
 	 	
 	 	if(strtolower($cmd->getName() === "police1")) {
 	 		if (!($sender->isOp())) {
 	 			$sender->sendMessage("§d•§e Cư Dân không có quyền");
 	 			return true; //OP 가 아닐 때 - 안전빵으로 한번 더ㅋㅋ
 	 		}
 	 		if (!(isset($args[0]))) {
 	 			$sender->sendMessage($msg);
 	 			return true;
 	 		}
			#-----------------------------------------------------------------------------
 	 		if ($args[0] === "add") {
 	 			if (!(isset($args[1]))) {
 	 				$sender->sendMessage($msg);
 	 				return true;
 	 			} //닉네임이 없을 때
				
 	 		$this->setPolice(strtolower($args[1]));
 	 		$sender->sendMessage($this->pre."§b§l Bạn vừa set Police1 cho §e".$args[1].".");
			return true;
 	 		}
			#-----------------------------------------------------------------------------
 	 		if ($args[0] === "remove") {
 	 			if (!(isset($args[1]))) {
 	 				$sender->sendMessage($msg);
 	 				return true;
 	 			} //닉네임이 없을 때
				
 	 		if (!($this->isPolice(strtolower($args[1])))) {
 	 				$sender->sendMessage($this->pre. "§c§lNgười này không có trong danh sách Police1.");
 	 				return true;
 	 			} //닉네임이 경찰이 아닐 때
				
 	 			$this->removePolice($args[1]);
 	 			$sender->sendMessage($this->pre."§b§lXóa Police1 của §e".$args[1]."§b thành công");
 	 			return true;
 	 		}
			#-----------------------------------------------------------------------------
 	 		if ($args[0] === "list") {
 	 			$list = implode("\n ", $this->getPolice());
 	 			$sender->sendMessage($this->pre."§b§oDanh sách Police1§a: " . $list);
 	 			return true; //리스트
				
			}else{
				$sender->sendMessage($msg);
				return true; //$args[0]이 없을 때
				
			}
 	 	}
 	 }
 	 
 	 public function onJoin (PlayerJoinEvent $ev) {
			$name = $ev->getPlayer()->getName();
			if ($this->isPolice(strtolower($name)) === true) {
				$per = $ev->getPlayer()->addAttachment($this);
				$per->setPermission("pocketmine.command.kick", true);
				$per->setPermission("pocketmine.command.teleport", true);
				$per->setPermission("pocketmine.command.list", true);
				$per->setPermission("solobanmaster.command.police", true);
				$ev->getPlayer()->setDisplayName("§l§c[§bCảnh Sát Trưởng§c] §r".$name);
				$per->setPermission("pocketmine.command.ban", true);
				$per->setPermission("pocketmine.command.ban.player", true);
				$per->setPermission("quan.check", true);
				$per->setPermission("pocketmine.command.ban.ip", true);
				$per->setPermission("pocketmine.command.ban.list", true);
				$per->setPermission("pocketmine.command.unban", true);
				$per->setPermission("pocketmine.command.unban.player", true);
				$per->setPermission("pocketmine.command.unban.ip", true);
				}
 	}
 	 
 	 public function onQuit (PlayerQuitEvent $ev) {
		$name = $ev->getPlayer()->getName();
 	 	$per = $this->getServer()->getPlayer($name)->addAttachment($this);
 	 	$per->setPermission("pocketmine.command.kick", false);
 	 	$per->setPermission("pocketmine.command.teleport", false);
 	 	$per->setPermission("pocketmine.command.list", false);
 	 	$per->setPermission("solobanmaster.command.police", false);
				$per->setPermission("pocketmine.command.ban", false);
				$per->setPermission("pocketmine.command.ban.player", false);
				$per->setPermission("quan.check", false);
				$per->setPermission("pocketmine.command.ban.ip", false);
				$per->setPermission("pocketmine.command.ban.list", false);
				$per->setPermission("pocketmine.command.unban", false);
				$per->setPermission("pocketmine.command.unban.player", false);
				$per->setPermission("pocketmine.command.unban.ip", false);
 	 }
  }
?>
