<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\inventorymonitor\inventory\group;

use kim\present\inventorymonitor\inventory\SyncInventory;
use pocketmine\item\Item;

abstract class SlotGroup{
	public const START = -1;
	public const END = -1;

	/** @var SyncInventory $syncInventory */
	protected $syncInventory;

	/**
	 * SlotGroup constructor.
	 *
	 * @param SyncInventory $syncInventory
	 */
	public function __construct(SyncInventory $syncInventory){
		$this->syncInventory = $syncInventory;
	}

	/**
	 * @param int $slot
	 *
	 * @return bool
	 */
	public function validate(int $slot) : bool{
		return $slot >= static::START && $slot <= static::END;
	}

	/**
	 * @param int  $slot
	 * @param Item $item
	 */
	public function setItem(int $slot, Item $item) : void{
		$this->onUpdate($slot - static::START, $item);
	}

	/**
	 * @param int  $index
	 * @param Item $item
	 */
	public abstract function onUpdate(int $index, Item $item) : void;
}