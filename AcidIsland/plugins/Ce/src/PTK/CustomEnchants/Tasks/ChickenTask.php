<?php

namespace PTK\CustomEnchants\Tasks;


use PTK\CustomEnchants\CustomEnchants\CustomEnchants;
use PTK\CustomEnchants\Main;
use pocketmine\item\Item;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

/**
 * Class ChickenTask
 * @package PTK\CustomEnchants\Tasks
 */
class ChickenTask extends PluginTask
{
    private $plugin;

    /**
     * ChickenTask constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct($plugin);
    }

    /**
     * @param int $currentTick
     */
    public function onRun( $currentTick)
    {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $enchantment = $this->plugin->getEnchantment($player->getInventory()->getChestplate(), CustomEnchants::CHICKEN);
            if ($enchantment !== null) {
                if (!isset($this->plugin->chickenTick[strtolower($player->getName())])) {
                    $this->plugin->chickenTick[strtolower($player->getName())] = 0;
                }
                $this->plugin->chickenTick[strtolower($player->getName())]++;
                if ($this->plugin->chickenTick[strtolower($player->getName())] >= 5 * 1200) {
                    $random = mt_rand(0, 100);
                    if ($random <= 5 * $enchantment->getLevel()) {
                        $drops = $this->plugin->getConfig()->getNested("chicken.rare-drop");
                        if (!is_array($drops)) {
                            $drops = ["266:0:1"];
                        }
                        $drop = array_rand($drops, 1);
                        $drop = explode(":", $drops[$drop]);
                        $item = count($drop) < 3 ? Item::get(Item::GOLD_INGOT, 0, 1) : Item::get($drop[0], $drop[1], $drop[2]);
                        $player->getLevel()->dropItem($player, $item, $player->getDirectionVector()->multiply(-0.4));
                        $player->sendTip(TextFormat::GREEN . "You have layed a " . $item->getName() . "...");
                    } else {
                        $player->getLevel()->dropItem($player, Item::get(Item::EGG, 0, 1), $player->getDirectionVector()->multiply(-0.4));
                        $player->sendTip(TextFormat::GREEN . "You have layed an egg.");
                    }
                    $this->plugin->chickenTick[strtolower($player->getName())] = 0;
                }
            }
        }
    }
}