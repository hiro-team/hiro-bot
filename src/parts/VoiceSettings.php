<?php

namespace hiro\parts;

class VoiceSettings {

	private array $queue = [];
	private int $currentQueue = 0;
	private bool $loopEnabled = false;

	public function __get($var)
	{
		return $this->{$var};
	}

	public function __set($var, $val): void
	{
		$this->{$var} = $val;
	}

}
