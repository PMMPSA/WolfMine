<?php

/*
 *
 * Newspaper
 *
 * Copyright © 2018 Johnmacrocraft
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

namespace Johnmacrocraft\Newspaper\forms;

use Johnmacrocraft\Newspaper\Newspaper;
use Johnmacrocraft\Newspaper\libs\dktapps\pmforms\MenuForm;
use Johnmacrocraft\Newspaper\libs\dktapps\pmforms\MenuOption;
use pocketmine\lang\BaseLang;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class ItemListForm extends MenuForm {

	/** @var BaseLang */
	private $lang;

	public function __construct(BaseLang $lang) {
		$this->lang = $lang;
		foreach(Newspaper::getPlugin()->getAllNewspaperInfo() as $info) {
			$options[] = new MenuOption((new Config($info, Config::YAML))->get("name"));
		}
		parent::__construct($lang->translateString("gui.itemlist.title"),
			$lang->translateString("gui.itemlist.label") .
			(isset($options) ? "" : str_repeat(TextFormat::EOL, 2) . $lang->translateString("gui.itemlist.label.noItems")),
			($options ?? []),
			function(Player $player, int $selectedOption) : void {
				$player->sendForm(new BuyInfoForm(Newspaper::getPlugin()->getNewspaperInfo($this->getOption($selectedOption)->getText()), $this->lang));
			}
		);
	}
}