<?php

/**
 *  _   _  _         _      _   ______  _        _      _____ ______
 * | \ | |(_)       | |    | |  | ___ \(_)      | |    |_   _|| ___ \
 * |  \| | _   __ _ | |__  | |_ | |_/ / _   ___ | |__    | |  | |_/ /
 * | . ` || | / _` || '_ \ | __||    / | | / __|| '_ \   | |  |    /
 * | |\  || || (_| || | | || |_ | |\ \ | || (__ | | | |  | |  | |\ \
 * \_| \_/|_| \__, ||_| |_| \__|\_| \_||_| \___||_| |_|  \_/  \_| \_|
 *             __/ |
 *            |___/
 *
 * @author NightRichTR
 *
 * @copyright UltiCraft Games and Software Corporation, Copyright 2015 - 2018
 * @license UltiCraft Games and Software Corporation, Açık yazılım lisansı altındadır. Tüm hakları saklıdır.
 *
 * - 'My Little Angel :*?'
 * - 'Just do it!'
 *
 */

namespace Boss;

use pocketmine\item\Item;

interface BossData{

    const BOSS_TYPE = "Zombie";

    const BOSS_PRIZE_ITEM_ID = Item::DIAMOND;

    const BOSS_PRIZE_ITEM_COUNT = 20;

    const BOSS_PRIZE_MONEY = 15000;

    const BOSS_HEALTH = 1000;

    const DAMAGE_ROSTER = [
        Item::DIAMOND_SWORD => 8,
        Item::IRON_SWORD => 6,
        Item::GOLDEN_SWORD => 4,
        Item::STONE_SWORD => 3,
        Item::WOODEN_SWORD => 2,
        Item::BOW => 7
    ];
}