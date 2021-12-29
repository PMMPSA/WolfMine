<?php
namespace PTK\DonRac\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as F;
use PTK\DonRac\DRMain;

/**
 * Class DonRacCommandExecutor
 * @package PTK\DonRac\Command
 */
class DonRacCommandExecutor
{
    /**
     * @param CommandSender $s
     * @param Command $cmd
     * @param array $args
     */
    function __construct(CommandSender $s, Command $cmd, array $args)
    {
        $this->executeCommand($s, $cmd, $args);
    }

    /**
     * @param CommandSender $s
     * @param Command $cmd
     * @param array $args
     * @return bool
     */
    private function executeCommand(CommandSender $s, Command $cmd, array $args)
    {
        $main = DRMain::getInstance();
        $entitiesManager = $main->getEntityManager();
        switch ($cmd->getName()) {
            case"clearlag":
                if (!isset($args[0])) {
                    $s->sendMessage("[Dọn Rác] Phiên bản đang chạy:" . $main->getDescription()->getVersion() . "\n /donrac xoa - Xóa rác trên mặt đất");
                    return true;
                }
                if (!in_array(strtolower($args[0]), array("all"))) {
                    $s->sendMessage(F::RED . "[Dọn Rác] Hãy dùng /donrac ' $args[0] '!");
                    return true;
                }
                switch (array_shift($args)) {
                    case "all":
                        foreach($s->getServer()->getOnlinePlayers() as $p){
							if($p != $s){
$p->kick("Server clear lag", false);
$s->kick("Server clear lag", false);
                        return true;
                        break;
}
                }
				}
                break;
        }
        return true;
    }
}