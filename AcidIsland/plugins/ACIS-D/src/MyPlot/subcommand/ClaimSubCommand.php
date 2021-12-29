<?php
namespace MyPlot\subcommand;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;

class ClaimSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.claim");
    }

    public function execute(CommandSender $sender, $label, array $args) {
        if (count($args) > 1) {
            return false;
        }
        $name = "";
        if (isset($args[0])) {
            $name = $args[0];
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner != "") {
            if ($plot->owner === $sender->getName()) {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.yourplot"));
            } else {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.alreadyclaimed", [$plot->owner]));
            }
            return true;
        }

        $maxPlots = $this->getPlugin()->getMaxPlotsOfPlayer($player);
        $plotsOfPlayer = count($this->getPlugin()->getProvider()->getPlotsByOwner($player->getName()));
        if ($plotsOfPlayer >= $maxPlots) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.maxplots", [$maxPlots]));
            return true;
        }

        $plotLevel = $this->getPlugin()->getLevelSettings($plot->levelName);
        $economy = $this->getPlugin()->getEconomyProvider();
        if ($economy !== null and !$economy->reduceMoney($player, $plotLevel->claimPrice)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.nomoney"));
            return true;
        }

        $plot->owner = $sender->getName();
        $plot->name = $name;
        if ($this->getPlugin()->getProvider()->savePlot($plot)) {
            
			
			$f = $this->getPlugin()->getPlotPosition($plot);
			$plotSize = $this->getPlugin()->getLevelSettings($plot->levelName)->plotSize;
			$f->x += floor($plotSize / 2) - 2;
			$f->z -= -88;
			$f->y += 1;
			
			$sender->getLevel()->setBlock(new \pocketmine\math\Vector3($f->x, $f->y, $f->z), \pocketmine\block\Block::get(\pocketmine\block\Block::CHEST), true, true);
			
			$nbt = new CompoundTag("", [
			new ListTag("Items", []),
			new StringTag("id", Tile::CHEST),
			new IntTag("x", $f->x),
			new IntTag("y", $f->y),
			new IntTag("z", $f->z)
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);
		
		//$sender->getLevel()->getChunk($sender->getX() >> 4, $sender->getZ() >> 4) <- nếu chuyển qua genisys thì sửa cái này vào $sender->getLevel()
		$tile = Tile::createTile("Chest",$sender->getLevel(), $nbt);
			//Tạo Chest
			$i = $tile->getInventory();
			$ip = $player->getInventory();
			//$i->addItem($ie1);
			//351:15
			//383:12
			$i->addItem(Item::get(257, 5, 1));
			$i->addItem(Item::get(2, 0, 10));
			$i->addItem(Item::get(8, 0, 5));
			$i->addItem(Item::get(85, 0, 5));
            $sender->sendTitle("§cĐây đã là đảo của bạn\n§f Chơi vui vẻ");
			} else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
        return true;
    }
}