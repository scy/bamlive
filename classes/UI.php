<?php

class UI {

	public static function renderButtons($def) {
		foreach ($def as $k => $v) {
			Out::printf('<button type="button"%s>%s</button>',
				(array_key_exists('enabled', $v) && !$v['enabled'])
					? ' disabled="disabled"' : '',
				$v['text']
			);
		}
	}

}
