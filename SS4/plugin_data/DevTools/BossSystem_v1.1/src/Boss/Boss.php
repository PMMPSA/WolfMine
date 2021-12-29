<?php

namespace Boss;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\hostile\Zombie;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\level\sound\ClickSound;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
class Boss extends PluginBase implements BossData, Listener{

    /** @var Zombie */
    public $boss;

    /** @var EconomyAPI */
    public $economy;

    /** @var Config */
    public $config;

    /** @var array */
    public $property;

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        if(!class_exists('onebone\economyapi\EconomyAPI')){
            $this->getLogger()->warning(TextFormat::RED . "EconomyAPI Found. Closing Plugin.");
            $this->onEnableStateChange(false);
        }
        $this->economy = EconomyAPI::getInstance();
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $this->getScheduler()->scheduleRepeatingTask(new BossTask($this), 20 * 60);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if($sender instanceof Player){
            if($sender->isOp()){
                $hash = $sender->getLevel()->getFolderName() . ':' . $sender->getFloorX() . ':' . $sender->getFloorY() . ':' . $sender->getFloorZ();

                $this->config->set('position', $hash);
                $this->config->save();

                $sender->sendMessage(TextFormat::GREEN . 'Genesis Area Determined.');
            }else{
                $sender->sendMessage(TextFormat::RED . "You are not authorized to use this command.");
            }
        }else{
            $sender->sendMessage(TextFormat::RED . "Please use this command in the game.");
        }
        return true;
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event){
        $zombie = $event->getEntity();
        $damager = $event->getDamager();
        if($this->boss instanceof Zombie){
            if($zombie instanceof Zombie and $damager instanceof Player){
                if($event->getFinalDamage() >= $zombie->getHealth()){
                    $damager->getInventory()->addItem(Item::get(self::BOSS_PRIZE_ITEM_ID, 0, self::BOSS_PRIZE_ITEM_COUNT));
                    $this->economy->addMoney($damager, self::BOSS_PRIZE_MONEY);

                    $damager->sendMessage(TextFormat::GREEN . "Congratulations! You killed Boss!");
                    $this->getServer()->broadcastMessage(TextFormat::GOLD . $damager->getName() . TextFormat::GREEN . " He killed boss! Congratulations!");
                }else{
                    if(mt_rand(1, 10) > 7){
                        $damager->attack(($new = new EntityDamageByEntityEvent($zombie, $damager, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, (float) mt_rand(1, 4))));
                        $newEntity = $new->getEntity();
                        if($newEntity instanceof Player){
                            if($new->getFinalDamage() >= $newEntity->getHealth()){
                                $this->property[$newEntity->getName()] = 1;
                            }
                        }
                    }

                    $event->setBaseDamage($this->getDamage($damager->getInventory()->getItemInHand()->getId()));
                    $this->boss->setHealth($this->boss->getHealth() - $event->getFinalDamage());
                    $this->boss->setNameTag(TextFormat::GREEN . "Zombie Boss!" . TextFormat::EOL . TextFormat::RED . "Can: " . TextFormat::AQUA . $this->boss->getHealth() . TextFormat::DARK_GRAY . "/" . TextFormat::YELLOW . $this->boss->getMaxHealth());

                    $damager->getLevel()->addSound(new ClickSound($damager));
                    $damager->addTitle(TextFormat::WHITE . "- " . TextFormat::GOLD . $event->getFinalDamage());
                }
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event){
        $player = $event->getPlayer()->getName();
        if(isset($this->property[$player])){
            $event->setDeathMessage(TextFormat::GOLD . $player . TextFormat::AQUA . ", Zombie Boss" . TextFormat::RED . " Was killed by!");
            unset($this->property[$player]);
        }
    }

    public function getDamage(int $id) : ?int{
        return isset(self::DAMAGE_ROSTER[$id]) ? mt_rand(1, self::DAMAGE_ROSTER[$id]) : 1 /*$this->random_float(1, mt_rand(2, 5))*/;
    }

    /**
     * @deprecated
     *
     * @param int $min
     * @param int $max
     * @return float
     */
    public function random_float(int $min, int $max) : float{
        try{
            return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX);
        }catch(\Exception $e){
            throw new \Error("Error: " . $e->getMessage());
        }
    }

    public function getPositions() : array{
        $hash = $this->config->get('position');
        $arr = explode(':', $hash);

        $level = $this->getServer()->getLevelByName(($levelName = array_shift($arr)));
        if($level === null){
            $this->getServer()->loadLevel($levelName);
            $level = $this->getServer()->getLevelByName($levelName);
        }

        $vector3 = new Vector3(...array_map(function($coordinate) : int{ return (int) $coordinate; }, $arr));

        return [
            $level,
            $vector3
        ];
    }

    public function spawnToZombie() : Entity{
        $get = $this->getPositions();

        $boss =  Entity::createEntity(self::BOSS_TYPE, $get[0], Entity::createBaseNBT($get[1], null, 2, 2));
        $boss->setMaxHealth(self::BOSS_HEALTH);
        $boss->setHealth(self::BOSS_HEALTH);
        $boss->setScale(4.0);
        $boss->setNameTag(TextFormat::GREEN . "Zombie Boss!" . TextFormat::EOL . TextFormat::RED . "Can: " . TextFormat::AQUA . self::BOSS_HEALTH . TextFormat::DARK_GRAY . "/" . TextFormat::YELLOW . self::BOSS_HEALTH);
        $boss->spawnToAll();

        $this->getServer()->broadcastMessage(TextFormat::GREEN . "Zombie boss is delivered!");

        return $boss;
    }
}