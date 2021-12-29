<?php
    
    /*This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.*/
    
    declare(strict_types=1);
    
    namespace PJZ9n\AntiFrameDrop;
    
    use pocketmine\event\block\BlockBreakEvent;
    use pocketmine\event\Listener;
    use pocketmine\event\server\DataPacketReceiveEvent;
    use pocketmine\math\Vector3;
    use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
    use pocketmine\plugin\PluginBase;
    use pocketmine\Server;
    
    class Main extends PluginBase implements Listener
    {
        
        public function onEnable(): void
        {
            Server::getInstance()->getPluginManager()->registerEvents($this, $this);
            //
        }
        
        public function onDataPacketReceive(DataPacketReceiveEvent $event): void
        {
            $player = $event->getPlayer();
            $packet = $event->getPacket();
            if (!$packet instanceof ItemFrameDropItemPacket) {
                return;
            }
            $frameItemPos = new Vector3($packet->x, $packet->y, $packet->z);
            $this->getLogger()->debug("Frame Pos: {$packet->x}, {$packet->y}, {$packet->z}");
            $blockBreakEvent = new BlockBreakEvent($player, $player->getLevel()->getBlock($frameItemPos),
                $player->getInventory()->getItemInHand(), false, []);
            try {
                $blockBreakEvent->call();
            } catch (\ReflectionException $e) {
                //
            }
            $this->getLogger()->debug("Dummy BlockBreakEvent Called By Plugin.");
            if ($blockBreakEvent->isCancelled()) {
                $event->setCancelled();
                $this->getLogger()->debug("Dummy BlockBreakEvent is cancelled.");
            }
        }
        
    }