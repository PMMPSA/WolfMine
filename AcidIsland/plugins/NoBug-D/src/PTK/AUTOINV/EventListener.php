<?php

/**
 * AutoInv EventListener class
 * 
 * Created on Mar 24, 2016 at 10:12:22 PM
 *
 * @author PTK-KienPham
 */

namespace PTK\AUTOINV;

use pocketmine\event\Listener;
use pocketmine\inventory\InventoryHolder;
use pocketmine\block\Block;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\math\Vector3;
use PTK\AUTOINV\Main;

class EventListener implements Listener {
        
        /** @var $plugin Main */
        private $plugin;
        
        /**
         * Construct a new event listener class
         * 
         * @param Main $plugin
         */
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        }
        
        /**
         * Get the owning plugin
         * 
         * @return Main
         */
        public function getPlugin() {
                return $this->plugin;
        }
        
        /**
         * Handles autoinv block breaking
         * 
         * @param BlockBreakEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onBreak(BlockBreakEvent $event) {
                if($event->isCancelled()) {
                $event->getPlayer()->sendMessage("§cBạn không thể đập block này");
                        return;
                } else {
                        $p = $event->getPlayer();
        $b = $event->getBlock();
        $block = new Vector3($b->getX(), $b->getY(), $b->getZ());
        $b->getLevel()->setBlock($block, Block::get(0));
                        foreach($event->getDrops() as $drop) {
                                $event->getPlayer()->getInventory()->addItem($drop);
                        }
                }
                $event->setDrops([]);
        }
        /**
         * Handles autoinv entity exploding
         * 
         * @param EntityExplodeEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onExplode(EntityExplodeEvent $event) {
                if($event->isCancelled()) {
                        return;
                } else {
                        $explosive = $event->getEntity();
                        $closest = PHP_INT_MAX;
                        $entity = null;
                        foreach($explosive->getLevel()->getNearbyEntities($explosive->getBoundingBox()->grow(24, 24, 24)) as $nearby) {
                                if($explosive->distance($nearby) <= $closest) {
                                        $entity = $nearby;
                                }
                        }
                        $disallowed = [
                            Block::TNT,
                            Block::FIRE,
                            Block::LAVA,
                            Block::WATER,
                            Block::STILL_LAVA,
                            Block::STILL_WATER,
                        ];
                        $blocks = $event->getBlockList();
                        if($nearby instanceof InventoryHolder) {
                                foreach($blocks as $key => $block) {
                                        if(isset($disallowed[$block->getId()])) {
                                                continue;
                                        }
                                        $nearby->getInventory()->addItem($block);
                                        unset($blocks[$key]);
                                }
                        }
                        $event->setBlockList($blocks);
                }
                return;
        }
}
