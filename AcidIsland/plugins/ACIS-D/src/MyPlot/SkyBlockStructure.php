<?php

namespace MyPlot;

use pocketmine\math\Vector3;
use pocketmine\level\ChunkManager;
use pocketmine\level\SimpleChunkManager;
use pocketmine\block\Block;
use pocketmine\level\generator\populator\Populator;
use pocketmine\utils\Random;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\NBT;
use pocketmine\level\generator\Generator;
use pocketmine\level\format\Chunk;
use pocketmine\tile\Chest;
use pocketmine\item\Item;

class SkyBlockStructure extends Populator{
	public $generator = null;

	public function __construct(Generator $gen){
		$this->generator = $gen;
	}

	/**
	 *
	 * @param ChunkManager $level 
	 * @param Chunk $chunk 
	 * @param  $Xofchunk 
	 * @param  $Zofchunk 
	 */
	public static function placeObject(ChunkManager $level, $chunk, $Xofchunk, $Zofchunk){
		$vec = new Vector3($chunk->getX() * 16 + $Xofchunk, 0, $chunk->getZ() * 16 + $Zofchunk);
		$vec = $vec->subtract(7, 0, 7); // fix offset
		for($x = 4; $x < 11; $x++){
			for($z = 4; $z < 11; $z++){
				$level->setBlockIdAt($vec->x + $x, 64, $vec->z + $z, Block::GRASS);
			}
		}
		for($x = 5; $x < 10; $x++){
			for($z = 5; $z < 10; $z++){
				$level->setBlockIdAt($vec->x + $x, 63, $vec->z + $z, Block::DIRT);
				$level->setBlockIdAt($vec->x + $x, 68, $vec->z + $z, Block::LEAVES); // 68
			}
		}
		for($x = 6; $x < 9; $x++){
			for($z = 6; $z < 9; $z++){
				$level->setBlockIdAt($vec->x + $x, 69, $vec->z + $z, Block::LEAVES); // 69
				$level->setBlockIdAt($vec->x + $x, 62, $vec->z + $z, Block::DIRT); // 62
			}
		}
		$level->setBlockIdAt($vec->x + 7, 60, $vec->z + 7, Block::BEDROCK); // 0
		$level->setBlockIdAt($vec->x + 7, 61, $vec->z + 7, Block::SAND); // 1
		$level->setBlockIdAt($vec->x + 7, 62, $vec->z + 7, Block::SAND); // 2
		$level->setBlockIdAt($vec->x + 7, 63, $vec->z + 7, Block::SAND); // 3
		$level->setBlockIdAt($vec->x + 8, 64, $vec->z + 8, Block::GRASS);
		$level->setBlockIdAt($vec->x + 8, 64, $vec->z + 9, Block::GRASS);
		$level->setBlockIdAt($vec->x + 8, 64, $vec->z + 7, Block::GRASS);
		$level->setBlockIdAt($vec->x + 8, 64, $vec->z + 6, Block::GRASS);
		$level->setBlockIdAt($vec->x + 9, 64, $vec->z + 5, Block::GRASS);
		$level->setBlockIdAt($vec->x + 8, 65, $vec->z + 10, Block::REEDS);
		$level->setBlockIdAt($vec->x + 10, 64, $vec->z + 8, Block::GRASS);
		$level->setBlockIdAt($vec->x + 10, 64, $vec->z + 9, Block::GRASS);
		$level->setBlockIdAt($vec->x + 10, 64, $vec->z + 7, Block::GRASS);
		$level->setBlockIdAt($vec->x + 10, 64, $vec->z + 6, Block::GRASS);
		$level->setBlockIdAt($vec->x + 9, 64, $vec->z + 8, Block::GRASS);
		$level->setBlockIdAt($vec->x + 9, 64, $vec->z + 9, Block::GRASS);
		$level->setBlockIdAt($vec->x + 9, 64, $vec->z + 7, Block::GRASS);
		$level->setBlockIdAt($vec->x + 9, 64, $vec->z + 6, Block::GRASS);
		$level->setBlockIdAt($vec->x + 4, 65, $vec->z + 7, Block::CRAFTING_TABLE);
		$level->setBlockIdAt($vec->x + 4, 65, $vec->z + 8, Block::FENCE);
		$level->setBlockIdAt($vec->x + 4, 65, $vec->z + 9, Block::FENCE);
		$level->setBlockIdAt($vec->x + 5, 65, $vec->z + 9, Block::FENCE);
		$level->setBlockIdAt($vec->x + 5, 65, $vec->z + 10, Block::FENCE);
$level->setBlockIdAt($vec->x + 6, 65, $vec->z + 10, Block::FENCE);
$level->setBlockIdAt($vec->x + 7, 65, $vec->z + 10, Block::FENCE);
$level->setBlockIdAt($vec->x + 8, 65, $vec->z + 10, Block::FENCE);
$level->setBlockIdAt($vec->x + 9, 65, $vec->z + 10, Block::FENCE);
$level->setBlockIdAt($vec->x + 10, 65, $vec->z + 10, Block::HAY_BALE);
$level->setBlockIdAt($vec->x + 10, 65, $vec->z + 9, Block::HAY_BALE);
$level->setBlockIdAt($vec->x + 10, 66, $vec->z + 9, Block::HAY_BALE);
$level->setBlockIdAt($vec->x + 10, 65, $vec->z + 8, Block::HAY_BALE);
$level->setBlockIdAt($vec->x + 9, 65, $vec->z + 9, Block::HAY_BALE);
		#$level->setBlockIdAt($vec->x + 4, 65, $vec->z + 8, Block::FURNACE);
		#$level->setBlockIdAt($vec->x + 5, 66, $vec->z + 10, Block::HAY_BALE);
		#$level->setBlockIdAt($vec->x + 5, 65, $vec->z + 9, Block::HAY_BALE);
		#$level->setBlockIdAt($vec->x + 5, 65, $vec->z + 10, Block::HAY_BALE);
		#$level->setBlockIdAt($vec->x + 6, 65, $vec->z + 9, Block::HAY_BALE);
		#$level->setBlockIdAt($vec->x + 6, 65, $vec->z + 10, Block::LIT_PUMPKIN);
		$level->setBlockIdAt($vec->x + 7, 67, $vec->z + 8, Block::LOG); // 5
		$level->setBlockIdAt($vec->x + 7, 65, $vec->z + 7, Block::LOG); // 5
		$level->setBlockIdAt($vec->x + 7, 66, $vec->z + 7, Block::LOG); // 6
		$level->setBlockIdAt($vec->x + 7, 67, $vec->z + 7, Block::LOG); // 7
		$level->setBlockIdAt($vec->x + 7, 68, $vec->z + 7, Block::LOG); // 8
		$level->setBlockIdAt($vec->x + 7, 69, $vec->z + 7, Block::LOG); // 9
		$level->setBlockIdAt($vec->x + 4, 64, $vec->z + 4, Block::AIR); // 64
		$level->setBlockIdAt($vec->x + 4, 64, $vec->z + 10, Block::AIR);
		$level->setBlockIdAt($vec->x + 5, 68, $vec->z + 5, Block::AIR); // 68
		$level->setBlockIdAt($vec->x + 5, 68, $vec->z + 9, Block::AIR);
		$level->setBlockIdAt($vec->x + 9, 68, $vec->z + 5, Block::AIR);
		$level->setBlockIdAt($vec->x + 9, 68, $vec->z + 9, Block::AIR);
		$level->setBlockIdAt($vec->x + 5, 69, $vec->z + 7, Block::LEAVES); // 69
		$level->setBlockIdAt($vec->x + 7, 69, $vec->z + 5, Block::LEAVES);
		$level->setBlockIdAt($vec->x + 9, 69, $vec->z + 7, Block::LEAVES);
		$level->setBlockIdAt($vec->x + 7, 69, $vec->z + 9, Block::LEAVES);
		$level->setBlockIdAt($vec->x + 7, 70, $vec->z + 6, Block::LEAVES); // 70
		$level->setBlockIdAt($vec->x + 6, 70, $vec->z + 7, Block::LEAVES);
		$level->setBlockIdAt($vec->x + 8, 70, $vec->z + 7, Block::LEAVES);
		$level->setBlockIdAt($vec->x + 7, 70, $vec->z + 8, Block::LEAVES);
		$level->setBlockIdAt($vec->x + 7, 71, $vec->z + 7, Block::LEAVES); // 71
		$level->setBlockIdAt($vec->x + 7, 61, $vec->z + 8, Block::DIRT); // 61
		$level->setBlockIdAt($vec->x + 8, 61, $vec->z + 7, Block::DIRT);
		$level->setBlockIdAt($vec->x + 7, 61, $vec->z + 6, Block::DIRT);
		$level->setBlockIdAt($vec->x + 6, 61, $vec->z + 7, Block::DIRT);
		$level->setBlockIdAt($vec->x + 5, 62, $vec->z + 7, Block::DIRT); // 62
		$level->setBlockIdAt($vec->x + 7, 62, $vec->z + 5, Block::DIRT);
		$level->setBlockIdAt($vec->x + 9, 62, $vec->z + 7, Block::DIRT);
		$level->setBlockIdAt($vec->x + 7, 62, $vec->z + 9, Block::DIRT);
		$level->setBlockIdAt($vec->x + 4, 63, $vec->z + 7, Block::DIRT); // 63
		$level->setBlockIdAt($vec->x + 7, 63, $vec->z + 4, Block::DIRT);
		$level->setBlockIdAt($vec->x + 7, 63, $vec->z + 10, Block::DIRT);
		$level->setBlockIdAt($vec->x + 10, 63, $vec->z + 7, Block::DIRT);
		$level->setBlockIdAt($vec->x + 10, 63, $vec->z + 8, Block::DIRT);
		$level->setBlockIdAt($vec->x + 10, 63, $vec->z + 9, Block::DIRT);
		$level->setBlockIdAt($vec->x + 10, 63, $vec->z + 6, Block::DIRT);
		$level->setBlockIdAt($vec->x + 10, 63, $vec->z + 5, Block::DIRT);
	}

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		$chunk = $level->getChunk($chunkX, $chunkZ);
		$shape = $this->generator->getShape($chunkX << 4, $chunkZ << 4);
		for($Z = 0; $Z < 16; ++$Z){
			for($X = 0; $X < 16; ++$X){
				$type = $shape[($Z << 4) | $X];
				if($type === MyPlotGenerator::ISLAND){
					self::placeObject($level, $chunk, $X, $Z);
				}
			}
		}
	}
}