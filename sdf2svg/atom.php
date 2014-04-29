<?php

class atom {

	public $coord;
	public $type;
	public $charge;
	public $stereo;
	public $displayH;

	function __construct( $type, $coord, $charge = 0, $stereo = 0, $displayH = false )
	{
		$this->coord = $coord;
		$this->type = $type;
		$this->charge = $charge;
		$this->stereo = $stereo;
		$this->displayH = $displayH;
	}

}


?>
