<?php
	/***************************
	*	Converts sdf file format to an array of mol files
	****************************/

	function sdf2mol( $sdf )
	{
		$ret = array();
		$mols = explode( "$$$$", $sdf );
		foreach ( $mols as $mol )
		{
			$lines = explode( "\n", $mol );
			if ( count( $lines ) > 4 ) // assume molfiles has more lines than 4
			{
				$ret[] = $mol;
			}
		}
		return $ret;
	}

?>