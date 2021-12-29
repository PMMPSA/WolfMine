<?php
/**
 *  ____  _            _______ _          _____
 * |  _ \| |          |__   __| |        |  __ \
 * | |_) | | __ _ _______| |  | |__   ___| |  | | _____   __
 * |  _ <| |/ _` |_  / _ \ |  | '_ \ / _ \ |  | |/ _ \ \ / /
 * | |_) | | (_| |/ /  __/ |  | | | |  __/ |__| |  __/\ V /
 * |____/|_|\__,_/___\___|_|  |_| |_|\___|_____/ \___| \_/
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze for the PocketMine-MP Community!
 *
 * @author iiFlamiinBlaze
 * Twitter: https://twitter.com/iiFlamiinBlaze
 * GitHub: https://github.com/iiFlamiinBlaze
 * Discord: https://discord.gg/znEsFsG
 */
declare(strict_types=1);

namespace iiFlamiinBlaze\BlazinRestart;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class RestartTask extends Task{

	/** @var int $seconds */
	private $seconds = 0;

	public function onRun(int $tick) : void{
		$this->seconds++;
		$time = intval(BlazinRestart::getInstance()->getConfig()->get("restart-time")) * 60;
		$restartTime = $time - $this->seconds;
		switch($restartTime){
			case 100:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 2 Phút");
				return;
			case 50:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 1 Phút");
				return;
			case 25:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 30 Giây");
				return;
			case 10:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 10 Giây");
				return;
			case 5:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 5 Giây");
				return;
			case 4:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 4 Giây");
				return;
			case 3:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 3 Giây");
				return;
			case 2:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 2 Giây");
				return;
			case 1:
				BlazinRestart::getInstance()->getServer()->broadcastMessage(BlazinRestart::PREFIX . TextFormat::GREEN . "§eMáy Chủ sẽ khởi động lại trong§c: 1 Giây");
				return;
			case 0:
				foreach(BlazinRestart::getInstance()->getServer()->getOnlinePlayers() as $player) $player->kick(strval(BlazinRestart::getInstance()->getConfig()->get("restart-message")));
				BlazinRestart::getInstance()->getServer()->shutdown();
				return;
		}
	}
}