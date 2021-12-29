<?php

namespace amgm;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandReader;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use PTK\coinapi\Coin;
use _64FF00\PurePerms\PurePerms;
use vip\mains;
class Main extends PluginBase {
  const CORE_API_HTTP_USER = "32586";
  const CORE_API_HTTP_PWD = "gaNPtx5ire6bNcrYQoNM";
  const BK = "https://www.baokim.vn/the-cao/restFul/send";
  public $prefix = " ";
  public $cfg;
  public $tien;
  public $cap;
  public $rank;
  public $data;
  public $coin;
  
    public function onEnable() {
   
    if(!is_dir($this->getDataFolder())) {
      mkdir($this->getDataFolder());
      }
      
      $this->coin = Coin::getInstance();
      $this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    $this->data = new Config($this->getDataFolder() ."tong_card_mem_nap.yml", Config::YAML, [
    ]);
	
   
   //anh đừng có edit gì hết á em thấy vầy đẹp rồi
    $this->tien = new Config($this->getDataFolder() ."nap_tien.yml", Config::YAML, [
	"10000" => "10000",
    "20000" => "20000",
    "50000" => "50000",
	"100000" => "100000",
	"200000" => "200000",
	"500000" => "500000"
	
    ]);
    
    $this->cap = new Config($this->getDataFolder() ."nap_vip.yml", Config::YAML, [
	"10000" => "V1",
	"20000" => "V2",
    "50000" => "V3",
	"100000" => "V4",
	"200000" => "V5"
    ]);
	
	$this->cfg = new Config($this->getDataFolder() ."cai_dat.yml", Config::YAML, [
    "merchant_id" => "32586",
    "secure_code" => "bacc3558ca40979f",
    "api_username" => "10394167",
    "api_pass" => "gaNPtx5ire6bNcrYQoNM",
    "uuid" => "Jkllkjaiaim"
    ]);
    
	$duongdan = 'plugins/ConfigNapThe/card_dung.log';
	if(file_exists($duongdan)){
          $this->getLogger()->info("§bNạp thẻ đã được Load");
		 
    }else{
    $fh = fopen('plugins\ConfigNapThe\card_dung.log', "a") ;
	fwrite($fh,'| Tai khoan                |     Loai the  |   Menh gia    |       Thoi gian                   |');
	fwrite($fh,"\r\n");
	fclose($fh);
    }
	$duongdan = 'plugins/ConfigNapThe/card_delay.log';
	if(file_exists($duongdan)){
 
		  $this->getLogger()->info(TextFormat::GREEN."§aplugins\ConfigNapThe\card_delay.log §eđã sẵn sàng");
    }else{
    $fh = fopen('plugins\ConfigNapThe\card_delay.log', "a") ;
	fwrite($fh,'| Tai khoan                |     Loai the  |   Menh gia    |       Thoi gian                   |');
	fwrite($fh,"\r\n");
	fclose($fh);
	}
    }
	
    public function onCommand(CommandSender $s, Command $cmd, $label, array $args) {
		
  $merchant_id = $this->cfg->get("merchant_id");
  $api_username = $this->cfg->get("api_username");
  $api_pass = $this->cfg->get("api_pass");
  $uuid = $this->cfg->get("uuid");
  $secure_code = $this->cfg->get("secure_code");
 
  settype($merchant_id, "string");
  settype($api_username, "string");
  settype($api_pass, "string");
  settype($uuid, "string");
  settype($secure_code, "string");
      
      if(strtolower($cmd->getName()) == "card") {
        
        if(isset($args[0])) {
          
          switch(strtolower($args[0])) {
            
            case "🎁🎁":
              
              if(isset($args[1]) && isset($args[2]) && isset($args[3])) {
                
                if(is_numeric($args[1]) && is_numeric($args[2])) {
                  
                  
                  $tranid = time();
                  switch(strtolower($args[3])) {
                    
                    case "vina":
                     $mang = "VINA";
                     break;
                     
                    case "mobi":
                     $mang = "MOBI";
                    break;
                    
                    case "viettel":
                     $mang = "VIETEL";
                    break;
                    
                    case "vtc":
                     $mang = "VTC";
                     break;
                     
                    case "gate":
                     $mang = "GATE";
                     
                    break;
                    }
         settype($mang,"string");
		 settype($tranid,"string");
                  $arrayPost = array(
		'merchant_id'=> $merchant_id,
		'api_username'=> $api_username,
		'api_password'=> $api_pass,
		'transaction_id'=> $tranid,
		'card_id'=> $mang,
		'pin_field'=> $args[1],
		'seri_field'=> $args[2],
		'algo_mode'=>'hmac'
);

$pina = (strtolower($args[1]));
$seria = (strtolower($args[2]));
$mang = (strtolower($args[3]));

$s->sendMessage("§c❇§a⬛§c⬛§b⬛§d◼§a◾▪§e SAO RPG§a ▪◾§d◼§b⬛§c⬛§a⬛§c❇");
$s->sendMessage(" ");
$s->sendMessage("§a▶§e Mã Seri:§a➡§e ".$pina.'');
$s->sendMessage(" ");
$s->sendMessage("§a▶§e Mã Pin:§a➡§e ".$seria.'');
$s->sendMessage(" ");
$s->sendMessage("§a▶§e Mạng:§a➡§e".$mang.'');
$s->sendMessage(" ");
$s->sendMessage("§c❇§a⬛§c⬛§b⬛§d◼§a◾▪§e SAO RPG§a ▪◾§d◼§b⬛§c⬛§a⬛§c❇ ");

ksort($arrayPost);

$data_sign = hash_hmac('SHA1',implode('',$arrayPost),$secure_code);

$arrayPost['data_sign'] = $data_sign;

$curl = curl_init(self::BK);

curl_setopt_array($curl, array(
		CURLOPT_POST=>true,
		CURLOPT_HEADER=>false,
		CURLINFO_HEADER_OUT=>true,
		CURLOPT_TIMEOUT=>30,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH=>CURLAUTH_DIGEST|CURLAUTH_BASIC,
		CURLOPT_USERPWD=> self::CORE_API_HTTP_USER.':'.self::CORE_API_HTTP_PWD,
		CURLOPT_POSTFIELDS=>http_build_query($arrayPost)
));

$data = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$result = json_decode($data,true);
date_default_timezone_set('Asia/Ho_Chi_Minh');
  
     if($status == 200) {
		 
   if($this->tien->exists($result["amount"])) {
     
 
     
     if($this->data->exists(strtolower($s->getName()))) {
       
     $this->data->set(strtolower($s->getName()), $this->data->get(strtolower($s->getName())) + $result["amount"]);
	 $this->data->save();
     } else {
       $this->data->set(strtolower($s->getName()), $result["amount"]);
	   $this->data->save();
       }
     
     $this->coin->addMoney($s->getName(), $this->tien->get($result["amount"]));
     
	   //xu ly loai cardokay
	   if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}

	   
	   
	   $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	   
	   

	$ngay_nap = date("d-m-Y H:i:s");
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	
    
	
     $s->sendMessage("§a✅§e Bạn đã nạp thẻ thành công!! ". $this->tien->get($result["amount"]) ." $!");
	 $test = new Giftcode();
	 $code = $test->generateCode();
	 $s->sendPopup(" §c❇§a⬛§c⬛§b⬛§d◼§a◾▪§e SAO RPG§a ▪◾§d◼§b⬛§c⬛§a⬛§c❇");
     return true;
     
	 } else {
		 //nap the thanh cong nhung ko co menh gia
       $s->sendMessage("§c Bạn đã nạp thẻ nhưng bạn sẽ không nhận được vip vui lòng inbox face Huỳnh Thanh Tùng hoạc Nguyễn Đông Quý bạn nhé!!");
       if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}
	  
	  $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	  
	 
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	   return true;
       
      $this->data->set(strtolower($s->getName()), "§c✖§b Thẻ không có mệnh giá.");
      }
	  
	 } else {
       
       $s->sendPopup("§c⚠§a Giao dịch lỗi");
       $s->sendMessage("§c⚜§e Miêu tả lỗi: ". $result["errorMessage"]);
       return true;
       
       }
	   
          
 
    } else {
      $s->sendMessage("§a♻§e Mã thẻ và Pin phải là số");
      }
    } else {
      $s->sendMessage("§e| §a|§d |§c |§b |§f ⬛⬛⬛§b §l§oSword Craft Online§r§f ⬛⬛⬛§b |§c |§d |§a |§e |");
      $s->sendMessage(" ");
      $s->sendMessage("§a[SAOPC]§b Chào bạn, hôm nay mình sẽ hướng dẫn bạn cách nạp thẻ ↖(^ω^)↗");
      $s->sendMessage(" ");
      $s->sendMessage("§61.§b /card 1 §aseri, pin, mạng");
      $s->sendMessage(" ");
      $s->sendMessage("§62.§b Bạn sẽ nhận được một bảng có đầy đủ thông tin thẻ bạn nạp");
      $s->sendMessage(" ");
      $s->sendMessage("§63.§b Chờ một lúc bạn sẽ nhận được rank kèm theo§e quà nạp lần đầu");
      $s->sendMessage(" ");
      $s->sendMessage(" §2⬛§a⬛§3⬛§b⬛§4⬛§c⬛§5⬛§d⬛§6⬛§7⬛§f♦§7⬛§6⬛§d⬛§5⬛§c⬛§4⬛§b⬛§3⬛§a⬛§2⬛");
      $s->sendMessage("§f Vui lòng không đưa thẻ cho SAOer khác, mọi chuyện đội ngũ SAOV sẽ không chịu trách nhiệm!!");
$s->sendMessage(" §2⬛§a⬛§3⬛§b⬛§4⬛§c⬛§5⬛§d⬛§6⬛§7⬛§f♦§7⬛§6⬛§d⬛§5⬛§c⬛§4⬛§b⬛§3⬛§a⬛§2⬛");
      $s->sendMessage(" ");
      $s->sendMessage("§64.§a Kêu gọi mọi người nạp thẻ để server phát triển nhé (^)o(^)");
      $s->sendMessage(" ");
      $s->sendMessage("§e| §a|§d |§c |§b |§f ⬛⬛⬛§b §l§oSword Craft Online§r§f ⬛⬛⬛§b |§c |§d |§a |§e |");
      $s->sendPopup("§6 Nạp thẻ§e để nhận nhiều ưu đãi hot");
     }
   break;
   
   case "napthe":
              
              if(isset($args[1]) && isset($args[2]) && isset($args[3])) {
                
                if(is_numeric($args[1]) && is_numeric($args[2])) {
                  
               
                  $tranid = time();
                  switch(strtolower($args[3])) {
                    
                    case "vina":
                     $mang = "VINA";
                     break;
                     
                    case "mobi":
                     $mang = "MOBI";
                    break;
                    
                    case "viettel":
                     $mang = "VIETEL";
                    break;
                    
                    case "vtc":
                     $mang = "VTC";
                     break;
                     
                    case "gate":
                     $mang = "GATE";
                     
                    break;
                    }
         settype($mang,"string");
		 settype($tranid,"string");
                  $arrayPost = array(
		'merchant_id'=> $merchant_id,
		'api_username'=> $api_username,
		'api_password'=> $api_pass,
		'transaction_id'=> $tranid,
		'card_id'=> $mang,
		'pin_field'=> $args[1],
		'seri_field'=> $args[2],
		'algo_mode'=>'hmac'
);

$pina = (strtolower($args[1]));
$seria = (strtolower($args[2]));
$mang = (strtolower($args[3]));

$s->sendMessage("§a♦§7 Mã seri: §6".$pina." ");
$s->sendMessage("§a♦§7 Mã pin: §6".$seria." ");
$s->sendMessage("§a♦§7 Mạng: §6".$pina." ");

ksort($arrayPost);

$data_sign = hash_hmac('SHA1',implode('',$arrayPost),$secure_code);

$arrayPost['data_sign'] = $data_sign;

$curl = curl_init(self::BK);

curl_setopt_array($curl, array(
		CURLOPT_POST=>true,
		CURLOPT_HEADER=>false,
		CURLINFO_HEADER_OUT=>true,
		CURLOPT_TIMEOUT=>30,
		CURLOPT_RETURNTRANSFER=>true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPAUTH=>CURLAUTH_DIGEST|CURLAUTH_BASIC,
		CURLOPT_USERPWD=> self::CORE_API_HTTP_USER.':'.self::CORE_API_HTTP_PWD,
		CURLOPT_POSTFIELDS=>http_build_query($arrayPost)
));

$data = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$result = json_decode($data,true);
date_default_timezone_set('Asia/Ho_Chi_Minh');

   
     if($status == 200) {
     if($this->cap->exists($result["amount"])) {
     
	  
     if($this->data->exists(strtolower($s->getName()))) {
       
     $this->data->set(strtolower($s->getName()), $this->data->get(strtolower($s->getName())) + $result["amount"]);
	 $this->data->save();
     } else {
       $this->data->set(strtolower($s->getName()), $result["amount"]);
	   $this->data->save();
       }

	
	   //xu ly loai cardokay
	   if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}
	   else if($result["amount"] == '1000000'){
			$loai_card = "1m";
		}
	   else if($result["amount"] == '1500000'){
			$loai_card = "1m5";
		}
	 
	   //xu ly ngay vip
	   if($result["amount"]=='10000'){
			$ngay ="10";
		}
		if($result["amount"]=='20000'){
			$ngay ="30";
		}
	   else if($result["amount"]=='50000'){
			$ngay = "80";
		}
	   else if($result["amount"]=='100000'){
			$ngay = "140";
		}
	   else if($result["amount"] == '200000'){
			$ngay = "250";
		}
	   else if($result["amount"] == '500000'){
			$ngay = "600";
		}
	   else if($result["amount"] == '1000000'){
			$ngay = "1000";
		}
	   else if($result["amount"] == '1500000'){
			$ngay = "1800";
		}
	 
     #$this->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip V1 '.strtolower($s->getName()).' '.$ngay."");
     #$this->purePerms->setGroup($s, $this->purePerms->getGroup($this->cap->get($result["amount"])));
     $this->coin->addCoin($s->getName(), $this->tien->get($result["amount"]) / 100 * 2);
     $s->sendMessage("§a︻┳デ═—§6 Nạp thẻ thành công, bạn nhận được §b".($this->tien->get($result["amount"]) / 100 * 2)."§l§cWC");
	 $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	 
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_dung.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
     return true;
     
	 } else {
		 //card nap thanh cong nhung ko co menh gia
       $s->sendMessage("§c●§a Nạp thẻ thành công nhưng thẻ không có mệnh giá, vui lòng gửi thông tin này về cho op:");
    $s->sendMessage("§a♦§7 Mã seri: §6".$pina."Delay");
  $s->sendMessage("§a♦§7 Mã pin: §6".$seria." ");
   $s->sendMessage("§a♦§7 Mạng: §6".$pina." ");
       if($result["amount"]=='10000'){
			$loai_card ="10k";
		}
	   else if($result["amount"]=='20000'){
			$loai_card ="20k";
		}
	   else if($result["amount"]=='50000'){
			$loai_card = "50k";
 		}
	   else if($result["amount"]=='100000'){
			$loai_card = "100k";
		}
	   else if($result["amount"] == '200000'){
			$loai_card = "200k";
		}
	   else if($result["amount"] == '500000'){
			$loai_card = "500k";
		}
		   else if($result["amount"] == '1000000'){
			$loai_card = "1m";
		}
	   else if($result["amount"] == '1500000'){
			$loai_card = "1m5";
		}
	  
	  $ngay_nap = date("d-m-Y H:i:s");
	  $length1 = 25 - strlen($s->getName());
	  $length2 = 10 - strlen($mang);
	  $length3= 10 - strlen($loai_card);
	  $length4 = 30 - strlen($ngay_nap);
	  
	 
	$space = ' ';
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,"| ".$s->getName().str_repeat($space,$length1)."|     ".$mang.str_repeat($space,$length2)."|     ".$loai_card.str_repeat($space,$length3)."|     ".$ngay_nap.str_repeat($space,$length4)."|");
	fwrite($fh,"\r\n");
	fclose($fh);
	$fh = fopen('plugins\NapThe\card_delay.log', "a") ;
	fwrite($fh,'------------------------------------------------------------------------------------------------');
	fwrite($fh,"\r\n");
	fclose($fh);
     return true;
	  return true;
       
      $this->data->set(strtolower($s->getName()), "§c●§a Nạp thẻ thành công nhưng thẻ không có mệnh giá, vui lòng gửi thông tin này về cho op:");
      $s->sendMessage("§a♦§7 Mã seri: §6".$pina."Delay");
  $s->sendMessage("§a♦§7 Mã pin: §6".$seria." ");
   $s->sendMessage("§a♦§7 Mạng: §6".$pina." ");
      }
	 } else {
       
       $s->sendMessage("§d❤§a Thẻ lỗi, thông tin lỗi:§b ".$result["errorMessage"]." ");
       return true;
       
       }
	   
          

    } else {
      $s->sendMessage("§b︻┳デ═—§a Coi chừng tui bắn ó, nạp cho đàng hoàng dô coi !");
      }
    } else {
      $s->sendMessage("§a《§7========= §c|§aＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c| §7=========§a》\n§d ❀§c Để nạp thẻ hãy sử dụng lệnh:\n§a ➤§6 §b/card napthe <seri> <pin> <vina,mobi,viettel,gate,vtc>\n§d ✿§x Mệnh giá máy chủ:\n§c ➻§b 10.000VNĐ\n§7 ➠§a 10 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 20.000VNĐ\n§7 ➠§a 20 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 50.000VNĐ\n§7 ➠§a 50 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 100.000VNĐ\n§7 ➠§a 100 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 200.000VNĐ\n§7 ➠§a 200 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 500.000VNĐ\n§7 ➠§a 500 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 1.000.000VNĐ\n§7 ➠§a 1000 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 1.500.000VNĐ\n§7 ➠§a 1500 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§a《§7========= §c|§aＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c| §7=========§a》");      
     }
   break;
   }
 } else {
   $s->sendMessage("§a《§7========= §c|§aＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c| §7=========§a》\n§d ❀§c Để nạp thẻ hãy sử dụng lệnh:\n§a ➤§6 §b/card napthe <seri> <pin> <vina,mobi,viettel,gate,vtc>\n§d ✿§x Mệnh giá máy chủ:\n§c ➻§b 10.000VNĐ\n§7 ➠§a 10 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 20.000VNĐ\n§7 ➠§a 20 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 50.000VNĐ\n§7 ➠§a 50 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 100.000VNĐ\n§7 ➠§a 100 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 200.000VNĐ\n§7 ➠§a 200 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 500.000VNĐ\n§7 ➠§a 500 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 1.000.000VNĐ\n§7 ➠§a 1000 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§c ➻§b 1.500.000VNĐ\n§7 ➠§a 1500 WolfCoin, dùng lệnh §d/muavip <1-8> để mua vip\n§a《§7========= §c|§aＷＯＬＦＭＩＮＥ ＡＣＩＤＩＳＬＡＮＤ§c| §7=========§a》");
   }
  }
   
 }
 
}