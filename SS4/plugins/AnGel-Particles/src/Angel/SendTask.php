<?php

namespace Angel;

use pocketmine\scheduler\Task;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\InstantEnchantParticle;
use pocketmine\math\Vector3;

use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\PortalParticle;
class SendTask extends Task{
	
	public function __construct(Main $main){
		$this->main = $main;
		//parent::__construct($main);
	}
	
	// Осторожно! Говнокод!
	// Чуть ниже вы можете увидеть нечно ужасное. Из ваших
	// глаз польётся кровь, а те ужасы будут вам ещё долго
	// сниться по ночам. Автор этого алгоритма болен шизо-
	// френией и сейчас находится на лечении у лучших пси-
	// хиатров европы. Но шансов мало. Если вам дорого
	// ваше здоровье, то крайе не советую пускаться вниз.
	// Я вас предупредил.
	//
	// P.S. Автор этого плагина вполне здоровый и написал
	// этот код примерно за час. Были использованы циклы
	// foreach и немного хитрости. Так что не пытайтесь
	// ничего копировать отсюда плиз.
	
	public function onRun(int $tick){
		foreach($this->getMain()->getServer()->getOnlinePlayers() as $p){
			if(isset($this->main->players[strtolower($p->getName())])){

  $x = $p->getX();
            $y = $p->getY();
            $z = $p->getZ();
              
                $red = new HappyVillagerParticle(new Vector3( $x, $y, $z));
                $green = new HappyVillagerParticle(new Vector3( $x, $y, $z));
                $flame = new HappyVillagerParticle(new Vector3($x, $y, $z));
                $level = $p->getLevel();
                  $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z)));
				  $level->addParticle(new HeartParticle(new Vector3( $x, $y+2, $x),  80, 100, 0, 1));
      
	  $level->addParticle(new HeartParticle(new Vector3( $x, $y+2, $x),  80, 100, 0, 1));
      	  $level->addParticle(new HappyVillagerParticle(new Vector3( $x-1, $y, $z)));
       $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.5, $y, $z)));
        $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.3, $y, $z)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-1.3, $y, $z)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.8, $y, $z)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z-0.3)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z-0.5)));
             $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z-0.8)));
             $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z-1.3)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z-1)));
          // +
       $level->addParticle(new HappyVillagerParticle(new Vector3( $x+1, $y, $z)));
       $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.5, $y, $z)));
        $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.3, $y, $z)));
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x+1.3, $y, $z)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z+0.3)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z-0.8)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z+0.5)));
            $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z+1)));
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y, $z+1.3)));
          //X
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.3, $y, $z-0.3)));
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.5, $y, $z-0.5)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.8, $y, $z-0.8)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+1, $y, $z-1)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+1.3, $y, $z-1.3)));
          //
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.3, $y, $z-0.3)));
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.5, $y, $z-0.5)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.8, $y, $z-0.8)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-1, $y, $z-1)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-1.3, $y, $z-1.3)));
          ////
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.3, $y, $z+0.3)));
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.5, $y, $z+0.5)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+0.8, $y, $z+0.8)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+1, $y, $z+1)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x+1.3, $y, $z+1.3)));
          //
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.3, $y, $z+0.3)));
           $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.5, $y, $z+0.5)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-0.8, $y, $z+0.8)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-1, $y, $z+1)));
          $level->addParticle(new HappyVillagerParticle(new Vector3( $x-1.3, $y, $z+1.3)));
		 
         $level->addParticle(new DustParticle(new Vector3($x, $y+1.8, $z), 244, 238, 71, 1));
		 //4
		  $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y+0.5, $z)));
	 $level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y+0.8, $z)));
	$level->addParticle(new HappyVillagerParticle(new Vector3( $x, $y+1, $z)));
		 //
        $center = new Vector3($x, $y, $z); $radius = 0.5; $count = 100; 
        $particle = new PortalParticle($center, 200, 100, 0, 1); 
     for($yaw = 0, $y = $center->y; $y < $center->y + 4; $yaw += (M_PI * 1) / 120, $y += 1 / 120){ 
        $x = -sin($yaw) + $center->x; 
        $z = cos($yaw) + $center->z; 
$particle->setComponents($x, $y, $z); 
$level->addParticle($particle);
} 
 // $center = new Vector3($x, $y, $z);
        $particle2 = new InstantEnchantParticle($center);
        for($yaw = 0, $y = $center->y; $y < $center->y + 10; $yaw += (M_PI * 1.5) / 10, $y += 1 / 20) {
            $x = -sin($yaw) + $center->x;
            $z = cos($yaw) + $center->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle2);
            
            //	$player->getLevel ()->addSound ( new BatSound ( $player->getPosition () ), array($player) );
    
  }/* $particle = new AngryVillagerParticle($center);
        for($yaw = 0, $y = $center->y; $y < $center->y + 5; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            $x = -sin($yaw) + $center->x;
            $z = cos($yaw) + $center->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle);*/
    
           /*     foreach([$red, $green, $flame] as $particle) {
                   
                    $level->addParticle($particle);
                   
                }*/
		/*		$playerPosition = $player->getPosition()->add(0, 1.2, 0);
				switch($player->getDirection()){
					case 0:
$position = $playerPosition->add(-0.5, 1.4, 0.8); 
$player->getLevel()->addParticle(new HappyVillagerParticle($position, 10)); 
$position = $playerPosition->add(-0.5, 1.4, -0.8); 
$player->getLevel()->addParticle(new HappyVillagerParticle($position, 10)); 

$position = $playerPosition->add(-0.5, 1.0, 0.8); 
$player->getLevel()->addParticle(new HappyVillagerParticle($position, 10)); 
$position = $playerPosition->add(-0.5, 1.0, -0.8); 
$player->getLevel()->addParticle(new HappyVillagerParticle($position, 10)); 

$position = $playerPosition->add(-0.5, 0.8, 0.8); 
$player->getLevel()->addParticle(new HappyVillagerParticle($position, 10)); 
$position = $playerPosition->add(-0.5, 0.8, -0.8); 
$player->getLevel()->addParticle(new HappyVillagerParticle($position, 10)); 


						break 1;*/
				}
			}
		}
	
	
	public function getMain()
	{
		return $this->main;
	}
}
