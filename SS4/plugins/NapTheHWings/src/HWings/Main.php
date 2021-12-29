<?php

namespace HWings;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\event\Listener;
use joejoe77777\FormAPI;

Class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("\n\n§l§cDonate [Auto] has been enabled \n\n");
		/*Plugin Coin khi nạp (tùy bạn)*/
		$this->vang = $this->getServer()->getPluginManager()->getPlugin("VANG");
		$this->tn = $this->getServer()->getPluginManager()->getPlugin("TichNap");
		$trans_id = time();  //mã giao dịch do bạn gửi lên, Napthengay.com sẽ trả về 
	}

	public function onCommand(CommandSender $sender, Command $cmd,string $label, array $args):bool{
		if($cmd->getName() == "muavang"){
			if(!(isset($args[0]) || isset($args[1]) || isset($args[2]) || isset($args[3]))){
			//Bảng help
				$sender->sendMessage("§d===§b•§e HỆ THỐNG MUA VÀNG §b•§d===\n§d•§b /muavang <§eSố PIN§b> <§eSố Serial§b> <§eGiá Trị: 10000, 20000, 50000, 100000, 200000, 500000, 1000000§b> <§eNhà Mạng: 1 - Viettel, 2 - Mobifone, 3 - Vinaphone§b>\n§d•§b Ví dụ§e: /muavang 9301738291038 04928174829 20000 1\n§d•§l§c Thực hiện sai dẫn đến mất thẻ, OP sẽ không chịu trách nhiệm!\n§d===§b•§e HỆ THỐNG MUA VÀNG §b•§d===
\n§d===§b•§e BẢNG GIÁ MUA VÀNG §b•§d===\n§d•§b 10.000đ = §e20 Vàng\n§d•§b 20.000đ = §e40 Vàng\n§d•§b 50.000đ = §e100 Vàng\n§d•§b 100.000đ = §e200 Vàng\n§d•§b 200.000đ = §e400 Vàng\n§d•§b 500.000đ = §e1.000 Vàng\n§d•§b 1.000.000đ = §e2.000 Vàng\n§l§c• Máy Chủ hiện đang khuyến mãi 30% cho mọi mệnh giá§r\n§d===§b•§e BẢNG GIÁ MUA VÀNG §b•§d===");
				return true;
			}
			if(!(is_numeric($args[0]) || is_numeric($args[1]) || is_numeric($args[2]) || is_numeric($args[3]))){
			//cảnh báo khi seri + pin + giá trị k phải là số
			$sender->sendMessage("§d•§e Số Serial/Số PIN/Giá Trị/Loại Mạng mà Cư Dân vừa nhập không phải là số, hãy thử lại!");
			return true;
			}
			if(!($args[2] == 10000 || $args[2] == 20000 || $args[2] == 50000 || $args[2] == 100000 || $args[2] == 200000 || $args[2] == 500000 || $args[2] == 1000000)){
			$sender->sendMessage("§d•§e Giá Trị mà Cư Dân vừa nhập hiện Thành Phố chưa hỗ trợ, hãy thử lại!");
			return true;
			}
			if($args[3] > 3 || $args[3] < 1){
			$sender->sendMessage("§d•§b Loại Nhà Mạng mà Cư Dân vừa nhập Thành Phố chưa hỗ trợ, hãy thử lại!");
			return true;
			}
			switch($args[3]){
				case "1":
				$ten = "Viettel";
					$mang = 1;
				break;
				case "2":
				$ten = "Mobifone";
					$mang = 2;
				break;
				case "3":
             $ten = "Vinaphone";
					$mang = 3;
			}
             //ID khi đăng ký trên napthengay.com
			$merchant_id = "4003";
			//email đăng ký trên đó
$api_email = "duonggiagia09@gmail.com";
//key khi đăng ký
$secure_code = "9013857f374f02cf607c1d4eea36a7b0";
$api_url = "http://api.napthengay.com/v2/";
$trans_id = time();
				$seri = $args[1];
                $sopin = $args[0];
                $card_value = $args[2];
                $mang = $mang;
                $user = $sender->getName();
			$arrayPost = array(
	"merchant_id"=>intval($merchant_id),
	"api_email"=>trim($api_email),
	"trans_id"=>trim($trans_id),
	"card_id"=>trim($mang),
	"card_value"=> intval($card_value),
	"pin_field"=>trim($sopin),
	"seri_field"=>trim($seri),
	"algo_mode"=>"hmac"
);
$data_sign = hash_hmac("SHA1",implode("",$arrayPost),$secure_code);

$arrayPost["data_sign"] = $data_sign;

$curl = curl_init($api_url);

curl_setopt_array($curl, array(
	CURLOPT_POST=>true,
	CURLOPT_HEADER=>false,
	CURLINFO_HEADER_OUT=>true,
	CURLOPT_TIMEOUT=>120,
	CURLOPT_RETURNTRANSFER=>true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_POSTFIELDS=>http_build_query($arrayPost)
));

$data = curl_exec($curl);

$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$result = json_decode($data, true);

$time = time();

if($status==200){
    $amount = $result["amount"];
	switch($amount) {
		//cái zcoin là loại coin bạb đã khai báo ở đầu
		case 10000: $vang = 20; $km = $vang * 0.3; $nhan = $km + $vang; break;
		case 20000: $vang = 40; $km = $vang * 0.3; $nhan = $km + $vang; break;
		case 50000: $vang = 100; $km = $vang * 0.3; $nhan = $km + $vang; break;
		case 100000: $vang = 200; $km = $vang * 0.3; $nhan = $km + $vang; break;
		case 200000: $vang = 400; $km = $vang * 0.3; $nhan = $km + $vang; break;
		case 500000: $vang = 1000; $km = $vang * 0.3; $nhan = $km + $vang; break;
		case 1000000: $vang = 2000; $km = $vang * 0.3; $nhan = $km + $vang; break;
		//nếu muốn thêm % khuyến mãi như này:
		/*
		$zcoin = 10;
		$nhan = $zcoin*1.5 hoặc 2
		1.5 là kmãi 50%
		2 là kmãi 100%
		10k = 20
20 = 40
50 = 100
100 = 200
200 = 400
500 = 1000
		*/

		
		
	}
				
	if($result["code"] == 100){
		/*$dbhost="localhost";
		$dbuser="CDN";
		$dbpass="ahjhj123";
		$dbname="DonateData";
		$db = mysql_connect($dbhost,$dbuser,$dbpass) or die("cant connect db");
		mysql_select_db($dbname,$db) or die("cant select db");
		mysql_query("UPDATE users SET coins = coins + ".$zcoin." WHERE login =".$user.";");*/
		// Xu ly thong tin tai day
		$file = "carddung.log";
		$fh = fopen($file,"a") or die("cant open file");
		fwrite($fh,"Tai khoan: ".$user.", Loai the: ".$ten.", Menh gia: ".$amount.", Thoi gian: ".$time);
		fwrite($fh,"\r\n");
		fclose($fh);
		//lời nhắn khi nạp hành công
		$this->vang->addVANG($sender->getName(), $nhan);
		$this->tn->addTichNap($sender->getName(), $args[2]);
		$sender->sendMessage("§d•§b Chúc mừng Cư Dân đã mua thành công§e ".$vang." Vàng§b, khuyến mãi §e30%§b thành §e".$nhan." Vàng");
		$sender->sendMessage("§d•§b Tổng số tiền bạn đã nạp: ".$this->tn->viewTichNap($sender->getName())." VNĐ");
		
	}else{
		$sender->sendMessage("§d•§b Mua Vàng không thành công, lỗi§e: ".$result["code"]." ");  
		$error = $result["msg"];
		$file = "cardsai.log";
		$fh = fopen($file,"a") or die("cant open file");
		fwrite($fh,"Tai khoan: ".$user.", Ma the: ".$sopin.", Seri: ".$seri.", Noi dung loi: ".$error.", Thoi gian: ".$time);
		fwrite($fh,"\r\n");
		fclose($fh);
       //Gửi lỗi (từ napthengay.com trả về)
		$sender->sendMessage("§d•§b Lỗi§e: ".$error);
	}
}
else{ 
       //thông báo khi id - key đăng ký trên napthengay.com không khớp
	$sender->sendMessage("§d•§e Dữ liệu không khớp!");
	/*   KẾT
	PLUGIN NÀY ĐƯỢC TẠO RA NHẰM MỤC ĐÍCH DẰN MẶT AI ĐÓ ĐÓ :), THÍCH ĐỘC QUYỀN THÌ T CHO ĐỘC QUYỀN, CÁC BẠN CÓ THỂ SHARE RỘNG RÃI NHƯNG LÀM ƠN HÃY NHỚ ĐẾN CÁI NGUỒN LÀ NGUYỄN ĐÔNG QUÝ HIHI
	SV CỦA T (1.9.X):
	ZOIDMC.TK
	PORT 2019
	THÂN ÁI VÀ DÍ DÁI VÀO MẶT ANH
	*/
}
	}
	return true;
	}
}