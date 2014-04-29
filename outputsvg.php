<?php
	include_once( "sdf2svg/math.php" );
	include_once( "sdf2svg/coord2d.php" );
	include_once( "sdf2svg/coord3d.php" );
	include_once( "sdf2svg/svg.php" );

	include_once( "sdf2svg/atom.php" );
	include_once( "sdf2svg/bond.php" );
	include_once( "sdf2svg/molecule.php" );

	include_once( "sdf2svg/mol2molecule.php" );
	include_once( "sdf2svg/molecule2svg.php" );
	include_once( "sdf2svg/sdf2mol.php" );

	$sizex = $_REQUEST["sizex"];
	$sizey = $_REQUEST["sizey"];

	$molData = "";
	// Read in molfile
	$handle = fopen( $_FILES["molfile"]['tmp_name'], 'rb' );
	if ( $handle != false )
	{
		if  ( filesize ( $_FILES["molfile"]['tmp_name'] ) > 0 )
		{
			$fileparts = explode( ".", $_FILES["molfile"]['name'] );
			$filename = $fileparts[0];
			$sdf = fread ( $handle, filesize ( $_FILES["molfile"]['tmp_name'] ) );
		}
		else
		{
			echo "Error reading file.";
			fclose( $handle );
			die();
		}
		fclose( $handle );
	} else
	{
		$filename = "draw";
		$sdf = $_REQUEST["moldata"];
	}



	if ( $sdf == "" )
	{
		echo "Upload or draw molfile";
		die();
	}

	// convert sdf to mol
	$molFiles = sdf2mol( $sdf );


	// Go through all mol files to fish out the name
	$mols = array();
	$n = 0;
	foreach ( $molFiles as $molfile )
	{
		$molfile = trim( $molfile );
		$lines = explode( "\n", $molfile );

		// if not already set, set to the first line in the mol file
		if ( $molName == "" )
			$molName = $lines[0];
			// if still not set, set to the second line in the mol file
		if ( $molName == "" )
			$molName = $lines[1];
			// if still no name, set it to n
		if ( $molName == "" )
			$molName = $n;
			// if key exists rename it to name_n
		if ( array_key_exists( $molName, $mols ) )
			$molName .= "_" . $n;

		$mols[$molName] = $molfile;
		$molName = "";
		$n++;
	}


	// Set headers
	header( "Content-type: image/svg+xml" );
	if ( $_REQUEST["download"] != "1" )
		header( "Content-Disposition: inline; filename=" . $filename . ".svg;" );
	else
		header( "Content-Disposition: attachment; filename=" . $filename . ".svg;" );


	// Get svg header
	$ret = getSVGHeader();

	/*
		Convert mol files to interal molecule format and get sizes for each mol file for normalization of bondlengths
	*/
	$molInfo = array();
	$totalBondLength = 0;
	$totalLongestAxis = 0;
	$bondLengthforLongestAxis  = 1.0;
	foreach ( $mols as $molname => $mol )
	{
		$molecule = mol2molecule( $mol );
		$avarageBondLength = $molecule->getAvarageBondLength();
		$longestAxis = $molecule->getLongestAxis();

		if ( $longestAxis > $totalLongestAxis )
		{
			$totalLongestAxis = $longestAxis;
			$bondLengthforLongestAxis = $avarageBondLength;
		}
		$molInfo[$molname] = array( "mol" => $molecule, "bindLen" => $avarageBondLength, "longestAxis" => $longestAxis );
		$totalBondLength += $avarageBondLength;
	}

	$totalAvarageBondLength = $totalBondLength / count( $mols );

	$i = 0;
	$itemsPerLine = ceil( sqrt( count( $mols ) ) );
	$factor = 1 / $itemsPerLine;
	$scale = ( $sizex / ( $totalLongestAxis ) ) * 0.9;
	foreach ( $mols as $molname => $molLine )
	{
		$mol = $molInfo[$molname]["mol"];
		$scaleForThisCompound = $bondLengthforLongestAxis / $molInfo[$molname]["bindLen"] * $scale * 0.95;
		$xoffset = ( ( $i * $factor - floor( $i * $factor ) ) ) * $sizex * $itemsPerLine;
		$yoffset = ( ( floor( $i / ( $itemsPerLine ) ) * $factor ) ) * $sizey * $itemsPerLine;
		$ret .= molecule2svg( $mol, $sizex, $sizey, $angle, $molname, $_REQUEST["displayName"], $scaleForThisCompound, $bondLengthforLongestAxis * $scale, $factor, $xoffset, $yoffset );
		$i++;
	}
	$ret .= getSVGTail();
	echo $ret;



?>