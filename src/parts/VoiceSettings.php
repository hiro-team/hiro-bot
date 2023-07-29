<?php

namespace hiro\parts;

class VoiceSettings {

	public array $queue = [];
	public int $currentSong = 0;
	public bool $loopEnabled = false;

	public function __get($var)
	{
		return $this->{$var};
	}

	public function __set($var, $val): void
	{
		$this->{$var} = $val;
	}

}
