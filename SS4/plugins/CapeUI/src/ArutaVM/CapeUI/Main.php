<?php

declare(strict_types=1);

namespace ArutaVM\CapeUI;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Skin;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\{
	Command, CommandSender
};
use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use jojoe77777\FormAPI;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\player\{
	PlayerJoinEvent, PlayerQuitEvent, PlayerChangeSkinEvent
};

class Main extends PluginBase implements Listener {

    protected $skin = [];
    public $skins;
    /** @var string */
    public $noperm = C::AQUA . "§c✿§a§l CapeUI§c ✿§a§l §cBạn không có quyền sử dụng Cape!";

    /**
     * @return void
     */

    public function onEnable() {
        $this->saveResource("capes.yml");
        $this->cfg = new Config($this->getDataFolder() . "capes.yml", Config::YAML);
        foreach ($this->cfg->get("capes") as $cape) {
            $this->saveResource("$cape.png");
        }
    }

	public function onJoin(PlayerJoinEvent $eve) {
		$sender = $eve->getPlayer();
		$this->skin[$sender->getName()] = $sender->getSkin();
	}

	public function onChangeSkin(PlayerChangeSkinEvent $eve) {
		$sender = $eve->getPlayer();
		$this->skin[$sender->getName()] = $sender->getSkin();
	}
	
       public function createCape($capeName) {
            $path = $this->getDataFolder()."{$capeName}.png";

            $img = @imagecreatefrompng($path);

            $bytes = '';

            $l = (int) @getimagesize($path)[1];

            for ($y = 0; $y < $l; $y++) {

                for ($x = 0; $x < 64; $x++) {

                    $rgba = @imagecolorat($img, $x, $y);

                    $a = ((~((int)($rgba >> 24))) << 1) & 0xff;

                    $r = ($rgba >> 16) & 0xff;

                    $g = ($rgba >> 8) & 0xff;

                    $b = $rgba & 0xff;

                    $bytes .= chr($r) . chr($g) . chr($b) . chr($a);

                }

            }

        @imagedestroy($img);
        return $bytes;
    }
        
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        $this->cfg = new Config($this->getDataFolder() . "capes.yml", Config::YAML);
        $cape = $this->cfg->get("capes");
        switch (strtolower($command->getName())) {
            case "cape":
                if ($sender instanceof Player) {
                    if (!isset($args[0])) {
                        if (!$sender->hasPermission("cape.cmd")) {
                            $sender->sendMessage($this->noperm);
                            return true;
                        } else {
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null) {
				}
				switch ($result) {
                    case 0:
                        $command = "cape abort";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                    case 1:
                    $command = "cape remove";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
						break;
						           case 2:
                    $command = "cape blue_creeper";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                   case 3:
                    $command = "cape enderman";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                   case 4:
                    $command = "cape energy";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                   case 5:
                    $command = "cape fire";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
						break;
						           case 6:
                    $command = "cape red_creeper";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                   case 7:
                    $command = "cape turtle";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                   /*case 8:
                    $command = "cape pickaxe";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;*/
                                  case 8:
                    $command = "cape firework";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                  case 9:
                    $command = "cape iron_golem";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
								  /*case 11:
                    $command = "cape king";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;*/
									case 10:
                    $command = "cape fire_dragon";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
             }
             });
        $form->setTitle("§b★ CapesUI§f Menu§b ★");
        $form->setContent("§f✿§a Hãy ấn vào Cape để sử dụng nó! §f✿");
        $form->addButton("§c★ Đóng", 0);
        $form->addButton("§c★ Xóa cape của bạn", 1);
        $form->addButton("§b★ Blue Creeper Cape", 2);
        $form->addButton("§a★ Ender Man Cape", 3);
        $form->addButton("§e★ Energy Cape", 4);
        $form->addButton("§4★ Fire Cape", 5);
        $form->addButton("§e★ Red Creeper Cape", 6);
        $form->addButton("§a★ Turtle Cape", 7);
        /*$form->addButton("§b★ Pickaxe Cape", 8);*/
        $form->addButton("§e★ Firework Cape", 8);
        $form->addButton("§f★ Iron Golem Cape", 9);
		/*$form->addButton("§e★ King Cape", 11);*/
		$form->addButton("§4★ Fire Dragon Cape", 10);
        $form->sendToPlayer($sender);
        }
        return true;
                    }
                    $arg = array_shift($args);
                    switch ($arg) {
                        case "abort":
                            return true;
                            break;
                        case "remove":
        $oldSkin = $sender->getSkin();
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), "", $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
        $sender->sendSkin();
                            $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aSkin đã được xóa bỏ!");
                            return true;
                        case "blue_creeper":
                            if (!$sender->hasPermission("blue_creeper.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Blue_Creeper");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aBlue Creeper Cape đã được kích hoạt!");
                         }
                            return true;
                        case "enderman":
                            if (!$sender->hasPermission("enderman.cape")) {
                                $sender->sendMessage($this->noperm);
                            return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Enderman");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aEnderman Cape đã được kích hoạt!");
                return true;
                            }
                        case "energy":
                            if (!$sender->hasPermission("energy.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                         } else {
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Energy");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aEnergy Cape đã được kích hoạt!");
                return true;
                         }
                        case "fire":
                            if (!$sender->hasPermission("fire.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Fire");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aFire Cape đã được kích hoạt!");
                return true;
                            }
                        case "red_creeper":
                            if (!$sender->hasPermission("red_creeper.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            } else {
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Red_Creeper");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aRed Creeper Cape đã được kích hoạt!");
                            return true;
                            }
                            case "turtle":
                            if (!$sender->hasPermission("turtle.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Turtle");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aTurtle Cape đã được kích hoạt!");
                            return true;
                            }
                        /*case "pickaxe":
                            if (!$sender->hasPermission("pickaxe.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Pickaxe");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aPickaxe Cape đã được kích hoạt!");
                            return true;
                            }*/
                        case "firework":
                            if (!$sender->hasPermission("firework.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Firework");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aFirework Cape đã được kích hoạt!");
                            return true;
                            }
                       case "iron_golem":
                            if (!$sender->hasPermission("iron_golem.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Iron_Golem");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §aIron Golem Cape đã được kích hoạt!");
                            return true;
                            }
                      /*case "king":
                            if (!$sender->hasPermission("king.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("King");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §eKing Cape đã được kích hoạt!");
                            return true;
		                    }*/
							case "fire_dragon":
                            if (!$sender->hasPermission("fire_dragon.cape")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }else{
        $oldSkin = $sender->getSkin();
        $capeData = $this->createCape("Fire_Dragon");
        $setCape = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $capeData, $oldSkin->getGeometryName(), $oldSkin->getGeometryData());
        $sender->setSkin($setCape);
                $sender->sendSkin();
                $sender->sendMessage("§c✿§a§l CapeUI§c ✿§a§l §4Fire Dragon Cape đã được kích hoạt!");
                            return true;
							}
  }
        }
        }
  return true;
}
}