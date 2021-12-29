<?php

namespace PTK\CustomEnchants;

use PTK\CustomEnchants\Blocks\PTKObsidian;
use PTK\CustomEnchants\Commands\CustomEnchantCommand;
use PTK\CustomEnchants\CustomEnchants\CustomEnchants;
/*use PTK\CustomEnchants\Entities\Fireball;
use PTK\CustomEnchants\Entities\PigProjectile;*/
use PTK\CustomEnchants\Tasks\CactusTask;
use PTK\CustomEnchants\Tasks\ChickenTask;
use PTK\CustomEnchants\Tasks\ForcefieldTask;
use PTK\CustomEnchants\Tasks\JetpackTask;
use PTK\CustomEnchants\Tasks\MeditationTask;
use PTK\CustomEnchants\Tasks\ParachuteTask;
use PTK\CustomEnchants\Tasks\ProwlTask;
use PTK\CustomEnchants\Tasks\RadarTask;
use PTK\CustomEnchants\Tasks\SizeTask;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

/**
 * Class Main
 * @package CustomEnchants
 */
class Main extends PluginBase
{
    const MAX_LEVEL = 0;
    const NOT_COMPATIBLE = 1;
    const NOT_COMPATIBLE_WITH_OTHER_ENCHANT = 2;

    const ROMAN_CONVERSION_TABLE = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1
    ];

    const COLOR_CONVERSION_TABLE = [
        "BLACK" => TextFormat::BLACK,
        "DARK_BLUE" => TextFormat::DARK_BLUE,
        "DARK_GREEN" => TextFormat::DARK_GREEN,
        "DARK_AQUA" => TextFormat::DARK_AQUA,
        "DARK_RED" => TextFormat::DARK_RED,
        "DARK_PURPLE" => TextFormat::DARK_PURPLE,
        "GOLD" => TextFormat::GOLD,
        "GRAY" => TextFormat::GRAY,
        "DARK_GRAY" => TextFormat::DARK_GRAY,
        "BLUE" => TextFormat::BLUE,
        "GREEN" => TextFormat::GREEN,
        "AQUA" => TextFormat::AQUA,
        "RED" => TextFormat::RED,
        "LIGHT_PURPLE" => TextFormat::LIGHT_PURPLE,
        "YELLOW" => TextFormat::YELLOW,
        "WHITE" => TextFormat::WHITE
    ];

    public $berserkercd;
    public $bountyhuntercd;
    public $cloakingcd;
    public $endershiftcd;
    public $growcd;
    public $jetpackcd;
    public $shrinkcd;
    public $vampirecd;

    public $growremaining;
    public $jetpackDisabled;
    public $shrinkremaining;

    public $blockface;
    public $breaking;
    public $chickenTick;
    public $grew;
    public $flying;
    public $flyremaining;
    public $forcefieldParticleTick;
    public $hallucination;
    public $jetpackChargeTick;
    public $meditationTick;
    public $mined;
    public $nofall;
    public $prowl;
    public $shrunk;


    public $enchants = [
        //id => ["name", "slot", "trigger", "rarity", maxlevel"]
        CustomEnchants::AERIAL => ["Aerial", "Weapons", "Damage", "Common", 5],
        CustomEnchants::AUTOREPAIR => ["Autorepair", "Damageable", "Move", "Uncommon", 5],
        CustomEnchants::BERSERKER => ["Berserker", "Armor", "Damaged", "Rare", 5],
        CustomEnchants::CLOAKING => ["Cloaking", "Armor", "Damaged", "Uncommon", 5],
        CustomEnchants::BLAZE => ["Blaze", "Bow", "Shoot", "Rare", 1],
        CustomEnchants::BLIND => ["Blind", "Weapons", "Damage", "Common", 5],
        CustomEnchants::BOUNTYHUNTER => ["Bounty Hunter", "Bow", "Damage", "Uncommon", 5],
        CustomEnchants::CACTUS => ["Cactus", "Armor", "Equip", "Rare", 1],
        CustomEnchants::CHARGE => ["Charge", "Weapons", "Damage", "Uncommon", 5],
        CustomEnchants::CHICKEN => ["Chicken", "Chestplate", "Equip", "Uncommon", 5],
        CustomEnchants::CRIPPLINGSTRIKE => ["Cripple", "Weapons", "Damage", "Common", 5],
        CustomEnchants::CRIPPLE => ["Cripple", "Weapons", "Damage", "Common", 5],
        CustomEnchants::CURSED => ["Cursed", "Armor", "Damaged", "Uncommon", 5],
        CustomEnchants::DEATHBRINGER => ["Deathbringer", "Weapons", "Damage", "Rare", 5],
        CustomEnchants::DISARMING => ["Disarming", "Weapons", "Damage", "Uncommon", 5],
        CustomEnchants::DRILLER => ["Driller", "Tools", "Break", "Uncommon", 5],
        CustomEnchants::DRUNK => ["Drunk", "Armor", "Damaged", "Rare", 5],
        CustomEnchants::ENDERSHIFT => ["Endershift", "Armor", "Damaged", "Rare", 5],
        CustomEnchants::ENERGIZING => ["Energizing", "Tools", "Break", "Uncommon", 5],
        CustomEnchants::ENLIGHTED => ["Enlighted", "Armor", "Damaged", "Uncommon", 5],
        CustomEnchants::EXPLOSIVE => ["Explosive", "Tools", "Break", "Rare", 10],
        CustomEnchants::FORCEFIELD => ["Forcefield", "Armor", "Equip", "Mythic", 1],
        CustomEnchants::FROZEN => ["Frozen", "Armor", "Damaged", "Rare", 5],
        CustomEnchants::GEARS => ["Gears", "Boots", "Equip", "Uncommon", 5],
        CustomEnchants::GLOWING => ["Glowing", "Helmets", "Equip", "Common", 1],
        CustomEnchants::GOOEY => ["Gooey", "Weapons", "Damage", "Uncommon", 5],
        CustomEnchants::GRAPPLING => ["Grappling", "Bow", "Projectile_Hit", "Rare", 1],
        CustomEnchants::GROW => ["Grow", "Armor", "Sneak", "Uncommon", 5],
        CustomEnchants::HALLUCINATION => ["Hallucination", "Weapons", "Damage", "Mythic", 5],
        CustomEnchants::HARDENED => ["Hardened", "Armor", "Damaged", "Uncommon", 5],
        CustomEnchants::HEADHUNTER => ["Headhunter", "Bow", "Damage", "Uncommon", 5],
        CustomEnchants::HEALING => ["Healing", "Bow", "Damage", "Rare", 1000],
        CustomEnchants::JETPACK => ["Jetpack", "Boots", "Sneak", "Rare", 8],
        CustomEnchants::LIFESTEAL => ["Lifesteal", "Weapons", "Damage", "Common", 5],
        CustomEnchants::LUMBERJACK => ["Lumberjack", "Axe", "Break", "Rare", 1],
        CustomEnchants::MAGMAWALKER => ["Magma Walker", "Boots", "Move", "Uncommon", 2],
        CustomEnchants::MEDITATION => ["Meditation", "Helmet", "Equip", "Uncommon", 5],
        CustomEnchants::MISSILE => ["Missile", "Bow", "Projectile_Hit", "Rare", 5],
        CustomEnchants::MOLOTOV => ["Molotov", "Bow", "Projectile_Hit", "Uncommon", 5],
        CustomEnchants::MOLTEN => ["Molten", "Armor", "Damaged", "Rare", 5],
        CustomEnchants::OBSIDIANSHIELD => ["Obsidian Shield", "Armor", "Equip", "Common", 5],
        CustomEnchants::PARACHUTE => ["Parachute", "Chestplate", "Equip", "Uncommon", 1],
        CustomEnchants::PARALYZE => ["Paralyze", "Bow", "Damage", "Rare", 5],
        CustomEnchants::PIERCING => ["Piercing", "Bow", "Damage", "Rare", 5],
        CustomEnchants::ANTIKNOCKBACK => ["Anti Knockback", "Armor", "Damage", "Rare", 1],
        CustomEnchants::POISON => ["Poison", "Weapons", "Damage", "Uncommon", 5],
        CustomEnchants::POISONED => ["Poisoned", "Armor", "Damaged", "Uncommon", 5],
        CustomEnchants::PORKIFIED => ["Porkified", "Bow", "Shoot", "Mythic", 3],
        CustomEnchants::PROWL => ["Prowl", "Chestplate", "Equip", "Rare", 1],
        CustomEnchants::QUICKENING => ["Quickening", "Tools", "Break", "Uncommon", 5],
        CustomEnchants::RADAR => ["Radar", "Compass", "Inventory", "Rare", 10],
        CustomEnchants::REVIVE => ["Revive", "Armor", "Death", "Rare", 5],
        CustomEnchants::REVULSION => ["Revulsion", "Armor", "Damaged", "Uncommon", 5],
        CustomEnchants::SELFDESTRUCT => ["Self Destruct", "Armor", "Damaged", "Rare", 5],
        CustomEnchants::SHRINK => ["Shrink", "Armor", "Sneak", "Uncommon", 2],
        CustomEnchants::SHUFFLE => ["Shuffle", "Bow", "Damage", "Rare", 1],
        CustomEnchants::SMELTING => ["Smelting", "Tools", "Break", "Uncommon", 1],
        CustomEnchants::SOULBOUND => ["Soulbound", "Global", "Death", "Mythic", 1],
        CustomEnchants::SPRINGS => ["Springs", "Boots", "Equip", "Uncommon", 5],
        CustomEnchants::STOMP => ["Stomp", "Boots", "Fall_Damage", "Uncommon", 5],
        CustomEnchants::TELEPATHY => ["Telepathy", "Tools", "Break", "Rare", 1],
        CustomEnchants::VAMPIRE => ["Vampire", "Weapons", "Damage", "Uncommon", 1],
        CustomEnchants::VOLLEY => ["Volley", "Bow", "Shoot", "Uncommon", 5],
        CustomEnchants::WITHER => ["Wither", "Weapons", "Damage", "Uncommon", 5]
    ];

    public function onEnable()
    {
        if (!$this->isSpoon()) {
            $this->initCustomEnchants();
            $this->saveDefaultConfig();
            $this->jetpackDisabled = $this->getConfig()->getNested("jetpack.disabled") ?? [];
            if (count($this->jetpackDisabled) > 0) {
                $this->getLogger()->info(TextFormat::RED . "Phù Phép JetPack Đã Được Tắt Trong Thế Giới " . implode(", ", $this->jetpackDisabled) . ".");
            }
            /*Entity::registerEntity(Fireball::class);
            Entity::registerEntity(PigProjectile::class);
            BlockFactory::registerBlock(new PTKObsidian(), true);*/
            $this->getServer()->getCommandMap()->register("customenchant", new CustomEnchantCommand("customenchant", $this));
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new CactusTask($this), 10);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new ChickenTask($this), 1);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new ForcefieldTask($this), 1);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new JetpackTask($this), 1);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new MeditationTask($this), 1);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new ParachuteTask($this), 3.9);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new ProwlTask($this), 1);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new RadarTask($this), 20);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new SizeTask($this), 20);
            $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

            $this->getLogger()->info(TextFormat::GREEN . "Plugi.");
        }
    }

    /**
     * Checks if server is using a spoon.
     *
     * @return bool
     */
    public function isSpoon()
    {
        if ($this->getServer()->getName() == "PocketMine-MP") {
            $this->getLogger()->error("Ái Chà Chà Có Vẻ Như Anh Bạn Đang Dùng PocketMine-MP Nhỉ. Khi Hoạt Động Trên PocketMine-MP Thì Plugin Này Sẽ Bị Lỗi! Hãy Sử Dụng GenisysPro");
            return true;
        }
        if ($this->getDescription()->getAuthors() !== ["PTK-KienPham"] || $this->getDescription()->getName() !== "CustomEnchants[PTK]") {
            $this->getLogger()->error("Đậu Xanh Nhà Mày Mày Dám Đổi Tên Với Author Plugin Của Tao Ak Đổi Lại Ngay.");
            return true;
        }
        return false;
    }

    public function initCustomEnchants()
    {
        CustomEnchants::init();
        foreach ($this->enchants as $id => $data) {
            $ce = $this->translateDataToCE($id, $data);
            CustomEnchants::registerEnchants($id, $ce);
        }
    }

    /**
     * Registers enchantment from id, name, trigger, rarity, and max level
     *
     * @param $id
     * @param $name
     * @param $type
     * @param $trigger
     * @param $rarity
     * @param $maxlevel
     */
    public function registerEnchantment($id, $name, $type, $trigger, $rarity, $maxlevel)
    {
        $data = [$name, $type, $trigger, $rarity, $maxlevel];
        $this->enchants[$id] = $data;
        $ce = $this->translateDataToCE($id, $data);
        CustomEnchants::registerEnchants($id, $ce);
    }

    /**
     * Translates data from strings to int
     *
     * @param $id
     * @param $data
     * @return CustomEnchants
     */
    public function translateDataToCE($id, $data)
    {
        $slot = CustomEnchants::SLOT_NONE;
        switch ($data[1]) {
            case "Global":
                $slot = CustomEnchants::SLOT_ALL;
                break;
            case "Weapons":
                $slot = CustomEnchants::SLOT_SWORD;
                break;
            case "Bow":
                $slot = CustomEnchants::SLOT_BOW;
                break;
            case "Tools":
                $slot = CustomEnchants::SLOT_TOOL;
                break;
            case "Pickaxe":
                $slot = CustomEnchants::SLOT_PICKAXE;
                break;
            case "Axe":
                $slot = CustomEnchants::SLOT_AXE;
                break;
            case "Armor":
                $slot = CustomEnchants::SLOT_ARMOR;
                break;
            case "Helmets":
                $slot = CustomEnchants::SLOT_HEAD;
                break;
            case "Chestplate":
                $slot = CustomEnchants::SLOT_TORSO;
                break;
            case "Leggings":
                $slot = CustomEnchants::SLOT_LEGS;
                break;
            case "Boots":
                $slot = CustomEnchants::SLOT_FEET;
                break;
            case "Compass":
                $slot = CustomEnchants::SLOT_COMPASS;
                break;
        }
        $rarity = CustomEnchants::RARITY_COMMON;
        switch ($data[3]) {
            case "Common":
                $rarity = CustomEnchants::RARITY_COMMON;
                break;
            case "Uncommon":
                $rarity = CustomEnchants::RARITY_UNCOMMON;
                break;
            case "Rare":
                $rarity = CustomEnchants::RARITY_RARE;
                break;
            case "Mythic":
                $rarity = CustomEnchants::RARITY_MYTHIC;
                break;
        }
        $ce = new CustomEnchants($id, $data[0], $rarity, CustomEnchants::ACTIVATION_SELF, $slot);
        return $ce;
    }

    /**
     * Get enchantment on an item with specific id. Returns null if not found.
     *
     * @param Item $item
     * @param $id
     * @return null|CustomEnchants
     */
    public function getEnchantment(Item $item, $id)
    {
        if (!$item->hasEnchantments()) {
            return null;
        }
        foreach ($item->getNamedTag()->ench as $entry) {
            if ($entry["id"] === $id) {
                $e = CustomEnchants::getEnchantment($entry["id"]);
                $e->setLevel($entry["lvl"]);
                return $e;
            }
        }
        return null;
    }

    /**
     * Adds enchantment to item
     *
     * @param Item $item
     * @param $enchants
     * @param $levels
     * @param bool $check
     * @param CommandSender|null $sender
     * @return Item
     */
    public function addEnchantment(Item $item, $enchants, $levels, $check = true, CommandSender $sender = null)
    {
        //TODO: Check if item can get enchant
        if (!is_array($enchants)) {
            $enchants = [$enchants];
        }
        if (!is_array($levels)) {
            $levels = [$levels];
        }
        if (count($enchants) > count($levels)) {
            for ($i = 0; $i <= count($enchants) - count($levels); $i++) {
                array_push($levels, 1);
            }
        }
        $combined = array_combine($enchants, $levels);
        foreach ($enchants as $enchant) {
            $level = $combined[$enchant];
            if (!$enchant instanceof CustomEnchants) {
                if (is_numeric($enchant)) {
                    $enchant = CustomEnchants::getEnchantment((int)$enchant);
                } else {
                    $enchant = CustomEnchants::getEnchantByName($enchant);
                }
            }
            if ($enchant == null) {
                if ($sender !== null) {
                    $sender->sendMessage(TextFormat::RED . "Phù Phép Không Tồn Tại.");
                }
                continue;
            }
            $result = $this->canBeEnchanted($item, $enchant, $level);
            if ($result === true || $check !== true) {
                $enchant->setLevel($level);
                if (!$item->hasCompoundTag()) {
                    $tag = new CompoundTag("", []);
                } else {
                    $tag = $item->getNamedTag();
                }
                if (!isset($tag->ench)) {
                    $tag->ench = new ListTag("ench", []);
                    $tag->ench->setTagType(NBT::TAG_Compound);
                }
                $found = false;
                foreach ($tag->ench as $k => $entry) {
                    if ($entry["id"] === $enchant->getId()) {
                        $tag->ench->{$k} = new CompoundTag("", [
                            "id" => new ShortTag("id", $enchant->getId()),
                            "lvl" => new ShortTag("lvl", $enchant->getLevel())
                        ]);
                        $item->setNamedTag($tag);
                        $item->setCustomName(str_replace($this->getRarityColor($enchant->getRarity()) . $enchant->getName() . " " . $this->getRomanNumber($entry["lvl"]), $this->getRarityColor($enchant->getRarity()) . $enchant->getName() . " " . $this->getRomanNumber($enchant->getLevel()), $item->getName()));
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $tag->ench->{count($tag->ench->getValue()) + 1} = new CompoundTag($enchant->getName(), [
                        "id" => new ShortTag("id", $enchant->getId()),
                        "lvl" => new ShortTag("lvl", $enchant->getLevel())
                    ]);
                    $level = $this->getRomanNumber($enchant->getLevel());
                    $item->setNamedTag($tag);
                    $item->setCustomName(TextFormat::RESET."§r§e".$item->getName() . "\n§a•§d>>" . $this->getRarityColor($enchant->getRarity()) . $enchant->getName() . " " . $level);
                }
                if ($sender !== null) {
                    $sender->sendMessage(TextFormat::GREEN . "§e•§d> §bBạn đã nâng cấp thành công item");
                }
                continue;
            }
            if ($sender !== null) {
                if ($result == self::NOT_COMPATIBLE) {
                    $sender->sendMessage(TextFormat::RED . "§a•§c Bạn không thể nâng cấp với vật phẩm này");
                }
                if ($result == self::NOT_COMPATIBLE_WITH_OTHER_ENCHANT) {
                    $sender->sendMessage(TextFormat::RED . "§a•§c Item này đã được nâng cấp, không thể thêm");
                }
                if ($result == self::MAX_LEVEL) {
                    $sender->sendMessage(TextFormat::RED . "§e•§d>§b Giới hạn Level của dạng nâng cấp này là§c " . $this->getEnchantMaxLevel($enchant) . "§d Level");
                }
            }
            continue;
        }
        return $item;
    }

    /**
     * Removes enchantment from item
     *
     * @param Item $item
     * @param CustomEnchants $enchant
     * @param int $level
     * @return bool|Item
     */
    public function removeEnchantment(Item $item, CustomEnchants $enchant, $level = -1)
    {
        if (!$item->hasEnchantments()) {
            return false;
        }
        $tag = $item->getNamedTag();
        $item = Item::get($item->getId(), $item->getDamage(), $item->getCount());
        foreach ($tag->ench as $k => $enchantment) {
            if (($enchantment["id"] == $enchant->getId() && ($enchantment["lvl"] == $level || $level == -1)) !== true) {
                $item = $this->addEnchantment($item, $enchantment["id"], $enchantment["lvl"], true);
            }
        }
        return $item;
    }

    /**
     * Returns enchantment type
     *
     * @param CustomEnchants $enchant
     * @return string
     */
    public function getEnchantType(CustomEnchants $enchant)
    {
        foreach ($this->enchants as $id => $data) {
            if ($enchant->getId() == $id) {
                return $data[1];
            }
        }
        return "Unknown";
    }

    /**
     * Returns rarity of enchantment
     *
     * @param CustomEnchants $enchant
     * @return string
     */
    public function getEnchantRarity(CustomEnchants $enchant)
    {
        foreach ($this->enchants as $id => $data) {
            if ($enchant->getId() == $id) {
                return $data[3];
            }
        }
        return "Common";
    }

    /**
     * Returns the max level the enchantment can have
     *
     * @param CustomEnchants $enchant
     * @return int
     */
    public function getEnchantMaxLevel(CustomEnchants $enchant)
    {
        foreach ($this->enchants as $id => $data) {
            if ($enchant->getId() == $id) {
                return $data[4];
            }
        }
        return 5;
    }

    /**
     * Sorts enchantments by type.
     *
     * @return array
     */
    public function sortEnchants()
    {
        $sorted = [];
        foreach ($this->enchants as $id => $data) {
            $type = $data[1];
            if (!isset($sorted[$type])) {
                $sorted[$type] = [$data[0]];
            } else {
                array_push($sorted[$type], $data[0]);
            }
        }
        return $sorted;
    }

    /**
     * Returns roman numeral of a number
     *
     * @param $integer
     * @return string
     */
    public function getRomanNumber($integer) //Thank you @Muqsit!
    {
        $romanString = "";
        while ($integer > 0) {
            foreach (self::ROMAN_CONVERSION_TABLE as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $romanString .= $rom;
                    break;
                }
            }
        }
        return $romanString;
    }

    /**
     * @param $rarity
     * @return string
     */
    public function getRarityColor($rarity)
    {
        switch ($rarity) {
            case CustomEnchants::RARITY_COMMON:
                $color = strtoupper($this->getConfig()->getNested("color.common"));
                return $this->translateColorNameToTextFormat($color) == false ? TextFormat::YELLOW : $this->translateColorNameToTextFormat($color);
            case CustomEnchants::RARITY_UNCOMMON:
                $color = strtoupper($this->getConfig()->getNested("color.uncommon"));
                return $this->translateColorNameToTextFormat($color) == false ? TextFormat::BLUE : $this->translateColorNameToTextFormat($color);
            case CustomEnchants::RARITY_RARE:
                $color = strtoupper($this->getConfig()->getNested("color.rare"));
                return $this->translateColorNameToTextFormat($color) == false ? TextFormat::GOLD : $this->translateColorNameToTextFormat($color);
            case CustomEnchants::RARITY_MYTHIC:
                $color = strtoupper($this->getConfig()->getNested("color.mythic"));
                return $this->translateColorNameToTextFormat($color) == false ? TextFormat::LIGHT_PURPLE : $this->translateColorNameToTextFormat($color);
            default:
                return TextFormat::GRAY;
        }
    }

    /**
     * @param $color
     * @return bool|mixed
     */
    public function translateColorNameToTextFormat($color)
    {
        foreach (self::COLOR_CONVERSION_TABLE as $name => $textformat) {
            if ($color == $name) {
                return $textformat;
            }
        }
        return false;
    }

    /**
     * Checks if an item can be enchanted with a specific enchantment and level
     *
     * @param Item $item
     * @param CustomEnchants $enchant
     * @param $level
     * @return bool
     */
    public function canBeEnchanted(Item $item, CustomEnchants $enchant, $level)
    {
        $type = $this->getEnchantType($enchant);
        if ($this->getEnchantMaxLevel($enchant) < $level) {
            return self::MAX_LEVEL;
        }
        if (($enchant->getId() == CustomEnchants::PORKIFIED && $this->getEnchantment($item, CustomEnchants::BLAZE) !== null) || ($enchant->getId() == CustomEnchants::BLAZE && $this->getEnchantment($item, CustomEnchants::PORKIFIED) !== null) || ($enchant->getId() == CustomEnchants::SHRINK && $this->getEnchantment($item, CustomEnchants::GROW)) || ($enchant->getId() == CustomEnchants::GROW && $this->getEnchantment($item, CustomEnchants::SHRINK))) {
            return self::NOT_COMPATIBLE_WITH_OTHER_ENCHANT;
        }
        switch ($type) {
            case "Global":
                return true;
            case "Damageable":
                if ($item->getMaxDurability() !== 0) {
                    return true;
                }
                break;
            case "Weapons":
                if ($item->isSword() !== false || $item->isAxe() || $item->getId() == Item::BOW) {
                    return true;
                }
                break;
            case "Bow":
                if ($item->getId() == Item::BOW) {
                    return true;
                }
                break;
            case "Tools":
                if ($item->isPickaxe() || $item->isAxe() || $item->isShovel() || $item->isShears()) {
                    return true;
                }
                break;
            case "Pickaxe":
                if ($item->isPickaxe()) {
                    return true;
                }
                break;
            case "Axe":
                if ($item->isAxe()) {
                    return true;
                }
                break;
            case "Armor":
                if ($item instanceof Armor) {
                    return true;
                }
                break;
            case "Helmets":
                switch ($item->getId()) {
                    case Item::LEATHER_CAP:
                    case Item::CHAIN_HELMET:
                    case Item::IRON_HELMET:
                    case Item::GOLD_HELMET:
                    case Item::DIAMOND_HELMET:
                        return true;
                }
                break;
            case "Chestplate":
                switch ($item->getId()) {
                    case Item::LEATHER_TUNIC:
                    case Item::CHAIN_CHESTPLATE;
                    case Item::IRON_CHESTPLATE:
                    case Item::GOLD_CHESTPLATE:
                    case Item::DIAMOND_CHESTPLATE:
                        return true;
                }
                break;
            case "Leggings":
                switch ($item->getId()) {
                    case Item::LEATHER_PANTS:
                    case Item::CHAIN_LEGGINGS:
                    case Item::IRON_LEGGINGS:
                    case Item::GOLD_LEGGINGS:
                    case Item::DIAMOND_LEGGINGS:
                        return true;
                }
                break;
            case "Boots":
                switch ($item->getId()) {
                    case Item::LEATHER_BOOTS:
                    case Item::CHAIN_BOOTS:
                    case Item::IRON_BOOTS:
                    case Item::GOLD_BOOTS:
                    case Item::DIAMOND_BOOTS:
                        return true;
                }
                break;
            case "Compass":
                if ($item->getId() == Item::COMPASS) {
                    return true;
                }
                break;
        }
        return self::NOT_COMPATIBLE;
    }

    /**
     * @param Position $pos
     * @param $ids
     * @param $deep
     * @return bool
     * @internal param $id
     */
    public function checkBlocks(Position $pos, $ids, $deep)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        foreach ($ids as $id) {
            for ($i = 0; $deep < 0 ? $i >= $deep : $i <= $deep; $deep < 0 ? $i-- : $i++) {
                $block = $pos->getLevel()->getBlock($pos->subtract(0, $i));
                if ($block->getId() !== $id) {
                    return false;
                }
            }
        }
        return true;
    }
}
