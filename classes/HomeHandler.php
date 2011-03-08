<?php

class HomeHandler {

	public static function handleGetIndex() {
		Out::add('<div id="resumeControls">');
		UI::renderButtons(array(array(
			'text' => 'Fortsetzen',
			'enabled' => false,
		)));
		Out::add('</div>');
		$players = PlayerStorage::getAllPlayers();
		$buttons = array();
		foreach ($players as $player) {
			$buttons[] = array(
				'text' => $player['name'],
			);
		}
		UI::renderButtons($buttons);
	}

}
