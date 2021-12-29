<?PHP
/*
  Matrix制作
  情绪出售本插件，本插件开源，免费
*/

namespace ItemCommand;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;

use onebone\economyapi\EconomyAPI;

   class ItemCommand extends PluginBase implements Listener
{

   public function onEnable()
 {
@mkdir($this->getDataFolder());
   $this->config = new Config($this->getDataFolder(). "Config.yml", Config::YAML, array("0:0",
"126:0" => "help"));
   $this->getServer()->getPluginManager()->registerEvents($this,$this);
   $this->getLogger()->info(TextFormat::GOLD."Enable!");
}
   
   public function PlayerInteract(PlayerInteractEvent $event)
{
   $player = $event->getPlayer();
   $id = $player->getInventory()->getItemInHand()->getID();
   $damage = $player->getInventory()->getItemInHand()->getDamage();
   $money = EconomyAPI::getInstance()->getAllMoney();
   if($this->config->exists($id.":".$damage))
{
   $event->setCancelled();
   $command = $this->config->get($id.":".$damage);
   Server::getInstance()->dispatchCommand($player, $command);
}
}  
}