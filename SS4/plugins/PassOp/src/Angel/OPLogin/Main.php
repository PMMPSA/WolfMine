<?php

namespace Angel\OPLogin;

use pocketmine\Player;
use pocketmine\utils\Config;

class Main extends \pocketmine\plugin\PluginBase implements \pocketmine\event\Listener{

    public $opLogin = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function getPassword(){
        return $this->getConfig()->get("Password");
    }

    public function setPassword($pass = null){
        if(is_null($pass)){
            return false;
        }
        $this->getConfig()->set("Password", $pass);
        $this->getConfig()->save();
    }

    public function onJoin(\pocketmine\event\player\PlayerJoinEvent $ev){
        if($ev->getPlayer()->isOp()){
            $this->opLogin[strtolower($ev->getPlayer()->getName())] = true;
            $ev->getPlayer()->sendMessage("§c(§eThông Báo§c)§b Vui lòng nhập mật khẩu dành riêng cho OP");
        }
    }
    public function onChat(\pocketmine\event\player\PlayerChatEvent $ev){
        $checks = 0;
        $name = strtolower($ev->getPlayer()->getName());
        if(isset($this->opLogin[$name])){
            if($ev->getMessage() == $this->getPassword()){
                unset($this->opLogin[strtolower($ev->getPlayer()->getName())]);
                $ev->setCancelled(true);
                $ev->getPlayer()->sendMessage("§c(§eThông Báo§c)§b Bạn đã nhập đúng");
            } else {
                $ev->getPlayer()->sendMessage("§c(§eThông Báo§c)§b Nhập sai, vui lòng thử lại!");
                $ev->setCancelled(true);
                $checks++;
            }
        }
        if($ev->getMessage() == $this->getPassword()){
            $ev->setCancelled();
        }
    }
    public function onMove(\pocketmine\event\player\PlayerMoveEvent $ev){
        $name = strtolower($ev->getPlayer()->getName());
        if(isset($this->opLogin[$name])){
            $ev->setCancelled(true);
        }
    }
    
    public function onPreprocessCommand(\pocketmine\event\player\PlayerCommandPreprocessEvent $ev){
        $name = strtolower($ev->getPlayer()->getName());
        if($ev->getMessage() !== "Daylacaipassopdaigiongnhupassopcuawolfminemuadautiendidoban" && isset($this->opLogin[$name])){
            $ev->setCancelled(true);
            $ev->getPlayer()->sendMessage("§c(§eThông Báo§c)§b Vui lòng nhập mật khẩu dành riêng cho OP để sử dụng lệnh");
        }
    }
           
    public function onBreak(\pocketmine\event\block\BlockBreakEvent $ev){
        $name = strtolower($ev->getPlayer()->getName());
        if(isset($this->opLogin[$name])){
            $ev->setCancelled(true);
            $ev->getPlayer()->sendMessage("§c(§eThông Báo§c)§b Vui lòng nhập mật khẩu dành riêng cho OP để đập Block");
        }
    }
    public function onPlace(\pocketmine\event\block\BlockPlaceEvent $ev){
        $name = strtolower($ev->getPlayer()->getName());
        if(isset($this->opLogin[$name])){
            $ev->setCancelled(true);
            $ev->getPlayer()->sendMessage("§c(§eThông Báo§c)§b Vui lòng nhập mật khẩu dành riêng cho OP để đặt Block");
        }
    }
}
