<?php

function distance( $x1, $y1, $x2, $y2 )
{
	if ( $x2 < $x1 )
	{
		$xtemp = $x2;
		$x2 = $x1;
		$x1 = $xtemp;
	}

	if ( $y2 < $y1 )
	{
		$ytemp = $y2;
		$y2 = $y1;
		$y1 = $ytemp;
	}


	return sqrt( pow( $x2 -$x1, 2 ) + pow( $y2 -$y1, 2 ) );
}

?>