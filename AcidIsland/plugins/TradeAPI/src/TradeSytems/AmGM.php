<?php

namespace TradeSytems;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\Inventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use onebone\economyapi\EconomyAPI;
use PTK\coinapi\Coin;
use AmGM\LVTM\Mine;

class AmGM extends PluginBase implements Listener{
public $coin;
public $eco;
public function onCommand(CommandSender $s, Command $cmd, $label, array $args){
$this->coin = Coin::getInstance();
$this->eco = EconomyAPI::getInstance();
$this->level = $this->getServer()->getPluginManager()->getPlugin("LevelToMine");
if($cmd->getName() == "trade"){
 if(isset($args[0])){
  switch(strtolower($args[0])){
   case "wc":
    if($this->eco->reduceMoney($s->getName(), $args[1] * 1000000)){
    $this->eco->reduceMoney($s->getName(), $args[1] * 1000000);
   $this->coin->addCoin($s->getName(), $args[1]);
   $s->sendMessage("§c❤§a Bạn đã đổi thành công §c$args[1]§b WolfCoin §abằng §c".($args[1] * 1000000)." Xu");
   }else{
    $s->sendMessage("§cBạn không có đủ tiền để đổi WolfCoin");
 }
       break;
   return true;
 case "help":
  $s->sendMessage("§a<§7========§c۝§6ＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c۝§7========§a>\n§b➲§c Để trade đồ hãy sử dụng: §a/trade <tên>\n§c➸§b Danh sách các món hàng có thể Trade:\n§c✾§a WolfCoin §d[wc]: §b1.000.000Xu/1 WolfCoin\n§c✾§a Xu §d[xu]: §b1 WolfCoin/700xu\n§c✾§a Kiếm SouldBlader §d[ksb]: §b1 Item/100 Diamond Emtyploder\n§c✾§a Cúp SouldBlader §d[csb]: §b1 Item/100 Diamond Emtyploder\n§c✾§a Rìu SouldBlader §d[rsb]: §b1 Item/100 Diamond Emtyploder\n§c✾§a Xẻng SouldBlader §d[xsb]: §b1 Item/100 Diamond Emtyploder\n§c✾§a Diamond Emtyploder §d[dem]: §b1 Item/10 Emerald Emerald\n§c✾§a SourceLive Emerald §d[se]:§b 1 Item/20 Gold DenDieDlo\n§c✾§a Gold DenDieDlo §d[gddd]: §b1 Item/5 Diamond Emtyploder\n§c✾§a Kiếm Lãnh Độ §d[kld]: §b1 Item/30 Gold DenDieDlo, 5 Diamond Emtyploder\n§c✾§a Set DissTionBlo §d[sdtb]: §b1 Set/150 Diamond Toursidal\n§c✾§a Diamond Toursidal §d[dt]:§b 1 Item/30 Khối Đất\n§b↺§a Mọi thứ sẽ được cập nhật thêm....\n§a<§7========§c۝§6ＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c۝§7========§a>");
  break;
  return true;
 case "xu":
    if($this->coin->reduceCoin($s->getName(), $args[1] / 2)){
    $this->coin->reduceCoin($s->getName(), $args[1] / 2);
   $this->eco->addMoney($s->getName(), $args[1] * 700);
   //$this->level->getCurrentLevel($s->getName()) - 1;
   $s->sendMessage("§c❤§a Bạn đã đổi thành công §c".($args[1] * 300)." §bXu §abằng §c$args[1] WolfCoin");
   }else{
    $s->sendMessage("§cBạn không có WolfCoin để đổi xu");
 }
break;
return true;
 case "dt":
 if($s->getInventory()->contains(Item::get(3,0,30))){
 $s->getInventory()->removeItem(Item::get(3,0,30));
 $dt = Item::get(248,3,1);
 $dt->setCustomName("§r§l§dVật phẩm không gian:§e Diamond Toursidal");
 $s->getInventory()->addItem($dt);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Diamond Toursidal");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d Khối đất x30 ");
 }
 break;
 return true;
 case "sdtb":
 if($s->getInventory()->contains(Item::get(248,3,150))){
 $s->getInventory()->removeItem(Item::get(248,3,150));
 $sdtb = Item::get(306,0,1);
 $sdtb1 = Item::get(307,0,1);
 $sdtb2 = Item::get(308,0,1);
 $sdtb3 = Item::get(309,0,1);
$sdtb->setCustomName("§r§l§a>>§c ❃§d Mũ DissTionBlo§c ❃§a <<");
$sdtb1->setCustomName("§r§l§a>>§c ❃§d Giáp DissTionBlo§c ❃§a <<");
$sdtb2->setCustomName("§r§l§a>>§c ❃§d Quần DissTionBlo§c ❃§a <<");
$sdtb3->setCustomName("§r§l§a>>§c ❃§d Ủng DissTionBlo§c ❃§a <<");
$sdtb->addEnchantment(Enchantment::getEnchantment(0)->setLevel(2));
$sdtb1->addEnchantment(Enchantment::getEnchantment(0)->setLevel(2));
$sdtb2->addEnchantment(Enchantment::getEnchantment(0)->setLevel(2));
$sdtb3->addEnchantment(Enchantment::getEnchantment(0)->setLevel(2));
$sdtb->addEnchantment(Enchantment::getEnchantment(1)->setLevel(3));
$sdtb1->addEnchantment(Enchantment::getEnchantment(1)->setLevel(3));
$sdtb2->addEnchantment(Enchantment::getEnchantment(1)->setLevel(3));
$sdtb3->addEnchantment(Enchantment::getEnchantment(1)->setLevel(3));
$sdtb->addEnchantment(Enchantment::getEnchantment(2)->setLevel(2));
$sdtb1->addEnchantment(Enchantment::getEnchantment(2)->setLevel(2));
$sdtb2->addEnchantment(Enchantment::getEnchantment(2)->setLevel(2));
$sdtb3->addEnchantment(Enchantment::getEnchantment(2)->setLevel(2));
$sdtb->addEnchantment(Enchantment::getEnchantment(3)->setLevel(2));
$sdtb1->addEnchantment(Enchantment::getEnchantment(3)->setLevel(2));
$sdtb2->addEnchantment(Enchantment::getEnchantment(3)->setLevel(2));
$sdtb3->addEnchantment(Enchantment::getEnchantment(3)->setLevel(2));
$sdtb->addEnchantment(Enchantment::getEnchantment(4)->setLevel(3));
$sdtb1->addEnchantment(Enchantment::getEnchantment(4)->setLevel(3));
$sdtb2->addEnchantment(Enchantment::getEnchantment(4)->setLevel(3));
$sdtb3->addEnchantment(Enchantment::getEnchantment(4)->setLevel(3));
$sdtb->addEnchantment(Enchantment::getEnchantment(5)->setLevel(3));
$sdtb1->addEnchantment(Enchantment::getEnchantment(5)->setLevel(3));
$sdtb2->addEnchantment(Enchantment::getEnchantment(5)->setLevel(3));
$sdtb3->addEnchantment(Enchantment::getEnchantment(5)->setLevel(3));
$s->getInventory()->addItem($sdtb);
$s->getInventory()->addItem($sdtb1);
$s->getInventory()->addItem($sdtb2);
$s->getInventory()->addItem($sdtb3);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Set DissTionBlo");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 150 Diamond Toursidal");
 }
 break;
 return true;
 case "kld":
 date_default_timezone_set("Asia/Ho_Chi_Minh");
  if($s->getInventory()->contains(Item::get(248,1,30))){
 $s->getInventory()->contains(Item::get(248,1,30));
  $s->getInventory()->contains(Item::get(248,2,3));
 $s->getInventory()->contains(Item::get(248,2,3));
 $kld = Item::get(268,0,1);
 $kld->setCustomName("§r§l§a❦§c ⇕§b Kiếm Lãnh Độ §c⇕§a ❦\n§r§dLevel của bạn vào §e".date("d-m-Y")." §alúc §d".date("H")."h §clà §b".$this->level->getCurrentLevel($s->getPlayer())."§c nên bạn được cộng §a".$this->level->getCurrentLevel($s->getPlayer())."§c vào level enchant");
 $kld->addEnchantment(Enchantment::getEnchantment(9)->setLevel(1 + $this->level->getCurrentLevel($s->getPlayer())));
 $kld->addEnchantment(Enchantment::getEnchantment(17)->setLevel(2 + $this->level->getCurrentLevel($s->getPlayer())));
 $s->getInventory()->addItem($kld);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Kiếm Lãnh Độ");
 }else{
  $s->sendMessage("§c❂§b Không có đủ đồ để trade ");
  }
   break;
   return true;
   case "🐷":
   $this->level->getCurrentLevel($s->getPlayer()) + $args[1];
   $s->sendMessage("§c•§a Level của bạn đã được nâng lên §b$args[1]");
   break;
   return true;
   case "ì":
   $s->sendMessage("§a♦§b<<§6=============§c❖§bTiểu Sử WolfMine§c❖§6=============§b>>§a♦\n§c• §bThành lập vào năm §a2016 với lãnh đạo là:
§bGamerSoiCon\n§c•§d Thời điểm phồn thịnh nhất: §b10/2017\n§c➤§a Đội ngũ Developer: §cAmGM, GamerSoiCoin\n§c✿§e Đã từng là một tượng đài khá lâu nhưng!\n§a➠§c Lục đục nội bộ, Staff phản và §bAmGM§c đã ra đi\n§c❥§d Khôi phục Server ngày: §a1/2/2018\n§c❃§a Cảm ơn bạn đã đồng hành với chúng tôi§6\n§a♦§b<<§6=============§c❖§bTiểu Sử WolfMine§c❖§6=============§b>>§a♦");
break;
return true;
case "gddd":
 if($s->getInventory()->contains(Item::get(248,2,3))){
 $s->getInventory()->removeItem(Item::get(248,2,3));
 $g = Item::get(248,1,1);
 $g->setCustomName("§r§l§dVật phẩm không gian:§e Gold DenDieDlo");
 $s->getInventory()->addItem($g);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Gold DenDieDlo");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d Diamond Emtyploder x5 ");
 }
 break;
 return true;
 case "se":
 if($s->getInventory()->contains(Item::get(248,3,90))){
 $s->getInventory()->removeItem(Item::get(248,3,90));
 $se = Item::get(248,4,1);
 $se->setCustomName("§r§l§dVật phẩm không gian:§e SourceLive Emerald");
 $s->getInventory()->addItem($se);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d SourceLive Emerald");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 90 Diamond Toursidal");
 }
 break;
 return true;
 case "dem":
 if($s->getInventory()->contains(Item::get(388,0,10))){
 $s->getInventory()->contains(Item::get(388,0,10));
  $de = Item::get(248,2,1);
 $de->setCustomName("§r§l§dVật phẩm không gian:§e Diamond Emtyploder");
 $s->getInventory()->addItem($de);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Diamond Emtyploder");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 10 Emerald");
 }
 break;
 return true;
 case "ksb":
 if($s->getInventory()->contains(Item::get(248,2,100))){
 $s->getInventory()->removeItem(Item::get(248,2,100));
 $ksb = Item::get(276,0,1);
 $ksb->setCustomName("§r§c✿§f Kiếm SouldBlader §c ✿");
 $ksb->addEnchantment(Enchantment::getEnchantment(9)->setLevel(2));
 $ksb->addEnchantment(Enchantment::getEnchantment(12)->setLevel(2));
 $s->getInventory()->addItem($ksb);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Kiếm SouldBlader");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 100 Diamond Emtyploder");
 }
 break;
 return true;
 case "csb":
 if($s->getInventory()->contains(Item::get(248,2,100))){
 $s->getInventory()->removeItem(Item::get(248,2,100));
 $csb = Item::get(278,0,1);
 $csb->setCustomName("§r§c✿§f Cúp SouldBlader §c ✿");
 $csb->addEnchantment(Enchantment::getEnchantment(15)->setLevel(2));
 $csb->addEnchantment(Enchantment::getEnchantment(17)->setLevel(2));
 $s->getInventory()->addItem($csb);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Cúp SouldBlader");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 100 Diamond Emtyploder");
 }
 break;
 return true;
 case "rsb":
 if($s->getInventory()->contains(Item::get(248,2,100))){
 $s->getInventory()->removeItem(Item::get(248,2,100));
 $rsb = Item::get(279,0,1);
 $rsb->setCustomName("§r§c✿§f Rìu SouldBlader §c ✿");
 $rsb->addEnchantment(Enchantment::getEnchantment(15)->setLevel(2));
 $rsb->addEnchantment(Enchantment::getEnchantment(17)->setLevel(2));
 $s->getInventory()->addItem($rsb);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Rìu SouldBlader");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 100 Diamond Emtyploder");
 }
 break;
 return true;
  case "xsb":
 if($s->getInventory()->contains(Item::get(248,2,100))){
 $s->getInventory()->removeItem(Item::get(248,2,100));
 $rsb = Item::get(277,0,1);
 $rsb->setCustomName("§r§c✿§f Xẻng SouldBlader §c ✿");
 $rsb->addEnchantment(Enchantment::getEnchantment(15)->setLevel(2));
 $rsb->addEnchantment(Enchantment::getEnchantment(17)->setLevel(2));
 $s->getInventory()->addItem($rsb);
 $s->sendMessage("§c✵§b Đổi thành công §a>>§d Xẻng SouldBlader");
 }else{
 $s->sendMessage("§c❂§b Không có đủ §a>>§d 100 Diamond Emtyploder");
 }
 break;
 return true;
 case "ò":
 $s->sendMessage("§a-§7|§a=/_§7][§cB U Y E N C H A N T§7][§a_\=§7|§a-\n§6➼§a Hệ thống enchant vật phẩm:\n§6➼§a /muaec <id> <1-5>\n§c❃§e Giá trị:\n§a❂§b Level 1: §d1.000Xu\n§a❂§b Level 2: §d2.000Xu\n§a❂§b Level 3: §d3.000Xu\n§a❂§b Level 4: §d40WC\n§a❂§b Level 5: §d50WC");
   }
   }
   }
   }
   }
   //}
   //}
# 《 》 « » ⇨ ⇒ ⇔ ⇚ ⇶ ⇵ ⇴ ⇳ ⇰ ⇯ ⇮ ⇭ ⇬ ⇫ ⇩ ⇨ ⇧ ⇦ ↻ ↺ ↨ ↧ ↦ ↥ ↤ ↣ ↢ ↡ ↠ ↟ ↞ ↝ ↜ ↛ ↚ ↙ ↘ ↗ ↖ ← ↑ → ↓ ↔ ↕ ↖ ↗ ↘ ↙ ↤ ↥ ↦ ↧ ↨ ↸ ↹ ↮ ⇤ ⇥ ⇲ ⇞ ⇟ ↩ ↪ ↫ ↬ ⇝ ↰ ↱ ↲ ↳ ↴ ↵ ↯ ↷ ↺ ↻ ⇜ ↶ ↼ ↽ ↾ ↿ ⇀ ⇁ ⇂ ⇃ ⇄ ⇅ ⇆ ⇇ ⇈ ⇉ ⇊ ⇍ ⇎ ⇏ ⇐ ⇑ ⇒ ⇓ ⇔ ⇕ ⇖ ⇗ ⇘ ⇙ ⇦ ⇧ ⇪ ⇫ ➔ ➙ ➘ ➚ ➛ ➜ ➞ ➟ ➠ ➡ ➢ ➣ ➤ ➥ ➦ ➶ ➵ ➳ ➴ ➲ ➱ ➯ ➾ ➽ ➭ ➬ ➼ ➻ ➫ ➪ ➺ ➹ ➩ ➨ ➸ ➷ ➧ ⃕ ⪡ ⨣ ▶ ▷ ◀ ◁ ▬
# ✲ ⋆ ❄ ❅ ❇ ❈ ❖ ✫ ✪ ✩ ✬ ✮ ✭ ✯ ✰ ✹ ✸ ✷ ✶ ✵ ✳ ✱ ❊ ❉ ✾ ✽ ✼ ✠ ☆ ★ ✡ ✴ ✺ ☼ ☸ ❋ ✽ ✻ ❆ ۞ ۝ ☀ ❃ ❂ ✿ ❀ ❁
# 💕 ❤ ❥ ♥ ♡ ♥ ❣ ❦ ❧ ღ ɞ 💖
#§a<§7========§c۝§6ＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c۝§7========§a>
#§b➲§c Để trade đồ hãy sử dụng: §a/trade <tên>
#§c➸§b Danh sách các món hàng có thể Trade:
#§c✾§a WolfCoin §d[wc]: §b1.000.000Xu/1 WolfCoin
#§c✾§a Xu §d[xu]: §b1 WolfCoin/300xu
#§c✾§a Kiếm SouldBlader §d[ksb]: §b1 Item/100 Diamond Emtyploder
#§c✾§a Cúp SouldBlader §d[csb]: §b1 Item/100 Diamond Emtyploder
#§c✾§a Rìu SouldBlader §d[rsb]: §b1 Item/100 Diamond Emtyploder
#§c✾§a Xẻng SouldBlader §d[xsb]: §b1 Item/100 Diamond Emtyploder
#§c✾§a Diamond Emtyploder §d[dem]: §b1 Item/10 SourceLive Emerald
#§c✾§a SourceLive Emerald [se]:§b 1 Item/90 Diamond Toursidal
#§c✾§a Gold DenDieDlo §d[gddd]: §b1 Item/5 Diamond Emtyploder
#§c✾§a Kiếm Lãnh Độ §d[kld]: §b1 Item/30 Gold DenDieDlo, 5 Diamond #Emtyploder
#§c✾§a Set DissTionBlo §d[sdtb]: §b1 Set/90 Level
#§c✾§a Diamond Toursidal §d[dt]:§b 1 Item/8 Gold DenDieDlo
#§b↺§a Mọi thứ sẽ được cập nhật thêm....
#§a<§7========§c۝§6ＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c۝§7========§a>
 #Diamond Toursidal = 248:3
 #Diamond Emtyploder = 248:2
 #Gold DenDieDlo = 248:1
 #SourceLive Emerald = 248:4
#§a-§7|§a=/_§7][§cB U Y E N C H A N T§7][§a_\=§7|§a-\n§6➼§a Hệ thống enchant vật phẩm:\n§6➼§a /buyec <id> <1-5>\n§c❃§e Giá trị:\n§a❂§b Level 1: §d1.000Xu\n§a❂§b Level 2: §d2.000Xu\n§a❂§b Level 3: §d3.000Xu\n§a❂§b Level 4: §d40WC\n§a❂§b Level 5: §d50WC