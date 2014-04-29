<?php

/***************************
*	Converts sdf/mol file format to the internal molecule format
****************************/

function mol2Molecule( $sdf )
{
	$lines = explode( "\n", $sdf );
	// Tests to find which is the start of the mol file by looking for the nr of atoms and bonds in the modecule

	$tries = array( 3, 2, 4, 1, 5 );
	$try = 0;
	$nrofm = -1;
	$nrofb = -1;
	while ( ( ( $nrofm < 1 || $nrofb < 1 ) ) && $try < count( $tries ) )
	{
		$nrofm = intval( trim( substr( $lines[$tries[$try]], 0, 3 ) ) );
		$nrofb = intval( trim( substr( $lines[$tries[$try]], 3, 3 ) ) );
		$try++;
	}
	// start of molecule
	$st = $tries[$try -1] + 1;
	$mol = new molecule( "" );
	$mol->name = $lines[0];

	// Read in atoms
	for ( $i = 0; $i < $nrofm; $i++ )
	{
		$x = trim( substr( $lines[$st + $i], 0, 10 ) );
		$y = trim( substr( $lines[$st + $i], 11, 10 ) );
		$z = trim( substr( $lines[$st + $i], 21, 10 ) );
		$a = trim( substr( $lines[$st + $i], 31, 3 ) );
		$c = trim( substr( $lines[$st + $i], 36, 3 ) );
		$s = trim( substr( $lines[$st + $i], 39, 3 ) );
		$mol->addAtom( new atom( $a, new coord3D( $x, $y, $z ), $c, $s ) );
	}


	// Read in bonds
	for ( $i = 0; $i < $nrofb; $i++ )
	{
		$a1 = trim( substr( $lines[$st + $i + $nrofm], 0, 3 ) );
		$a2 = trim( substr( $lines[$st + $i + $nrofm], 3, 3 ) );
		$t =  trim( substr( $lines[$st + $i + $nrofm], 6, 3 ) );
		$s =  trim( substr( $lines[$st + $i + $nrofm], 9, 3 ) );
		$mol->addBond( new bond( $a1 -1, $a2 -1, $t, $s ) );

		if ( $s == 1 || $s == 6 || ( $mol->atoms[$a1 -1]->type != "C" && $mol->atoms[$a2 -1]->type != "C" ) )
		{
			$mol->atoms[$a1 -1]->displayH = true;
			$mol->atoms[$a2 -1]->displayH = true;
		}

	}

	return $mol;
}

?>