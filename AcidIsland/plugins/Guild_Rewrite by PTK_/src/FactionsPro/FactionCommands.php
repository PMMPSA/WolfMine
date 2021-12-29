<?php

namespace FactionsPro;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\level\level;
use pocketmine\level\Position;
use onebone\economyapi\EconomyAPI;

class FactionCommands {

    public $plugin;

    public function __construct(FactionMain $pg) {
        $this->plugin = $pg;
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        if ($sender instanceof Player) {
			$player = $sender->getPlayer()->getName();
			$create = $this->plugin->prefs->get("CreateCost");
			$claim = $this->plugin->prefs->get("ClaimCost");
			$oclaim = $this->plugin->prefs->get("OverClaimCost");
			$allyr = $this->plugin->prefs->get("AllyCost");
			$allya = $this->plugin->prefs->get("AllyPrice");
			$home = $this->plugin->prefs->get("SetHomeCost");
            $player = $sender->getPlayer()->getName();
            if (strtolower($command->getName('guilds') or $command->getName('g'))) {
                if (empty($args)) {
                    $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§6 Xin hãy sử dụng lệnh: §bô/g help"));
                    return true;
                }
                if (count($args == 2)) {

                    ///////////////////////////////// WAR /////////////////////////////////

                    if ($args[0] == "war") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g war <Tên guild hoặc tp>"));
                            return true;
                        }
                        if (strtolower($args[1]) == "tp") {
                            foreach ($this->plugin->wars as $r => $f) {
                                $fac = $this->plugin->getPlayerFaction($player);
                                if ($r == $fac) {
                                    $x = mt_rand(0, $this->plugin->getNumberOfPlayers($fac) - 1);
                                    $tper = $this->plugin->war_players[$f][$x];
                                    $sender->teleport($this->plugin->getServer()->getPlayerByName($tper));
                                    return;
                                }
                                if ($f == $fac) {
                                    $x = mt_rand(0, $this->plugin->getNumberOfPlayers($fac) - 1);
                                    $tper = $this->plugin->war_players[$r][$x];
                                    $sender->teleport($this->plugin->getServer()->getPlayer($tper));
                                    return;
                                }
                            }
                            $sender->sendMessage("§b[§3GuildPE§b]§cBạn cần ở trong 1 cuộc chiến thì mới có thể sử dụng lệnh này!");
                            return true;
                        }
                        if (!(ctype_alnum($args[1]))) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cBạn chỉ có thể sử dụng chữ và số!"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cHội không tồn tại!"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($sender->getName())) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cBạn cần ở trong 1 hội!"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cChỉ có người lãnh đạo của hội mới có thể gây chiến!"));
                            return true;
                        }
                        if (!$this->plugin->areEnemies($this->plugin->getPlayerFaction($player), $args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cHội của bạn không phải là kẻ thù của hội §b$args[1]"));
                            return true;
                        } else {
                            $factionName = $args[1];
                            $sFaction = $this->plugin->getPlayerFaction($player);
                            foreach ($this->plugin->war_req as $r => $f) {
                                if ($r == $args[1] && $f == $sFaction) {
                                    foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
                                        $task = new FactionWar($this->plugin, $r);
                                        $handler = $this->plugin->getServer()->getScheduler()->scheduleDelayedTask($task, 20 * 60 * 2);
                                        $task->setHandler($handler);
                                        $p->sendMessage("§b[§3GuildPE§b]§e cuộc chiến giữa hội §b$factionName §evà hội§b $sFaction §eđã bắt đầu!");
                                        if ($this->plugin->getPlayerFaction($p->getName()) == $sFaction) {
                                            $this->plugin->war_players[$sFaction][] = $p->getName();
                                        }
                                        if ($this->plugin->getPlayerFaction($p->getName()) == $factionName) {
                                            $this->plugin->war_players[$factionName][] = $p->getName();
                                        }
                                    }
                                    $this->plugin->wars[$factionName] = $sFaction;
                                    unset($this->plugin->war_req[strtolower($args[1])]);
                                    return true;
                                }
                            }
                            $this->plugin->war_req[$sFaction] = $factionName;
                            foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
                                if ($this->plugin->getPlayerFaction($p->getName()) == $factionName) {
                                    if ($this->plugin->getLeader($factionName) == $p->getName()) {
                                        $p->sendMessage("§b[§3GuildPE§b]§eHội §b $sFaction §emuốn gây chiến với hội của bạn! Xin hãy sử dụng lệnh /g war $sFaction để bắt đầu cuộc chiến!");
                                        $sender->sendMessage("§b[§3GuildPE§b]§aBạn đã gửi chiến thư cho đội này thành công! Xin hãy chờ hội này đồng ý!");
                                        return true;
                                    }
                                }
                            }
                            $sender->sendMessage("§b[§3GuildPE§b]§cLãnh đạo của hội này không trực tuyến!");
                            return true;
                        }
                    }

                    /////////////////////////////// CREATE ///////////////////////////////

                    if ($args[0] == "create") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§6 xin hãy sử dụng: /g create <Tên Hội> để tạo hội cần 2000Xu!"));
                            return true;
                        }
                        if ($this->plugin->isNameBanned($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cTên này đã bị cấm xin hãy chọn tên khác!"));
                            return true;
                        }
                        if ($this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cĐã có 1 hội với tên này trong hệ thống! Xin hãy chọn tên khác!"));
                            return true;
                        }
                        if (strlen($args[1]) > $this->plugin->prefs->get("MaxFactionNameLength")) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cTên này quá dài bạn chỉ có thể tạo tên hội với ". $this->plugin->prefs->get("MaxFactionNameLength") . " kí tự! Xin hãy đặt 1 tên khác ngắn hơn!"));
                            return true;
                        }
                        if ($this->plugin->isInFaction($sender->getName())) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cBạn đã có 1 hội rồi! Bạn cần thoát khỏi hội cũ thì mới có thể tạo hội mới!"));
                            return true;
                        } elseif($r = EconomyAPI::getInstance()->reduceMoney($player, $create)) {
                            $factionName = $args[1];
							$player = strtolower($player);
                            $rank = "Leader";
                            $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO master (player, faction, rank) VALUES (:player, :faction, :rank);");
                            $stmt->bindValue(":player", $player);
                            $stmt->bindValue(":faction", $factionName);
                            $stmt->bindValue(":rank", $rank);
                            $result = $stmt->execute();
                            $this->plugin->updateAllies($factionName);
                            $this->plugin->setFactionPower($factionName, $this->plugin->prefs->get("TheDefaultPowerEveryFactionStartsWith"));
                            $this->plugin->updateTag($sender->getName());
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§aBạn đã tạo 1 hội mới thành công! Xin hãy sử dụng lệnh /g dec để cập nhật thông tin cho hội!", true));
							$sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§aBạn đã dùng §6$create Xu để tạo hội mới!", true));
                            return true;
                        } else {
						
						switch($r){
							case EconomyAPI::RET_INVALID:
							
								$sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cBạn không đủ tiền để tạo hội! Bạn cần $create Xu để tạo hội!"));
								break;
							case EconomyAPI::RET_CANCELLED:
						
								$sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cLỖI!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§LỖI!"));
								break;
						}
					  }
                    }

                    /////////////////////////////// INVITE ///////////////////////////////

                    if ($args[0] == "invite") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§6Xin hay sử dụng: /g invite <Tên Người Chơi> để thêm người vào hội"));
                            return true;
                        }
                        if ($this->plugin->isFactionFull($this->plugin->getPlayerFaction($player))) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cThành viên trong hội của bạn đã đầy hãy xoá bớt để có thể thêm thành viên khác vào!"));
                            return true;
                        }
                        $invited = $this->plugin->getServer()->getPlayerExact($args[1]);
                        if (!($invited instanceof Player)) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cNgười chơi không tồn tại hoặc không trực tuyến!"));
                            return true;
                        }
                        if ($this->plugin->isInFaction($invited) == true) {
                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cNgười chơi này đã ở hội khác!"));
                            return true;
                        }
                        if ($this->plugin->prefs->get("OnlyLeadersAndOfficersCanInvite")) {
                            if (!($this->plugin->isOfficer($player) || $this->plugin->isLeader($player))) {
                                $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cChỉ chủ của hội hoặc người có thẩm quyền mới có thể thêm thành viên vào hội!"));
                                return true;
                            }
                        }
                        if ($invited->getName() == $player) {

                            $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§cBạn không thể thêm chính bạn vào hội của bạn!"));
                            return true;
                        }

                        $factionName = $this->plugin->getPlayerFaction($player);
                        $invitedName = $invited->getName();
                        $rank = "Member";

                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO confirm (player, faction, invitedby, timestamp) VALUES (:player, :faction, :invitedby, :timestamp);");
                        $stmt->bindValue(":player", $invitedName);
                        $stmt->bindValue(":faction", $factionName);
                        $stmt->bindValue(":invitedby", $sender->getName());
                        $stmt->bindValue(":timestamp", time());
                        $result = $stmt->execute();
                        $sender->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§aBạn đã mời người chơi§b $invitedName §avào hội thành công hãy chờ người chơi này trả lời!", true));
                        $invited->sendMessage($this->plugin->formatMessage("§b[§3GuildPE§b]§6Bạn được mời vào hội§b $factionName§6! Xin hãy sử dụng lệnh /g accept để đồng ý vào hội hoặc /g deny để từ chối!", true));
                    }

                    /////////////////////////////// LEADER ///////////////////////////////

                    if ($args[0] == "leader") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g leader <người chơi>"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($sender->getName())) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới có thể sử dụng"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải là chủ guild mới dùng được"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) != $this->plugin->getPlayerFaction($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Hãy thêm người chơi này vào guild của bạn trước!"));
                            return true;
                        }
                        if (!($this->plugin->getServer()->getPlayerExact($args[1]) instanceof Player)) {
                            $sender->sendMessage($this->plugin->formatMessage("Người chơi không online"));
                            return true;
                        }
                        if ($args[1] == $sender->getName()) {

                            $sender->sendMessage($this->plugin->formatMessage("Bạn không thể tặng guild cho chính bạn"));
                            return true;
                        }
                        $factionName = $this->plugin->getPlayerFaction($player);

                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO master (player, faction, rank) VALUES (:player, :faction, :rank);");
                        $stmt->bindValue(":player", $player);
                        $stmt->bindValue(":faction", $factionName);
                        $stmt->bindValue(":rank", "Member");
                        $result = $stmt->execute();

                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO master (player, faction, rank) VALUES (:player, :faction, :rank);");
                        $stmt->bindValue(":player", $args[1]);
                        $stmt->bindValue(":faction", $factionName);
                        $stmt->bindValue(":rank", "Leader");
                        $result = $stmt->execute();


                        $sender->sendMessage($this->plugin->formatMessage("Bạn không còn là chủ guild nữa", true));
                        $this->plugin->getServer()->getPlayerExact($args[1])->sendMessage($this->plugin->formatMessage("Bạn không còn là chủ của guild $factionName!", true));
                        $this->plugin->updateTag($sender->getName());
                        $this->plugin->updateTag($this->plugin->getServer()->getPlayerExact($args[1])->getName());
                    }

                    /////////////////////////////// PROMOTE ///////////////////////////////

                    if ($args[0] == "promote") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g promote <người chơi>"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($sender->getName())) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải là chủ guild mới dùng được"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) != $this->plugin->getPlayerFaction($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Player is not in this guilds!"));
                            return true;
                        }
                        if ($args[1] == $sender->getName()) {
                            $sender->sendMessage($this->plugin->formatMessage("You can't promote yourself!"));
                            return true;
                        }

                        if ($this->plugin->isOfficer($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Player is already Assistants!"));
                            return true;
                        }
                        $factionName = $this->plugin->getPlayerFaction($player);
                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO master (player, faction, rank) VALUES (:player, :faction, :rank);");
                        $stmt->bindValue(":player", $args[1]);
                        $stmt->bindValue(":faction", $factionName);
                        $stmt->bindValue(":rank", "Officer");
                        $result = $stmt->execute();
                        $player = $this->plugin->getServer()->getPlayerExact($args[1]);
                        $sender->sendMessage($this->plugin->formatMessage("$args[1] has been promoted to Assistants", true));

                        if ($player instanceof Player) {
                            $player->sendMessage($this->plugin->formatMessage("You were promoted to assistants of $factionName!", true));
                            $this->plugin->updateTag($this->plugin->getServer()->getPlayerExact($args[1])->getName());
                            return true;
                        }
                    }

                    /////////////////////////////// DEMOTE ///////////////////////////////

                    if ($args[0] == "demote") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g demote <người chơi>"));
                            return true;
                        }
                        if ($this->plugin->isInFaction($sender->getName()) == false) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này"));
                            return true;
                        }
                        if ($this->plugin->isLeader($player) == false) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be leader to use this"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) != $this->plugin->getPlayerFaction($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Player is not in this guilds!"));
                            return true;
                        }

                        if ($args[1] == $sender->getName()) {
                            $sender->sendMessage($this->plugin->formatMessage("You can't demote yourself!"));
                            return true;
                        }
                        if (!$this->plugin->isOfficer($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Player is already Member!"));
                            return true;
                        }
                        $factionName = $this->plugin->getPlayerFaction($player);
                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO master (player, faction, rank) VALUES (:player, :faction, :rank);");
                        $stmt->bindValue(":player", $args[1]);
                        $stmt->bindValue(":faction", $factionName);
                        $stmt->bindValue(":rank", "Member");
                        $result = $stmt->execute();
                        $player = $this->plugin->getServer()->getPlayerExact($args[1]);
                        $sender->sendMessage($this->plugin->formatMessage("$args[1] has been demoted to Member", true));
                        if ($player instanceof Player) {
                            $player->sendMessage($this->plugin->formatMessage("You were demoted to member of $factionName!", true));
                            $this->plugin->updateTag($this->plugin->getServer()->getPlayerExact($args[1])->getName());
                            return true;
                        }
                    }

                    /////////////////////////////// KICK ///////////////////////////////

                    if ($args[0] == "kick") {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g kick <người chơi>"));
                            return true;
                        }
                        if ($this->plugin->isInFaction($sender->getName()) == false) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này!"));
                            return true;
                        }
                        if ($this->plugin->isLeader($player) == false) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be leader to use this!"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) != $this->plugin->getPlayerFaction($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Player is not in this guilds!"));
                            return true;
                        }
                        if ($args[1] == $sender->getName()) {
                            $sender->sendMessage($this->plugin->formatMessage("You can't kick yourself!"));
                            return true;
                        }
                        $kicked = $this->plugin->getServer()->getPlayerExact($args[1]);
                        $factionName = $this->plugin->getPlayerFaction($player);
                        $this->plugin->db->query("DELETE FROM master WHERE player='$args[1]';");
                        $sender->sendMessage($this->plugin->formatMessage("You successfully kicked $args[1]", true));
                        $this->plugin->subtractFactionPower($factionName, $this->plugin->prefs->get("PowerGainedPerPlayerInFaction"));

                        if ($kicked instanceof Player) {
                            $kicked->sendMessage($this->plugin->formatMessage("You have been kicked from $factionName", true));
                            $this->plugin->updateTag($this->plugin->getServer()->getPlayerExact($args[1])->getName());
                            return true;
                        }
                    }

                    /////////////////////////////// INFO ///////////////////////////////

                    if (strtolower($args[0]) == 'info') {
                        if (isset($args[1])) {
                            if (!(ctype_alnum($args[1])) | !($this->plugin->factionExists($args[1]))) {
                                $sender->sendMessage($this->plugin->formatMessage("Guilds does not exist"));
                                $sender->sendMessage($this->plugin->formatMessage("Make sure the name of the selected guild is ABSOLUTELY EXACT."));
                                return true;
                            }
                            $faction = $args[1];
                            $result = $this->plugin->db->query("SELECT * FROM motd WHERE faction='$faction';");
                            $array = $result->fetchArray(SQLITE3_ASSOC);
                            $power = $this->plugin->getFactionPower($faction);
                            $money = $this->plugin->getFactionMoney($faction);
                            $message = $array["message"];
                            $leader = $this->plugin->getLeader($faction);
                            $numPlayers = $this->plugin->getNumberOfPlayers($faction);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§eInformation §l§b«" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aGuilds §8: " . TextFormat::GREEN . "§d$faction" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aLeader §8: " . TextFormat::YELLOW . "§d$leader" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aPlayers §8: " . TextFormat::LIGHT_PURPLE . "§d$numPlayers" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aGuildsPoints §8: " . TextFormat::RED . "§d$power" . " " . TextFormat::RESET);     
                $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aGuildsMoneys §8: " . TextFormat::RED . "§d$money" . " " . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aDescription §8: " . TextFormat::AQUA . TextFormat::UNDERLINE . "§d$message" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§eInformation §l§b«" . TextFormat::RESET);
                        } else {
                            if (!$this->plugin->isInFaction($player)) {
                                $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này!"));
                                return true;
                            }
                            $faction = $this->plugin->getPlayerFaction(($sender->getName()));
                            $result = $this->plugin->db->query("SELECT * FROM motd WHERE faction='$faction';");
                            $array = $result->fetchArray(SQLITE3_ASSOC);
                            $power = $this->plugin->getFactionPower($faction);
                            $money = $this->plugin->getFactionMoney($faction);
                            $message = $array["message"];
                            $leader = $this->plugin->getLeader($faction);
                            $numPlayers = $this->plugin->getNumberOfPlayers($faction);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§eInformation §l§b«" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aGuilds §8: " . TextFormat::GREEN . "§d$faction" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aLeader §8: " . TextFormat::YELLOW . "§d$leader" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aPlayers §8: " . TextFormat::LIGHT_PURPLE . "§d$numPlayers" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aGuildsPoints §8: " . TextFormat::RED . "§d$power" . " " . TextFormat::RESET);     
                $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aGuildsMoneys §8: " . TextFormat::RED . "§d$money" . " " . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§aDescription §8: " . TextFormat::AQUA . TextFormat::UNDERLINE . "§d$message" . TextFormat::RESET);
                            $sender->sendMessage(TextFormat::GOLD . TextFormat::ITALIC . "§r§l§b» §r§eInformation §l§b«" . TextFormat::RESET);
                    return true;
                        }
                    }
/*Help Commands*/
                    if (strtolower($args[0]) == "help") {
                        if (!isset($args[1]) || $args[1] == 1) {
                            $sender->sendMessage(TextFormat::GOLD . "§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«\n§l§b»§r     §dGuilds Help Page §f[§c1§f/§f§c6§f]       §l§b«\n§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«" . TextFormat::RED . "\n§l§c»§r §e/g about §l§b»§r §aShows Any Information You Need To Know!\n§l§c»§r §e/g accept §l§b»§r §aAccept A Guilds Request!\n§l§c»§r §e/g create <name> §l§b»§r §aCreate Your Desire Guilds!\n§l§c»§r §e/g del §l§b»§r §aDelete Your Own Guilds!\n§l§c»§r §e/g demote <người chơi> §l§b»§r §aDemote Your Any Assistance To Members!\n§l§c»§r §e/g deny §l§b»§r §aDeny A Guilds Request!");
                            return true;
                        }
                        if ($args[1] == 2) {
                            $sender->sendMessage(TextFormat::GOLD . "§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«\n§l§b»§r     §dGuilds Help Page §f[§c2§f/§f§c6§f]       §l§b«\n§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«" . TextFormat::RED . "\n§l§c»§r §e/g help <page> §l§b»§r §aShows A List Of Guilds Help Page!\n§l§c»§r §e/g info §l§b»§r §aShows Your Guilds Information!\n§l§c»§r §e/g info <faction> §l§b»§r §aShows Targets Guilds Information!\n§l§c»§r §e/g invite <người chơi> §l§b»§r §aInvite A Player As A Leader!\n§l§c»§r §e/g kick <người chơi> §l§b»§r §aKick/Remove Specific Player From Guilds!\n§l§c»§r §e/g leader <người chơi> §l§b»§r §aMake A Player To Be The New Leader!\n§l§c»§r §e/g leave §l§b»§r §aLeave Your Current Guilds!");
                            return true;
                        }
                        if ($args[1] == 3) {
                            $sender->sendMessage(TextFormat::GOLD . "§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«\n§l§b»§r     §dGuilds Help Page §f[§c3§f/§f§c6§f]       §l§b«\n§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«" . TextFormat::RED . "\n§l§c»§r §e/g members - {Members + Statuses} §l§b»§r §aShows Your Guilds MembersList!\n§l§c»§r §e/g assistants - {Assistants + Statuses} §l§b»§r §aShows Your AssistantsList!\n§l§c»§r §e/g ourleaders - {Leader + Status} §l§b»§r §aShows Your LeadersList!\n§l§c»§r §e/g allies §l§b»§r §aShows The Guild YOU ALLIED!\n§l§c»§r §e/g claim\n§l§c»§r §e/g unclaim\n§l§c»§r §e/g pos\n§l§c»§r §e/g overclaim\n/g say <message>");
                            return true;
                        }
                        if ($args[1] == 4) {
                            $sender->sendMessage(TextFormat::GOLD . "§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«\n§l§b»§r     §dGuilds Help Page §f[§c4§f/§f§c6§f]       §l§b«\n§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«" . TextFormat::RED . "\n§l§c»§r §e/g desc §l§b»§r §aUpdate The Guilds Description!\n§l§c»§r §e/g promote <người chơi> §l§b»§r §aPromote A Members To Assistants!\n§l§c»§r §e/g allywith <guilds> §l§b»§r §aRequest An Alliance With A Guilds!\n§l§c»§r §e/g breakalliancewith <guilds> §l§b»§r §aBreak The Alliance Contract With A Guilds!\n§l§c»§r §e/g allyok §l§b»§r §aAccept An Alliance Request!\n§l§c»§r §e/g allyno §l§b»§r §aDenied An Alliance Request!\n§l§c»§r §e/g allies <guilds> §l§b»§r §aShows A Specific Guilds Alliance!");
                            return true;
                        }
                        if ($args[1] == 5) {
                            $sender->sendMessage(TextFormat::GOLD . "§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«\n§l§b»§r     §dGuilds Help Page §f[§c5§f/§f§c5§f]       §l§b«\n§l§b»§r§a-=-=-=-=-=-=-=-=-=-=-=-=-=-§l§b«" . TextFormat::RED . "\n§l§c»§r §e/g membersof <guilds> §l§b»§r §aShows The List Of A Specific Guilds Members!\n§l§c»§r §e/g assistantsof <guilds> §l§b»§r §aShows The List Of A Specific Guilds Assistants!\n§l§c»§r §e/g leadersof <guilds> §l§b»§r §aShows The Guilds Leaders List!\n§l§c»§r §e/g search <người chơi> §l§b»§r §aSearch The Player Guilds!\n§l§c»§r §e/g leaderboards §l§b»§r §aShows Top Ranking Guilds!\n§l§c»§r §e/g setef §l§b»§r §aSet Effects For Guilds!\n§l§c»§r §e/g efinfo §l§b»§r §aShows Effects Information!\n§l§c»§r §e/g getef §l§b»§r §aGets The Effects You Have Setted!\n§l§c»§r §e/g sethome\n§l§c»§r §e/g unsethome\n§l§c»§r §e/g home");
                            return true;

                        }
                        if ($args[1] == 6){
                            $sender->isOP();
                            $sender->sendMessage(Textformat::GOLD. "Special OP Commands\n/g forcedelete <guilds>\n/g addgp\n/g forceunclaim <guilds>\n/g addmoney");
                            return true;
                        }else{
                            $sender->sendMessage("ERR :P");
                            return true;
                        }
                    }
                }
                if (count($args == 1)) {



					/////////////////////////////// CLAIM ///////////////////////////////
					
					if(strtolower($args[0]) == 'claim') {//
						if(!$this->plugin->isInFaction($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be in a guilds to claim"));
							return true;
						}
                        if($this->plugin->prefs->get("OfficersCanClaim")){
                            if(!$this->plugin->isLeader($player) || !$this->plugin->isOfficer($player)) {
							    $sender->sendMessage($this->plugin->formatMessage("§cOnly Leaders and Officers can claim"));
							    return true;
						    }
                        } else {
                            if(!$this->plugin->isLeader($player)) {
							    $sender->sendMessage($this->plugin->formatMessage("§cYou must be leader to use this"));
							    return true;
						    }
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be leader to use this."));
                            return true;
                        }
                        if (!in_array($sender->getPlayer()->getLevel()->getName(), $this->plugin->prefs->get("ClaimWorlds"))) {
                            $sender->sendMessage($this->plugin->formatMessage("You can only claim in Guilds Worlds: " . implode(" ", $this->plugin->prefs->get("ClaimWorlds"))));
                            return true;
                        }
                        
						if($this->plugin->inOwnPlot($sender)) {
							$sender->sendMessage($this->plugin->formatMessage("§aYour guilds has already claimed this area."));
							return true;
						}
						$faction = $this->plugin->getPlayerFaction($sender->getPlayer()->getName());
                        if($this->plugin->getNumberOfPlayers($faction) < $this->plugin->prefs->get("PlayersNeededInFactionToClaimAPlot")){
                           
                           $needed_players =  $this->plugin->prefs->get("PlayersNeededInFactionToClaimAPlot") - 
                                               $this->plugin->getNumberOfPlayers($faction);
                           $sender->sendMessage($this->plugin->formatMessage("§bYou need §e$needed_players §bmore players to claim"));
				           return true;
                        }
                        if($this->plugin->getFactionPower($faction) < $this->plugin->prefs->get("PowerNeededToClaimAPlot")){
                            $needed_power = $this->plugin->prefs->get("PowerNeededToClaimAPlot");
                            $faction_power = $this->plugin->getFactionPower($faction);
							$sender->sendMessage($this->plugin->formatMessage("§3Your guilds doesn't have enough power to claim"));
							$sender->sendMessage($this->plugin->formatMessage("§e"."$needed_power" . " §3power is required. Your guilds only has §a$faction_power §3power."));
                            return true;
                        }
						elseif($r = EconomyAPI::getInstance()->reduceMoney($player, $claim)){
						$x = floor($sender->getX());
						$y = floor($sender->getY());
						$z = floor($sender->getZ());
						if($this->plugin->drawPlot($sender, $faction, $x, $y, $z, $sender->getPlayer()->getLevel(), $this->plugin->prefs->get("PlotSize")) == false) {
                            
							return true;
						}
                        
						$sender->sendMessage($this->plugin->formatMessage("§bGetting your coordinates...", true));
                        $plot_size = $this->plugin->prefs->get("PlotSize");
                        $faction_power = $this->plugin->getFactionPower($faction);
						$sender->sendMessage($this->plugin->formatMessage("§aLand successfully claimed for §6$$claim§a.", true));
					}
					else {
						// $r is an error code
						switch($r){
							case EconomyAPI::RET_INVALID:
								# Invalid $amount
								$sender->sendMessage($this->plugin->formatMessage("§3You do not have enough Money to Claim! Need §6$$claim"));
								break;
							case EconomyAPI::RET_CANCELLED:
								# Transaction was cancelled for some reason :/
								$sender->sendMessage($this->plugin->formatMessage("Error!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("Error!"));
								break;
						}
					}
					}
                    //position
                    if(strtolower($args[0]) == 'pos'){
                        $x = floor($sender->getX());
						$y = floor($sender->getY());
						$z = floor($sender->getZ());
                        $fac = $this->plugin->factionFromPoint($x,$z);
                        $power = $this->plugin->getFactionPower($fac);
                        if(!$this->plugin->isInPlot($sender)){
                            $sender->sendMessage($this->plugin->formatMessage("§bThis area is unclaimed. Use §e/g claim §bto claim", true));
							return true;
                        }
                        $sender->sendMessage($this->plugin->formatMessage("§3This plot is claimed by §a$fac §3with §e$power §3power"));
                    }
                    
                    if(strtolower($args[0]) == 'overclaim') {
						if(!$this->plugin->isInFaction($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cBạn phải có 1 guild mới dùng được lệnh này"));
							return true;
						}
						if(!$this->plugin->isLeader($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be leader to use this"));
							return true;
						}
                        $faction = $this->plugin->getPlayerFaction($player);
						if($this->plugin->getNumberOfPlayers($faction) < $this->plugin->prefs->get("PlayersNeededInFactionToClaimAPlot")){
                           
                           $needed_players =  $this->plugin->prefs->get("PlayersNeededInFactionToClaimAPlot") - 
                                               $this->plugin->getNumberOfPlayers($faction);
                           $sender->sendMessage($this->plugin->formatMessage("§3You need §e$needed_players §3more players to overclaim"));
				           return true;
                        }
                        if($this->plugin->getFactionPower($faction) < $this->plugin->prefs->get("PowerNeededToClaimAPlot")){
                            $needed_power = $this->plugin->prefs->get("PowerNeededToClaimAPlot");
                            $faction_power = $this->plugin->getFactionPower($faction);
							$sender->sendMessage($this->plugin->formatMessage("§3Your guilds does not have enough power to claim! Get power by killing players!"));
							$sender->sendMessage($this->plugin->formatMessage("§e$needed_power" . "§3 power is required but your guilds only has §e$faction_power §3power"));
                            return true;
                        }
						$sender->sendMessage($this->plugin->formatMessage("§bGetting your coordinates...", true));
						$x = floor($sender->getX());
						$y = floor($sender->getY());
						$z = floor($sender->getZ());
                        if($this->plugin->prefs->get("EnableOverClaim")){
                            if($this->plugin->isInPlot($sender)){
                                $faction_victim = $this->plugin->factionFromPoint($x,$z);
                                $faction_victim_power = $this->plugin->getFactionPower($faction_victim);
                                $faction_ours = $this->plugin->getPlayerFaction($player);
                                $faction_ours_power = $this->plugin->getFactionPower($faction_ours);
                                if($this->plugin->inOwnPlot($sender)){
                                    $sender->sendMessage($this->plugin->formatMessage("§aYour guilds has already claimed this land"));
                                    return true;
                                } else {
                                    if($faction_ours_power < $faction_victim_power){
                                        $sender->sendMessage($this->plugin->formatMessage("§3Your power level is too low to over claim §b$faction_victim"));
                                        return true;
                                    } elseif($r = EconomyAPI::getInstance()->reduceMoney($player, $oclaim))
									   {
                                        $this->plugin->db->query("DELETE FROM plots WHERE faction='$faction_ours';");
                                        $this->plugin->db->query("DELETE FROM plots WHERE faction='$faction_victim';");
                                        $arm = (($this->plugin->prefs->get("PlotSize")) - 1) / 2;
                                        $this->plugin->newPlot($faction_ours,$x+$arm,$z+$arm,$x-$arm,$z-$arm);
					$sender->sendMessage($this->plugin->formatMessage("§aYour guilds has successfully overclaimed the land of §b$faction_victim §afor §6$$oclaim", true));
                                        return true;
                                    }
									else {
						// $r is an error code
						    switch($r){
							case EconomyAPI::RET_INVALID:
								# Invalid $amount
								$sender->sendMessage($this->plugin->formatMessage("§3You do not have enough Money to Overclaim! Need §6$oclaim"));
								break;
							case EconomyAPI::RET_CANCELLED:
								# Transaction was cancelled for some reason :/
								$sender->sendMessage($this->plugin->formatMessage("Error!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("Error!"));
								break;
						}
					}
                                    
                                }
                            } else {
                                $sender->sendMessage($this->plugin->formatMessage("§cYou are not in claimed land"));
                                return true;
                            }
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("§cInsufficient permissions"));
                            return true;
                        }
                        
					}
                    
					
					/////////////////////////////// UNCLAIM ///////////////////////////////
					
					if(strtolower($args[0]) == "unclaim") {
                        if(!$this->plugin->isInFaction($sender->getName())) {
							$sender->sendMessage($this->plugin->formatMessage("§cBạn phải có 1 guild mới dùng được lệnh này"));
							return true;
						}
						if(!$this->plugin->isLeader($sender->getName())) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be leader to use this"));
							return true;
						}
						$faction = $this->plugin->getPlayerFaction($sender->getName());
						$this->plugin->db->query("DELETE FROM plots WHERE faction='$faction';");
						$sender->sendMessage($this->plugin->formatMessage("§aLand successfully unclaimed", true));
					}
					/////////////////////////////// SETHOME ///////////////////////////////
					
					if(strtolower($args[0] == "sethome")) {
						if(!$this->plugin->isInFaction($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be in a guilds to do this"));
							return true;
						}
						if(!$this->plugin->isLeader($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be leader to set home"));
							return true;
						}
                        
                        $faction_power = $this->plugin->getFactionPower($this->plugin->getPlayerFaction($player));
                        $needed_power = $this->plugin->prefs->get("PowerNeededToSetOrUpdateAHome");
                        if($faction_power < $needed_power){
                            $sender->sendMessage($this->plugin->formatMessage("§3Your guilds doesn't have enough power set a home. Get power by killing players!"));
                            $sender->sendMessage($this->plugin->formatMessage("§e $needed_power §3power is required to set a home. Your guilds has §e$faction_power §3power."));
							return true;
                        }
						elseif($r = EconomyAPI::getInstance()->reduceMoney($player, $home)){
						$factionName = $this->plugin->getPlayerFaction($sender->getName());
						$stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO home (faction, x, y, z) VALUES (:faction, :x, :y, :z);");
						$stmt->bindValue(":faction", $factionName);
						$stmt->bindValue(":x", $sender->getX());
						$stmt->bindValue(":y", $sender->getY());
						$stmt->bindValue(":z", $sender->getZ());
						$result = $stmt->execute();
						$sender->sendMessage($this->plugin->formatMessage("Guilds home set for $home Coins", true));
                        }
						else {

						    switch($r){
							case EconomyAPI::RET_INVALID:

								$sender->sendMessage($this->plugin->formatMessage("Error! You Need $home Coins To Set A Home!"));
								break;
							case EconomyAPI::RET_CANCELLED:
								$sender->sendMessage($this->plugin->formatMessage("Error!"));
								break;
							case EconomyAPI::RET_NO_ACCOUNT:
								$sender->sendMessage($this->plugin->formatMessage("Error!"));
								break;
						}
					}
					}
					
					/////////////////////////////// UNSETHOME ///////////////////////////////
						
					if(strtolower($args[0] == "unsethome")) {
						if(!$this->plugin->isInFaction($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be in a guilds to do this"));
							return true;
						}
						if(!$this->plugin->isLeader($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be leader to unset home"));
							return true;
						}
						$faction = $this->plugin->getPlayerFaction($sender->getName());
						$this->plugin->db->query("DELETE FROM home WHERE faction = '$faction';");
						$sender->sendMessage($this->plugin->formatMessage("§aHome unset succeed", true));
					}
					
					/////////////////////////////// HOME ///////////////////////////////
						
					if(strtolower($args[0] == "home")) {
						if(!$this->plugin->isInFaction($player)) {
							$sender->sendMessage($this->plugin->formatMessage("§cYou must be in a guilds to do this."));
                            return true;
						}
						$faction = $this->plugin->getPlayerFaction($sender->getName());
						$result = $this->plugin->db->query("SELECT * FROM home WHERE faction = '$faction';");
						$array = $result->fetchArray(SQLITE3_ASSOC);
						if(!empty($array)) {
							$sender->getPlayer()->teleport(new Vector3($array['x'], $array['y'], $array['z']));
							$sender->sendMessage($this->plugin->formatMessage("§bTeleported to home.", true));
							return true;
						} else {
							$sender->sendMessage($this->plugin->formatMessage("Guilds home has not been set"));
				        }
				    }
                    //TOP10 Leaderboards
                    if (strtolower($args[0]) == 'leaderboards') {
                        $this->plugin->sendListOfTop10FactionsTo($sender);
                    }
                    //force unclaim
                    if(strtolower($args[0] == "forceunclaim")){
                        if(!isset($args[1])){
                            $sender->sendMessage($this->plugin->formatMessage("/g forceunclaim <guilds>"));
                            return true;
                        }
                        if(!$this->plugin->factionExists($args[1])) {
							$sender->sendMessage($this->plugin->formatMessage("§cThe requested guilds does not exist"));
                            return true;
						}
                        if(!($sender->isOp())) {
							$sender->sendMessage($this->plugin->formatMessage("§cInsufficient permissions"));
                            return true;
						}
				        $sender->sendMessage($this->plugin->formatMessage("§bLand of §a$args[1]§b unclaimed"));
                        $this->plugin->db->query("DELETE FROM plots WHERE faction='$args[1]';");
                        
                    }
                    //forcedelete
                    if (strtolower($args[0]) == 'forcedelete') {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g forcedelete <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist."));
                            return true;
                        }
                        if (!($sender->isOp())) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be OP to do this."));
                            return true;
                        }
                        $this->plugin->db->query("DELETE FROM master WHERE faction='$args[1]';");
                        $this->plugin->db->query("DELETE FROM plots WHERE faction='$args[1]';");
                        $this->plugin->db->query("DELETE FROM allies WHERE faction1='$args[1]';");
                        $this->plugin->db->query("DELETE FROM allies WHERE faction2='$args[1]';");
                        $this->plugin->db->query("DELETE FROM strength WHERE faction='$args[1]';");
                        $this->plugin->db->query("DELETE FROM motd WHERE faction='$args[1]';");
                        $this->plugin->db->query("DELETE FROM home WHERE faction='$args[1]';");
                        $sender->sendMessage($this->plugin->formatMessage("Unwanted guilds was successfully deleted and their guilds plot was unclaimed!", true));
                    }
                    //Add Guilds Points
                    if (strtolower($args[0]) == 'addgp') {
                        if (!isset($args[1]) or ! isset($args[2])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g addgp <guilds> <GuildsPoints>"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist."));
                            return true;
                        }
                        if (!($sender->isOp())) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be OP to do this."));
                            return true;
                        }
                        $this->plugin->addFactionPower($args[1], $args[2]);
                        $sender->sendMessage($this->plugin->formatMessage("Successfully added $args[2] GuildsPoints to $args[1]", true));
                    }
                    if (strtolower($args[0]) == 'addmoney') {
                        if (!isset($args[1]) or ! isset($args[2])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g addmoney <guilds> <GuildsMoneys>"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist."));
                            return true;
                        }
                        if (!($sender->isOp())) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be OP to do this."));
                            return true;
                        }
                        $this->plugin->addFactionMoney($args[1], $args[2]);
                        $sender->sendMessage($this->plugin->formatMessage("Successfully added $args[2] GuildsMoneys to $args[1]", true));
                    }
                    //Stalk A player
                    if (strtolower($args[0]) == 'search') {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g search <người chơi>"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The selected player is not in a guilds  or doesn't exist."));
                            $sender->sendMessage($this->plugin->formatMessage("Make sure the name of the selected player is ABSOLUTELY EXACT."));
                            return true;
                        }
                        $faction = $this->plugin->getPlayerFaction($args[1]);
                        $sender->sendMessage($this->plugin->formatMessage("-$args[1] is in $faction-", true));
                    }

                    /////////////////////////////// DESCRIPTION ///////////////////////////////

                    if (strtolower($args[0]) == "desc") {
                        if ($this->plugin->isInFaction($sender->getName()) == false) {
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này!"));
                            return true;
                        }
                        if ($this->plugin->isLeader($player) == false) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be leader to use this"));
                            return true;
                        }
                        $sender->sendMessage($this->plugin->formatMessage("Type your message in chat. It will not be visible to other players", true));
                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO motdrcv (player, timestamp) VALUES (:player, :timestamp);");
                        $stmt->bindValue(":player", $sender->getName());
                        $stmt->bindValue(":timestamp", time());
                        $result = $stmt->execute();
                    }

                    /////////////////////////////// ACCEPT ///////////////////////////////

                    if (strtolower($args[0]) == "accept") {
                        $player = $sender->getName();
                        $lowercaseName = ($player);
                        $result = $this->plugin->db->query("SELECT * FROM confirm WHERE player='$lowercaseName';");
                        $array = $result->fetchArray(SQLITE3_ASSOC);
                        if (empty($array) == true) {
                            $sender->sendMessage($this->plugin->formatMessage("You have not been invited to any guilds"));
                            return true;
                        }
                        $invitedTime = $array["timestamp"];
                        $currentTime = time();
                        if (($currentTime - $invitedTime) <= 60) { //This should be configurable
                            $faction = $array["faction"];
                            $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO master (player, faction, rank) VALUES (:player, :faction, :rank);");
                            $stmt->bindValue(":player", ($player));
                            $stmt->bindValue(":faction", $faction);
                            $stmt->bindValue(":rank", "Member");
                            $result = $stmt->execute();
                            $this->plugin->db->query("DELETE FROM confirm WHERE player='$lowercaseName';");
                            $sender->sendMessage($this->plugin->formatMessage("You successfully joined $faction", true));
                            $this->plugin->addFactionPower($faction, $this->plugin->prefs->get("PowerGainedPerPlayerInFaction"));
                            $this->plugin->getServer()->getPlayerExact($array["invitedby"])->sendMessage($this->plugin->formatMessage("$player joined the guilds", true));
                            $this->plugin->updateTag($sender->getName());
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("Invite has timed out"));
                            $this->plugin->db->query("DELETE * FROM confirm WHERE player='$player';");
                        }
                    }

                    /////////////////////////////// DENY ///////////////////////////////

                    if (strtolower($args[0]) == "deny") {
                        $player = $sender->getName();
                        $lowercaseName = ($player);
                        $result = $this->plugin->db->query("SELECT * FROM confirm WHERE player='$lowercaseName';");
                        $array = $result->fetchArray(SQLITE3_ASSOC);
                        if (empty($array) == true) {
                            $sender->sendMessage($this->plugin->formatMessage("You have not been invited to any guilds"));
                            return true;
                        }
                        $invitedTime = $array["timestamp"];
                        $currentTime = time();
                        if (($currentTime - $invitedTime) <= 60) { //This should be configurable
                            $this->plugin->db->query("DELETE FROM confirm WHERE player='$lowercaseName';");
                            $sender->sendMessage($this->plugin->formatMessage("Invite declined", true));
                            $this->plugin->getServer()->getPlayerExact($array["invitedby"])->sendMessage($this->plugin->formatMessage("$player declined the invitation"));
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("Invite has timed out"));
                            $this->plugin->db->query("DELETE * FROM confirm WHERE player='$lowercaseName';");
                        }
                    }

                    /////////////////////////////// DELETE ///////////////////////////////

                    if (strtolower($args[0]) == "del") {
                        if ($this->plugin->isInFaction($player) == true) {
                            if ($this->plugin->isLeader($player)) {
                                $faction = $this->plugin->getPlayerFaction($player);
                                $this->plugin->db->query("DELETE FROM plots WHERE faction='$faction';");
                                $this->plugin->db->query("DELETE FROM master WHERE faction='$faction';");
                                $this->plugin->db->query("DELETE FROM allies WHERE faction1='$faction';");
                                $this->plugin->db->query("DELETE FROM allies WHERE faction2='$faction';");
                                $this->plugin->db->query("DELETE FROM strength WHERE faction='$faction';");
                                $this->plugin->db->query("DELETE FROM motd WHERE faction='$faction';");
                                $this->plugin->db->query("DELETE FROM home WHERE faction='$faction';");
                                $sender->sendMessage($this->plugin->formatMessage("Guilds successfully disbanded.", true));
                                $this->plugin->updateTag($sender->getName());
                            } else {
                                $sender->sendMessage($this->plugin->formatMessage("You are not leader!"));
                            }
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("You are not in a guilds!"));
                        }
                    }

                    /////////////////////////////// LEAVE ///////////////////////////////

                    if (strtolower($args[0] == "leave")) {
                        if ($this->plugin->isLeader($player) == false) {
                            $remove = $sender->getPlayer()->getNameTag();
                            $faction = $this->plugin->getPlayerFaction($player);
                            $name = $sender->getName();
                            $this->plugin->db->query("DELETE FROM master WHERE player='$name';");
                            $sender->sendMessage($this->plugin->formatMessage("You successfully left $faction", true));

                            $this->plugin->subtractFactionPower($faction, $this->plugin->prefs->get("PowerGainedPerPlayerInFaction"));
                            $this->plugin->updateTag($sender->getName());
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("You must delete the guilds or give leadership to someone else first"));
                        }
                    }

                    /////////////////////////////// MEMBERS/OFFICERS/LEADER AND THEIR STATUSES ///////////////////////////////
                    if (strtolower($args[0] == "members")) {
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        $this->plugin->getPlayersInFactionByRank($sender, $this->plugin->getPlayerFaction($player), "Member");
                    }
                    if (strtolower($args[0] == "membersof")) {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g membersof <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                            return true;
                        }
                        $this->plugin->getPlayersInFactionByRank($sender, $args[1], "Member");
                    }
                    if (strtolower($args[0] == "assistants")) {
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        $this->plugin->getPlayersInFactionByRank($sender, $this->plugin->getPlayerFaction($player), "Officer");
                    }
                    if (strtolower($args[0] == "assistantsof")) {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g assistantsof <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                            return true;
                        }
                        $this->plugin->getPlayersInFactionByRank($sender, $args[1], "Officer");
                    }
                    if (strtolower($args[0] == "ourleaders")) {
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        $this->plugin->getPlayersInFactionByRank($sender, $this->plugin->getPlayerFaction($player), "Leader");
                    }
                    if (strtolower($args[0] == "leadersof")) {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g leadersof <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                            return true;
                        }
                        $this->plugin->getPlayersInFactionByRank($sender, $args[1], "Leader");
                    }
                    if (strtolower($args[0] == "say")) {
                        if (!($this->plugin->isInFaction($player))) {

                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to send faction messages"));
                            return true;
                        }
                        $r = count($args);
                        $row = array();
                        $rank = "Member";
                        $f = $this->plugin->getPlayerFaction($player);

                        if ($this->plugin->isOfficer($player)) {
                            $rank = "Assistant";
                        } else if ($this->plugin->isLeader($player)) {
                            $rank = "Leader";
                        }
                        $message = " ";
                        for ($i = 0; $i < $r - 1; $i = $i + 1) {
                            $message = $message . $args[$i + 1] . " ";
                        }
                        $result = $this->plugin->db->query("SELECT * FROM master WHERE faction='$f';");
                        for ($i = 0; $resultArr = $result->fetchArray(SQLITE3_ASSOC); $i = $i + 1) {
                            $row[$i]['player'] = $resultArr['player'];
                            $p = $this->plugin->getServer()->getPlayerExact($row[$i]['player']);
                            if ($p instanceof Player) {
                                $p->sendMessage(TextFormat::ITALIC . TextFormat::RED . "" . TextFormat::AQUA . " <$rank> " . TextFormat::GREEN . "<$player> " . "-> " .TextFormat::ITALIC . TextFormat::DARK_AQUA . $message .  TextFormat::RESET);
  
                            }
                        }
                    }


                    ////////////////////////////// ALLY SYSTEM ////////////////////////////////
                    if (strtolower($args[0] == "enemywith")) {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g enemywith <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be the leader to do this"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) == $args[1]) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds can not enemy with itself"));
                            return true;
                        }
                        if ($this->plugin->areAllies($this->plugin->getPlayerFaction($player), $args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds is already enemied with $args[1]"));
                            return true;
                        }
                        $fac = $this->plugin->getPlayerFaction($player);
                        $leader = $this->plugin->getServer()->getPlayerExact($this->plugin->getLeader($args[1]));

                        if (!($leader instanceof Player)) {
                            $sender->sendMessage($this->plugin->formatMessage("The leader of the requested guilds is offline"));
                            return true;
                        }
                        $this->plugin->setEnemies($fac, $args[1]);
                        $sender->sendMessage($this->plugin->formatMessage("You are now enemies with $args[1]!", true));
                        $leader->sendMessage($this->plugin->formatMessage("The leader of $fac has declared your guilds as an enemy", true));
                    }
                    if (strtolower($args[0] == "allywith")) {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g allywith <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be the leader to do this"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) == $args[1]) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds can not ally with itself"));
                            return true;
                        }
                        if ($this->plugin->areAllies($this->plugin->getPlayerFaction($player), $args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds is already allied with $args[1]"));
                            return true;
                        }
                        $fac = $this->plugin->getPlayerFaction($player);
                        $leader = $this->plugin->getServer()->getPlayerExact($this->plugin->getLeader($args[1]));
                        $this->plugin->updateAllies($fac);
                        $this->plugin->updateAllies($args[1]);

                        if (!($leader instanceof Player)) {
                            $sender->sendMessage($this->plugin->formatMessage("The leader of the requested guilds is offline"));
                            return true;
                        }
                        if ($this->plugin->getAlliesCount($args[1]) >= $this->plugin->getAlliesLimit()) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds has the maximum amount of allies", false));
                            return true;
                        }
                        if ($this->plugin->getAlliesCount($fac) >= $this->plugin->getAlliesLimit()) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds has the maximum amount of allies", false));
                            return true;
                        }
                        $stmt = $this->plugin->db->prepare("INSERT OR REPLACE INTO alliance (player, faction, requestedby, timestamp) VALUES (:player, :faction, :requestedby, :timestamp);");
                        $stmt->bindValue(":player", $leader->getName());
                        $stmt->bindValue(":faction", $args[1]);
                        $stmt->bindValue(":requestedby", $sender->getName());
                        $stmt->bindValue(":timestamp", time());
                        $result = $stmt->execute();
                        $sender->sendMessage($this->plugin->formatMessage("You requested to ally with $args[1]!\nWait for the leader's response...", true));
                        $leader->sendMessage($this->plugin->formatMessage("The leader of $fac requested an alliance.\nType /g allyok to accept or /g allyno to deny.", true));
                    }
                    if (strtolower($args[0] == "breakalliancewith")) {
                        if (!isset($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Sử dụng: /g breakalliancewith <guilds>"));
                            return true;
                        }
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be the leader to do this"));
                            return true;
                        }
                        if (!$this->plugin->factionExists($args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                            return true;
                        }
                        if ($this->plugin->getPlayerFaction($player) == $args[1]) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds can not break alliance with itself"));
                            return true;
                        }
                        if (!$this->plugin->areAllies($this->plugin->getPlayerFaction($player), $args[1])) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds is not allied with $args[1]"));
                            return true;
                        }

                        $fac = $this->plugin->getPlayerFaction($player);
                        $leader = $this->plugin->getServer()->getPlayerExact($this->plugin->getLeader($args[1]));
                        $this->plugin->deleteAllies($fac, $args[1]);
                        $this->plugin->deleteAllies($args[1], $fac);
                        $this->plugin->subtractFactionPower($fac, $this->plugin->prefs->get("PowerGainedPerAlly"));
                        $this->plugin->subtractFactionPower($args[1], $this->plugin->prefs->get("PowerGainedPerAlly"));
                        $this->plugin->updateAllies($fac);
                        $this->plugin->updateAllies($args[1]);
                        $sender->sendMessage($this->plugin->formatMessage("Your faction $fac is no longer allied with $args[1]", true));
                        if ($leader instanceof Player) {
                            $leader->sendMessage($this->plugin->formatMessage("The leader of $fac broke the alliance with your guilds $args[1]", false));
                        }

                    }
                    if (strtolower($args[0] == "allies")) {
                        if (!isset($args[1])) {
                            if (!$this->plugin->isInFaction($player)) {
                                $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                                return true;
                            }

                            $this->plugin->updateAllies($this->plugin->getPlayerFaction($player));
                            $this->plugin->getAllAllies($sender, $this->plugin->getPlayerFaction($player));
                        } else {
                            if (!$this->plugin->factionExists($args[1])) {
                                $sender->sendMessage($this->plugin->formatMessage("The requested guilds doesn't exist"));
                                return true;
                            }
                            $this->plugin->updateAllies($args[1]);
                            $this->plugin->getAllAllies($sender, $args[1]);
                        }
                    }
                    if (strtolower($args[0] == "allyok")) {
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be a leader to do this"));
                            return true;
                        }
                        $lowercaseName = ($player);
                        $result = $this->plugin->db->query("SELECT * FROM alliance WHERE player='$lowercaseName';");
                        $array = $result->fetchArray(SQLITE3_ASSOC);
                        if (empty($array) == true) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds has not been requested to ally with any guilds"));
                            return true;
                        }
                        $allyTime = $array["timestamp"];
                        $currentTime = time();
                        if (($currentTime - $allyTime) <= 60) { //This should be configurable
                            $requested_fac = $this->plugin->getPlayerFaction($array["requestedby"]);
                            $sender_fac = $this->plugin->getPlayerFaction($player);
                            $this->plugin->setAllies($requested_fac, $sender_fac);
                            $this->plugin->setAllies($sender_fac, $requested_fac);
                            $this->plugin->addFactionPower($sender_fac, $this->plugin->prefs->get("PowerGainedPerAlly"));
                            $this->plugin->addFactionPower($requested_fac, $this->plugin->prefs->get("PowerGainedPerAlly"));
                            $this->plugin->db->query("DELETE FROM alliance WHERE player='$lowercaseName';");
                            $this->plugin->updateAllies($requested_fac);
                            $this->plugin->updateAllies($sender_fac);
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds has successfully allied with $requested_fac", true));
                            $this->plugin->getServer()->getPlayerExact($array["requestedby"])->sendMessage($this->plugin->formatMessage("$player from $sender_fac has accepted the alliance!", true));
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("Request has timed out"));
                            $this->plugin->db->query("DELETE * FROM alliance WHERE player='$lowercaseName';");
                        }
                    }
                    if (strtolower($args[0]) == "allyno") {
                        if (!$this->plugin->isInFaction($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be in a guilds to do this"));
                            return true;
                        }
                        if (!$this->plugin->isLeader($player)) {
                            $sender->sendMessage($this->plugin->formatMessage("You must be a leader to do this"));
                            return true;
                        }
                        $lowercaseName = ($player);
                        $result = $this->plugin->db->query("SELECT * FROM alliance WHERE player='$lowercaseName';");
                        $array = $result->fetchArray(SQLITE3_ASSOC);
                        if (empty($array) == true) {
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds has not been requested to ally with any guilds"));
                            return true;
                        }
                        $allyTime = $array["timestamp"];
                        $currentTime = time();
                        if (($currentTime - $allyTime) <= 60) { //This should be configurable
                            $requested_fac = $this->plugin->getPlayerFaction($array["requestedby"]);
                            $sender_fac = $this->plugin->getPlayerFaction($player);
                            $this->plugin->db->query("DELETE FROM alliance WHERE player='$lowercaseName';");
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds has successfully declined the alliance request.", true));
                            $this->plugin->getServer()->getPlayerExact($array["requestedby"])->sendMessage($this->plugin->formatMessage("$player from $sender_fac has declined the alliance!"));
                        } else {
                            $sender->sendMessage($this->plugin->formatMessage("Request has timed out"));
                            $this->plugin->db->query("DELETE * FROM alliance WHERE player='$lowercaseName';");
                        }
                    }


///////////////////////////////////////
                    ///////////////EFFFECTS?//////////////////////////
                    $amp = 0;
                    $strengthperkill = $this->plugin->prefs->get("PowerGainedPerKillingAnEnemy");
                    $lvl = array($strengthperkill*100,$strengthperkill*500,$strengthperkill*1000,$strengthperkill*5000);
                    if(strtolower($args[0]) == 'setef'){
                        if(!isset($args[1])){
                            $sender->sendMessage($this->plugin->formatMessage("/g setef <fast:str:jump:haste:res:life>"));
							return true;
                        }
                        if(!$this->plugin->isInFaction($player)){
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này!"));
							return true;
                        }
                        if(!$this->plugin->isLeader($player)) {
							$sender->sendMessage($this->plugin->formatMessage("You must be leader to use this."));
							return true;
						}
                        $factionname = $this->plugin->getPlayerFaction($player);
                        $factionstrength = $this->plugin->getFactionPower($factionname);
                        $strengthperkill = $this->plugin->prefs->get("PowerGainedPerKillingAnEnemy");
                        if($factionstrength < $lvl[0]){
                            $needed_power = $lvl[0];
							$sender->sendMessage($this->plugin->formatMessage("Your guilds doesn't have enough GuildsPoints to select an effect."));
							$sender->sendMessage($this->plugin->formatMessage("$needed_power GuildsPoints is required but your guilds has only $factionstrength GuildsPoints."));
							return true;
                        }
                        if(!(in_array(strtolower($args[1]),array("fast","str","jump","haste","res","life")))){
                            $sender->sendMessage($this->plugin->formatMessage("The '$args[1]' mode is not available."));
                            $sender->sendMessage($this->plugin->formatMessage("/g setef <fast:str:jump:haste:res:life>"));
							return true;
                        }
                        $this->plugin->addEffectTo($this->plugin->getPlayerFaction($player),strtolower($args[1]));
                        $this->plugin->updateTagsAndEffectsOf($factionname);
                        $sender->sendMessage($this->plugin->formatMessage("Successfully updated your guilds's effect.",true));
                        return true;
                    }
                    if(strtolower($args[0]) == 'efinfo'){
                        for($i=0;$i<4;$i++){
                            $s = $i + 1;
                            $sender->sendMessage($this->plugin->formatMessage("Lvl $s effects unlock at $lvl[$i] GuildsPoints",true));
                        }
                        return true;
                    }
                    if(strtolower($args[0]) == 'getef'){
                        if(!$this->plugin->isInFaction($player)){
                            $sender->sendMessage($this->plugin->formatMessage("Bạn phải có 1 guild mới dùng được lệnh này!"));
							return true;
                        }
                        $factionname = $this->plugin->getPlayerFaction($player);
                        $factionstrength = $this->plugin->getFactionPower($factionname);
                        if($this->plugin->getEffectOf($factionname) == "none"){
                            $sender->sendMessage($this->plugin->formatMessage("Your guilds's effect is not set. Set it by typing /g setef <effect>"));
                            return true;
                        }
                        $sender->removeAllEffects();
                        for($i=0;$i<4;$i++){
                            if($factionstrength >= $lvl[$i]){
                                $amp = $i;
                            }
                        }
                        switch($this->plugin->getEffectOf($factionname)){
                            case "fast":
                                $sender->addEffect(Effect::getEffect(1)->setDuration(PHP_INT_MAX)->setAmplifier($amp)->setVisible(false));
                                break;
                            case "str":
                                $sender->addEffect(Effect::getEffect(5)->setDuration(PHP_INT_MAX)->setAmplifier($amp)->setVisible(false));
                                break;
                            case "jump":
                                $sender->addEffect(Eddect::getEffect(8)->setDuration(PHP_INT_MAX)->setAmplifier($amp)->setVisible(false));
                                break;
                            case "haste":
                                $sender->addEffect(Effect::getEffect(3)->setDuration(PHP_INT_MAX)->setAmplifier($amp)->setVisible(false));
                                break;
                            case "res":
                                $sender->addEffect(Effect::getEffect(11)->setDuration(PHP_INT_MAX)->setAmplifier($amp)->setVisible(false));
                                break;
                            case "life":
                                $sender->addEffect(Effect::getEffect(21)->setDuration(PHP_INT_MAX)->setAmplifier($amp)->setVisible(false));
                                break;
                        }  
                        $sender->sendMessage($this->plugin->formatMessage("Enjoy your effect!", true));
                        return true;
                        }







                    /////////////////////////////// ABOUT ///////////////////////////////

                    if (strtolower($args[0] == 'about')) {
                        $sender->sendMessage(TextFormat::GREEN . "§l§b»§r\n eThis Server Using A Guilds System.\n §eBe The Most Powerfull Guilds In This Server!\n §eCreate,Join,Destroy A Guilds!\n §eStart Now By Using This Commands! : /g help [page]\n§l§b« ");
                        $sender->sendMessage(TextFormat::GOLD . "\n\n§aDevelop By GamerXzavier.");
                    }
                    //Thanks To The original authors Tethered_
                    //Thank To The Supporter
                    //Big Thanks To NeuroBinds Project Corporation For Helping 64% Of The Code!
                }
            }
        } else {
            $this->plugin->getServer()->getLogger()->info($this->plugin->formatMessage("Please run command in game"));
        }
    }

}
