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
 * @author NightRichTR (Anıl Mısırlıoğlu)
 * @copyright UltiCraft Games and Software Corporation, Copyright 2015 - 2018
 * @license UltiCraft Games and Software Corporation, Açık yazılım lisansı altındadır. Tüm hakları saklıdır.
 *
 * - 'My Little Angel :*'
 * - 'Just do it!'
 *
 */

namespace Boss;

use pocketmine\scheduler\Task;

class BossTask extends Task{

    /** @var Boss */
    private $boss;

    public function __construct(Boss $boss){
        $this->boss = $boss;
    }

    public function onRun(int $currentTick){
        $get = $this->boss->config->get('timer', null);
        $pos = $this->boss->config->get('position', null);

        if($get !== null and $pos !== null){
            if((int) $get <= time()){
                $this->boss->spawnToZombie();
                $this->boss->config->set('timer', strtotime('+2 hours'));
            }
        }else{
            $this->boss->config->set('timer', strtotime('+2 hours'));
        }

        $this->boss->config->save();
    }

}