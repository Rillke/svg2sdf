<?php

class bond{

	public $atom1;
	public $atom2;
	public $type;
	public $stereo;	
	
	function __construct($atom1,$atom2,$type,$stereo=0)
	{
		$this->atom1 = $atom1;
		$this->atom2 = $atom2;
		$this->type = $type;
		$this->stereo= $stereo;
	}


}

?>
