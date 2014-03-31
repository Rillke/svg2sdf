<?php
	include_once("sdf2svg/math.php");
	include_once("sdf2svg/coord2d.php");
	include_once("sdf2svg/coord3d.php");
	include_once("sdf2svg/svg.php");
	
	include_once("sdf2svg/atom.php");
	include_once("sdf2svg/bond.php");
	include_once("sdf2svg/molecule.php");
	
	include_once("sdf2svg/sdf2mol.php");
	include_once("sdf2svg/mol2molecule.php");
	include_once("sdf2svg/molecule2svg.php");
	
	$sizex = 500;
	$sizey = 500;
	
	$sdf = file_get_contents("sample.sdf");
	
	//convert sdf to mol
	$molFiles = sdf2mol($sdf);
	
	
	// Go through all mol files to fish out the name
	$mols = array();
	foreach($molFiles as $molfile)
	{
		$molfile = trim($molfile);
		$lines = explode("\n",$molfile);
		$molName = $lines[0];
		$mols[$molName] = $molfile;
	}
	
	
	//Set headers
	header("Content-type: image/svg+xml");
	header("Content-Disposition: inline; filename=test.svg;");
		
		
	//Get svg header
	$ret = getSVGHeader();

	/*
		Convert mol files to interal molecule format and get sizes for each mol file for normalization of bondlengths
	*/
	$molInfo = array();
	$totalBondLength = 0;
	$totalLongestAxis = 0;
	$bondLengthforLongestAxis  = 1.0;
	foreach($mols as $molname=>$mol)
	{
		$molecule = mol2molecule($mol);
		$avarageBondLength = $molecule->getAvarageBondLength();
		$longestAxis = $molecule->getLongestAxis();

		if ($longestAxis > $totalLongestAxis)
		{
			$totalLongestAxis = $longestAxis;
			$bondLengthforLongestAxis = $avarageBondLength;
		}
		$molInfo[$molname] = array("mol"=>$molecule,"bindLen"=>$avarageBondLength,"longestAxis"=>$longestAxis);
		$totalBondLength += $avarageBondLength;
	}	

	$totalAvarageBondLength = $totalBondLength / count($mols);

	$i = 0;	
	$itemsPerLine = ceil(sqrt(count($mols)));
	$factor = 1 / $itemsPerLine;
	$scale = ($sizex / ($totalLongestAxis))*0.9;
	foreach($mols as $molname=>$molLine)
	{
		$mol = $molInfo[$molname]["mol"];
		$scaleForThisCompound = $bondLengthforLongestAxis / $molInfo[$molname]["bindLen"] * $scale * 0.95;
		$xoffset = (($i * $factor - floor($i*$factor)))*$sizex*$itemsPerLine; 
		$yoffset = ((floor($i/($itemsPerLine)) * $factor))*$sizey*$itemsPerLine; 
		$ret .= molecule2svg($mol,$sizex,$sizey,$angle,$molname,true,$scaleForThisCompound,$bondLengthforLongestAxis*$scale,$factor,$xoffset,$yoffset);
		$i++;
	}
	$ret .= getSVGTail();
	echo $ret;

?>