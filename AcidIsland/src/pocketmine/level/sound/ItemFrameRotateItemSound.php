<?php

/*
 *
 *  _______                  _             _          __      ___   _            
 * |__   __|                (_)           | |         \ \    / / \ | |          
 *    | | ___ _ __ _ __ ___  _ _ __   __ _| |_ ___  _ _\ \  / /|  \| |  
 *    | |/ _ \ '__| '_ ` _ \| | '_ \ / _` | __/ _ \| '__\ \/ / | . ` | 
 *    | |  __/ |  | | | | | | | | | | (_| | || (_) | |   \  /  | |\  |
 *    |_|\___|_|  |_| |_| |_|_|_| |_|\__,_|\__\___/|_|    \/   |_| \_| 
 *                    
 *                     
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TerminatorVNTeam
 * @link https://github.com/TerminatorVN/TerminatorVN
 *
 *
*/

namespace pocketmine\level\sound;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

class ItemFrameRotateItemSound extends GenericSound {
	/**
	 * ItemFrameRotateItemSound constructor.
	 *
	 * @param Vector3 $pos
	 * @param int     $pitch
	 */
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ITEMFRAME_ROTATE_ITEM, $pitch);
	}
}
