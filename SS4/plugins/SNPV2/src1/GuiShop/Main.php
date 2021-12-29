<?php

namespace GuiShop;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\{Item, ItemBlock};
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TF;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\event\server\DataPacketReceiveEvent;
use GuiShop\Modals\elements\{Dropdown, Input, Button, Label, Slider, StepSlider, Toggle};
use GuiShop\Modals\network\{GuiDataPickItemPacket, ModalFormRequestPacket, ModalFormResponsePacket, ServerSettingsRequestPacket, ServerSettingsResponsePacket};
use GuiShop\Modals\windows\{CustomForm, ModalWindow, SimpleForm};
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender, CommandExecutor};
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener {
	
  public $shop;
  public $item;
  //documentation for setting up the items
  /*
  "Item name" => [item_id, item_damage, buy_price, sell_price]
  */
public $Whool = [
    "ICON" => ["Whool",35,14],
    "Len Trắng" => [35,0,5,0],
    "Len Đỏ" => [35,14,5,0],
    "Len Cam" => [35,1,5,0],
    "Len Xanh Lá Cây" => [35,5,5,0],
    "Len Xanh Lá Cây (Đậm)" => [35,13,5,0],
    "Len Vàng" => [35,4,5,0],
    "Len Tím" => [35,2,5,0],
    "Len Xanh Nước Biển" => [35,9,5,0]
  ];
	
  public $Concrete = [
    "ICON" => ["Concrete",236,14],
    "Bê Tông Trắng" => [236,0,5,0],
    "Bê Tông Đỏ" => [236,14,5,0],
    "Bê Tông Cam" => [236,1,5,0],
    "Bê Tông Xanh Lá Cây" => [236,5,5,0],
    "Bê Tông Xanh Lá Cây (Đậm)" => [236,13,5,0],
    "Bê Tông Vàng" => [236,4,5,0],
    "Bê Tông Tím" => [236,2,5,0],
    "Bê Tông Xanh Nước Biển" => [236,9,5,0]
  ];
	
  public $Food = [
    "ICON" => ["Food",364,0],
    "Thịt Bò Nướng" => [364,0,5,0],
    "Thịt Heo Nướng" => [320,0,5,0],
    "Thịt Gà Nướng" => [366,0,25,0]
  ];
	
  public $More = [
    "ICON" => ["More",347,0],
    "Đồng Hồ" => [347,0,10,0],
    "Cánh" => [444,0,20,0],
    "Sách Chưa Biên" => [386,0,20,0],
    "Shulker" => [205,0,30,0],
    "Mắt Rồng" => [368,0,20,0],
  ];

  public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    PacketPool::registerPacket(new GuiDataPickItemPacket());
		PacketPool::registerPacket(new ModalFormRequestPacket());
		PacketPool::registerPacket(new ModalFormResponsePacket());
		PacketPool::registerPacket(new ServerSettingsRequestPacket());
		PacketPool::registerPacket(new ServerSettingsResponsePacket());
    $this->item = [$this->Whool, $this->Concrete, $this->Food, $this->More];
  }
  public function sendMainShop(Player $player){
$money = $this->getServer()->getPluginManager()->getPlugin("DNP")->viewDNP($player->getName());
    $ui = new SimpleForm("§d•§b Số Điểm Nhân Phẩm bạn đang có§e: ".$money);
    foreach($this->item as $category){
      if(isset($category["ICON"])){
        $rawitemdata = $category["ICON"];
        $button = new Button("§d•§e ".$rawitemdata[0]." §d•");
        $button->addImage('url', "http://avengetech.me/items/".$rawitemdata[1]."-".$rawitemdata[2].".png");
        $ui->addButton($button);
      }
    }
    $pk = new ModalFormRequestPacket();
    $pk->formId = 110;
    $pk->formData = json_encode($ui);
    $player->dataPacket($pk);
    return true;
  }
  public function sendShop(Player $player, $id){
  $money = $this->getServer()->getPluginManager()->getPlugin("DNP")->viewDNP($player->getName());
    $ui = new SimpleForm("§d•§b Số Điểm Nhân Phẩm bạn đang có§e: ".$money);
    $ids = -1;
    foreach($this->item as $category){
      $ids++;
      $rawitemdata = $category["ICON"];
      if($ids == $id){
        $name = $rawitemdata[0];
        $data = $this->$name;
        foreach($data as $name => $item){
          if($name != "ICON"){
            $button = new Button("§d• §e".$name." §d•");
            $button->addImage('url', "http://avengetech.me/items/".$item[0]."-".$item[1].".png");
            $ui->addButton($button);
          }
        }
      }
    }
    $pk = new ModalFormRequestPacket();
    $pk->formId = 111;
    $pk->formData = json_encode($ui);
    $player->dataPacket($pk);
    return true;
  }
  public function sendConfirm(Player $player, $id){
    $ids = -1;
    $idi = -1;
    foreach($this->item as $category){
      $ids++;
      $rawitemdata = $category["ICON"];
      if($ids == $this->shop[$player->getName()]){
        $name = $rawitemdata[0];
        $data = $this->$name;
        foreach($data as $name => $item){
          if($name != "ICON"){
            if($idi == $id){
              $this->item[$player->getName()] = $id;
              $iname = $name;
              $cost = $item[2];
              $sell = $item[3];
              break;
            }
          }
          $idi++;
        }
      }
    }
    $money = $this->getServer()->getPluginManager()->getPlugin("DNP")->viewDNP($player->getName());
    $ui = new CustomForm("§d• §bXác Nhận Mua§e: ".$iname);
    $slider = new Slider("§d• §bSố Lượng§e: ",1,64,0);
    $toggle = new Toggle("§l§eBật Để Bán");
    if($sell == 0) $sell = "0";
    $label = new Label("§d• §bSố Điểm Nhân Phẩm bạn đang có§e: ".$money." VNĐ\n\n§d• §bMua với giá§e: ".$cost." Điểm Nhân Phẩm\n\n§d• §bBán với giá: ".$sell." Điểm Nhân Phẩm");
    $ui->addElement($label);
    $ui->addElement($toggle);
    $ui->addElement($slider);
    $pk = new ModalFormRequestPacket();
    $pk->formId = 112;
    $pk->formData = json_encode($ui);
    $player->dataPacket($pk);
    return true;
  }
  public function sell(Player $player, $data, $amount){
    $ids = -1;
    $idi = -1;
    foreach($this->item as $category){
      $ids++;
      $rawitemdata = $category["ICON"];
      if($ids == $this->shop[$player->getName()]){
        $name = $rawitemdata[0];
        $data = $this->$name;
        foreach($data as $name => $item){
          if($name != "ICON"){
            if($idi == $this->item[$player->getName()]){
              $iname = $name;
              $id = $item[0];
              $damage = $item[1];
              $cost = $item[2]*$amount;
              $sell = $item[3]*$amount;
              if($sell == 0){
                $player->sendMessage("(!) §cThis is not sellable!");
                return true;
              }
              if($player->getInventory()->contains(Item::get($id,$damage,$amount))){
                $player->getInventory()->removeItem(Item::get($id,$damage,$amount));
                //EconomyAPI::getInstance()->addMoney($player, $sell);
    $this->getServer()->getPluginManager()->getPlugin("DNP")->addDNP($player->getName(), $sell);
                $player->sendMessage("§d•§b Bạn đã bán §e".$amount." §e".$iname." §bvới giá §e".$sell." §b Nhân Phẩm");
              }else{
                $player->sendMessage("§d•§b Bạn không có đủ §e".$amount." §e".$iname." §bđể bán");
              }
              unset($this->item[$player->getName()]);
              unset($this->shop[$player->getName()]);
              return true;
            }
          }
          $idi++;
        }
      }
    }
    return true;
  }
  public function purchase(Player $player, $data, $amount){
    $ids = -1;
    $idi = -1;
    foreach($this->item as $category){
      $ids++;
      $rawitemdata = $category["ICON"];
      if($ids == $this->shop[$player->getName()]){
        $name = $rawitemdata[0];
        $data = $this->$name;
        foreach($data as $name => $item){
          if($name != "ICON"){
            if($idi == $this->item[$player->getName()]){
              $iname = $name;
              $id = $item[0];
              $damage = $item[1];
              $cost = $item[2]*$amount;
              $sell = $item[3]*$amount;
              if($this->getServer()->getPluginManager()->getPlugin("DNP")->viewDNP($player->getName()) > $cost){
                $player->getInventory()->addItem(Item::get($id,$damage,$amount));
			  $this->getServer()->getPluginManager()->getPlugin("DNP")->reduceDNP($player->getName(), $cost);
                $player->sendMessage("§d• §bBạn đã mua §e".$amount." §e".$iname." §bvới giá §e".$cost." §bĐiểm Nhân Phẩm");
              }else{
                $player->sendMessage("§d• §bBạn không có đủ điểm Nhân Phẩm để mua §e".$amount." §e".$iname);
              }
              unset($this->item[$player->getName()]);
              unset($this->shop[$player->getName()]);
              return true;
            }
          }
          $idi++;
        }
      }
    }
    return true;
  }
  public function DataPacketReceiveEvent(DataPacketReceiveEvent $event){
    $packet = $event->getPacket();
    $player = $event->getPlayer();
    if($packet instanceof ModalFormResponsePacket){
      $id = $packet->formId;
      $data = $packet->formData;
      $data = json_decode($data);
      if($data === Null) return true;
      if($id === 110){
        $this->shop[$player->getName()] = $data;
        $this->sendShop($player, $data);
        return true;
      }
      if($id === 111){
        //$this->shop[$player->getName()] = $data;
        $this->sendConfirm($player, $data);
        return true;
      }
      if($id === 112){
        $selling = $data[1];
        $amount = $data[2];
        if($selling){
          $this->sell($player, $data, $amount);
          return true;
        }
        $this->purchase($player, $data, $amount);
        return true;
      }
    }
    return true;
  }
  public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool{
    switch(strtolower($command)){
      case "np-shop":
        $this->sendMainShop($player);
        return true;
    }
  }
}
