<?php

namespace PTK\CustomEnchants\Tasks;

use PTK\CustomEnchants\CustomEnchants\CustomEnchants;
use PTK\CustomEnchants\Main;
use pocketmine\entity\Item;
use pocketmine\entity\projectile\Projectile;
use pocketmine\level\particle\FlameParticle;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;

/**
 * Class ForcefieldTask
 * @package PTK\CustomEnchants\Tasks
 */
class ForcefieldTask extends PluginTask
{
    private $plugin;

    /**
     * ForcefieldTask constructor.
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
            $forcefields = 0;
            foreach ($player->getInventory()->getArmorContents() as $armor) {
                $enchantment = $this->plugin->getEnchantment($armor, CustomEnchants::FORCEFIELD);
                if ($enchantment !== null) {
                    $forcefields++;
                }
            }
            if ($forcefields > 0) {
                $entities = $player->getLevel()->getNearbyEntities($player->getBoundingBox()->grow($forcefields * 0.75, $forcefields * 0.75, $forcefields * 0.75), $player);
                foreach ($entities as $entity) {
                    if ($entity instanceof Projectile) {
                        if ($entity->getOwningEntity() !== $player) {
                            $entity->setMotion($entity->getMotion()->multiply(-1));
                        }
                    } else {
                        if (!$entity instanceof Item) {
                            $entity->setMotion(new Vector3($entity->getDirectionVector()->x * -0.75, 0, $entity->getDirectionVector()->y * -0.75));
                        }
                    }
                }
                if (!isset($this->plugin->forcefieldParticleTick[strtolower($player->getName())])) {
                    $this->plugin->forcefieldParticleTick[strtolower($player->getName())] = 0;
                }
                $this->plugin->forcefieldParticleTick[strtolower($player->getName())]++;
                if ($this->plugin->forcefieldParticleTick[strtolower($player->getName())] >= 5) {
                    $radius = $forcefields * 0.75;
                    $diff = 5;
                    for ($theta = 0; $theta <= 360; $theta += $diff) {
                        $x = $radius * sin($theta);
                        $y = 0.5;
                        $z = $radius * cos($theta);
                        $pos = $player->add($x, $y, $z);
                        $player->getLevel()->addParticle(new FlameParticle($pos));
                    }
                    $this->plugin->forcefieldParticleTick[strtolower($player->getName())] = 0;
                }
            }
        }
    }
}