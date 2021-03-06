<?php

namespace ZMusicBox;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\level;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\math\Math;
use pocketmine\level\format\Chunk;
use pocketmine\level\format\FullChunk;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\Binary;
use ZMusicBox\NoteBoxAPI;

class ZMusicBox extends PluginBase implements Listener{
	public $song;
	public $MusicPlayer;
	public $name;
	
	public function onEnable(){
		$this->getLogger()->info("ZMusicBox v3.0.0 is loading!");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getPluginDir())){
			@mkdir($this->getServer()->getDataPath()."plugins/songs");
		}
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		if(!$this->CheckMusic()){
			$this->getLogger()->info("§bPlease put in nbs files!!!");
		}else{
			$this->StartNewTask();
		}
		$this->getLogger()->info("ZMusicBox loaded!!!!!");
	} 

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		switch($cmd->getName()) {
			case "music":
				if(isset($args[0])){
					switch($args[0]){
						case "next":
						case "skip":
							$this->StartNewTask();
							$sender->sendMessage(TextFormat::GREEN."Switched to next song");
							return true;
							break;
						case "stop":
						case "pause":
							if($sender->isOp()){
								$this->getScheduler()->cancelAllTasks($this);
								$sender->sendMessage(TextFormat::GREEN."Song Stopped");
							}else{
								$sender->sendMessage(TextFormat::RED."No Permission");
							}
							return true;
							break;	
						case "start":
						case "begin":
						case "resume":
							if($sender->isOp()){
								$this->StartNewTask();
								$sender->sendMessage(TextFormat::GREEN."Song Started");
							}else{
								$sender->sendMessage(TextFormat::RED."No Permission");
							}
							return true;
							break;	
					}
				}else{
					$sender->sendMessage(TextFormat::RED."Usage:/music <start|stop|next>");
					return true;
				}
			break;		
		}
		return false;
	}
	
	public function CheckMusic(){
		if($this->getDirCount($this->getPluginDir()) > 0 and $this->RandomFile($this->getPluginDir(),"nbs")){
			return true;
		}
		return false;
	}
	
	public function getDirCount($PATH){
      		$num = sizeof(scandir($PATH));
      		$num = ($num>2)?$num-2:0;
		return $num;
	}
	
	public function getPluginDir(){
		return $this->getServer()->getDataPath()."plugins/songs/";
	}
	
	public function getRandomMusic(){
		$dir = $this->RandomFile($this->getPluginDir(),"nbs");
		if($dir){
			$api = new NoteBoxAPI($this,$dir);
			return $api;
		}
		return false;
	}
	
	Public function RandomFile($folder='', $extensions='.*'){
		$folder = trim($folder);
		$folder = ($folder == '') ? './' : $folder;
		if (!is_dir($folder)){
			return false;
		}
		$files = array();
		if ($dir = @opendir($folder)){
			while($file = readdir($dir)){
				if (!preg_match('/^\.+$/', $file) and
					preg_match('/\.('.$extensions.')$/', $file)){
					$files[] = $file;        
				}      
			}   
			closedir($dir);  
		}else{
			return false;
		}
		if (count($files) == 0){
			return false;
		}
		mt_srand((double)microtime()*1000000);
		$rand = mt_rand(0, count($files)-1);
		if (!isset($files[$rand])){
			return false;
		}
		if(function_exists("iconv")){
			$rname = iconv('gbk','UTF-8',$files[$rand]);
		}else{
			$rname = $files[$rand];
		}
		$this->name = str_replace('.nbs', '', $rname);
		return $folder . $files[$rand];
	}
	
	public function getNearbyNoteBlock($x,$y,$z,$world){
        $nearby = [];
		$minX = $x - 5;
        $maxX = $x + 5;	
        $minY = $y - 5;
        $maxY = $y + 5;
        $minZ = $z - 2;
        $maxZ = $z + 2;
        
        for($x = $minX; $x <= $maxX; ++$x){
			for($y = $minY; $y <= $maxY; ++$y){
				for($z = $minZ; $z <= $maxZ; ++$z){
					$v3 = new Vector3($x, $y, $z);
					$block = $world->getBlock($v3);
					if($block->getID() == 25){
						$nearby[] = $block;
					}
				}
			}
		}
		return $nearby;
	}
	
	public function getFullBlock($x, $y, $z, $level){
		return $level->getChunk($x >> 4, $z >> 4, false)->getFullBlock($x & 0x0f, $y & 0x7f, $z & 0x0f);
	}
  
	public function Play($sound,$type = 0,$blo = 0){
		if(is_numeric($sound) and $sound > 0){
			foreach($this->getServer()->getOnlinePlayers() as $p){
				$noteblock = $this->getNearbyNoteBlock($p->x,$p->y,$p->z,$p->getLevel());
				$noteblock1 = $noteblock;
				if(!empty($noteblock)){
					if($this->song->name != ""){
						$p->sendPopup("§b|->§6Now Playing: §a".$this->song->name."§b<-|");
					}else{	
						$p->sendPopup("§b|->§6Now Playing: §a".$this->name."§b<-|");
					}
					$i = 0;
					while ($i < $blo){
						if(current($noteblock)){
							next($noteblock);
							$i ++;
						}else{
							$noteblock = $noteblock1;
							$i ++;
						}
					}
					$block = current($noteblock);
					if($block){
						$pk = new BlockEventPacket();
						$pk->x = $block->x;
						$pk->y = $block->y;
						$pk->z = $block->z;
						$pk->eventType = $type;
						$pk->eventData = $sound;
						$p->dataPacket($pk);
						$pk = new LevelSoundEventPacket();
						$pk->sound = LevelSoundEventPacket::SOUND_NOTE;
						/*$pk->x = $block->x;
						$pk->y = $block->y;
						$pk->z = $block->z;*/
						$pk->position = new Vector3($block->x, $block->y, $block->z);
						//$pk->volume = $type; //old
						//$pk->pitch = $sound; //old
						$pk->extraData = $sound; //**new changes**
						//$pk->unknownBool = true; //old
						//$pk->unknownBool2 = true; //old 
						$p->dataPacket($pk);
					}
				}
			}
		}
	}
		
	public function onDisable(){
		$this->getLogger()->info("ZMusicBox Unload Success!");
	}
	
	public function StartNewTask(){
		$this->song = $this->getRandomMusic();
		$this->getScheduler()->cancelAllTasks($this);
		$this->MusicPlayer = new MusicPlayer($this);
		$this->getScheduler()->scheduleRepeatingTask($this->MusicPlayer, 2990 / $this->song);
	}
	
}

class MusicPlayer extends Task{

    public function __construct(ZMusicBox $plugin){
        $this->plugin = $plugin;
    }
	
	public function onRun(int $CT){
		if(isset($this->plugin->song->sounds[$this->plugin->song->tick])){
			$i = 0;
			foreach($this->plugin->song->sounds[$this->plugin->song->tick] as $data){
				$this->plugin->Play($data[0],$data[1],$i);
				$i++;
			}
		}
		$this->plugin->song->tick++;
		if($this->plugin->song->tick > $this->plugin->song->length){
			$this->plugin->StartNewTask();
		}
	}

}
