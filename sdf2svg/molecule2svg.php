<?php
	
	function molecule2SVG(molecule $mol,$sizeX,$sizeY,$angle=0,$molName="",$displayName=false,$scale=1.0,$standardBondLength=1.0,$scaleGroup=1.0,$offsetGroupX=0.0,$offsetGroupY=0.0)
	{
		
			$str = "";

			//Defines color scheme
			$bgc =  "#ffffff"; 
			$white = "#ffffff"; 
			$black = "#000000";
			$grey = "#e0e0e0";
			$darkgrey = "#a4a4a4"; 
			$blue = "#0000ff";
			$red = "#ff0000";
			$yellow = "#c8aa1a";
			$green = "#00bc00";
			$orange = "#fdaa04";
			$darkorange = "#db8802";


			$ms = $mol->get2DMassCenter();

			$sX = $ms->x;
			$sY = $ms->y;

			$extremes = $mol->get2DExtremeCoords();

			$maxX = $extremes[1]->x;
			$minX = $extremes[0]->x;
			$maxY = $extremes[1]->y;
			$minY = $extremes[0]->y;

			$cX = ($maxX*$scale + $minX*$scale)/2;
			$cY = ($maxY*$scale + $minY*$scale)/2;	

			$nr = Min(count($marklist),10);


	 		for($i=0;$i<count($mol->bonds);$i++)
			{
				$c = $black;
				$c2 = $black;

				$atom1 = $mol->atoms[$mol->bonds[$i]->atom1];
				$atom2 = $mol->atoms[$mol->bonds[$i]->atom2];

				switch($atom1->type)
				{
					case "O":
						$c = $red;
					break;

					case "N":
						$c = $blue;
					break;

					case "P":
					case "S":
						$c = $yellow;
					break;

					case "Br":
						$c = $darkorange;
					break;

					case "As":
						$c = $orange;
					break;

					case "Cl":
					case "Na":
					case "F":
						$c = $green;
					break;

					case "H":
						$c = $darkgrey;
					break;

					default:
						$c = $black;
					break;
				}

				switch($atom2->type)
				{
					case "O":
						$c2 = $red;
					break;

					case "N":
						$c2 = $blue;
					break;

					case "Br":
						$c2 = $darkorange;
					break;
					case "As":
						$c2 = $orange;
					break;

					case "P":
					case "S":
						$c2 = $yellow;
					break;

					case "Cl":
					case "F":
					case "Na":
						$c2 = $green;
					break;

					case "H":
						$c2 = $darkgrey;
					break;


					default:
						$c2 = $black;
					break;
				}

				$displayBond = true;

				if ($atom1->type=="H" || $atom2->type=="H")
				{
					$displayBond = false;

					if ((($atom1->type!="C" && $atom1->type!="H") || ($atom2->type!="C" && $atom2->type!="H")) && ($atom2->displayH || $atom2->displayH))
					{
						$displayBond = true;
					}

					if ($mol->bonds[$i]->stereo==1 || $mol->bonds[$i]->stereo==6)
						$displayBond = true;
				}

				if ($displayBond)
				{
					$_2d_1 = $mol->get2D($atom1->coord,$angle);
					$_2d_2 = $mol->get2D($atom2->coord,$angle);

					$x1 = $sizeX/2 - $cX + $_2d_1->x*$scale;
					$x2 = $sizeX/2 - $cX + $_2d_2->x*$scale;
					$y1 = $sizeY/2 - $cY +$_2d_1->y*$scale;
					$y2 = $sizeY/2 - $cY +$_2d_2->y*$scale;


					$thickness = $standardBondLength /25;



					if ($mol->bonds[$i]->stereo==0)
					{
					if ($mol->bonds[$i]->type == 2)
						$str .=dickelinieSVG($x1,$y1,$x2,$y2,$c,$c2,$standardBondLength/20,$thickness);
					elseif($mol->bonds[$i]->type == 1)
						$str .=twoColorLineSVG($x1,$y1,$x2,$y2,$c,$c2,$thickness);
					elseif($mol->bonds[$i]->type == 3)
					{
						$str .=dickelinieSVG($x1,$y1,$x2,$y2,$c,$c2,$standardBondLength/10,$thickness);
						$str .=twoColorLineSVG($x1,$y1,$x2,$y2,$c,$c2,$thickness);
					}
					}
					elseif($mol->bonds[$i]->stereo==1)
						$str .=upLineSVG($x1,$y1,$x2,$y2,$c,$c2,$standardBondLength/7);
					elseif($mol->bonds[$i]->stereo==6)
						$str .=downLineSVG($x1,$y1,$x2,$y2,$c,$c2,$standardBondLength/7);
					elseif($mol->bonds[$i]->stereo==3)
						$str .=upDownLineSVG($x1,$y1,$x2,$y2,$c,$c2,$standardBondLength/7);

				}
			}

				for($i=0;$i<count($mol->atoms);$i++)
				{
					$ch = "";
					switch($mol->atoms[$i]->charge)
					{
						case 1:
							$ch ="3+";
						break;

						case 2:
							$ch ="2+";
						break;

						case 3:
							$ch ="+";
						break;

						case 4:
							$ch ="**";
						break;

						case 5:
							$ch ="-";
						break;

						case 6:
							$ch ="2-";
						break;

						case 7:
							$ch ="3-";
						break;
					}


					switch($mol->atoms[$i]->type)
					{
						case "O":
							$c = $red;
						break;

						case "Na":
						case "N":
							$c = $blue;
						break;

						case "S":
							$c = $yellow;
						break;
						case "Br":
							$c = $darkorange;
						break;
						case "As":
							$c = $orange;
						break;

						case "F":
						case "Cl":
						case "Br":
							$c = $green;
						break;

						case "C":
							$c=$black;
						break;

						case "H":
							$c=$darkgrey;
						break;

						default:
							$c=$yellow;
						break;
					}

					$atom = $mol->atoms[$i]->type;

					if ($atom == "C" || $atom=="c")
					{
						$atom = "";
						$ch = "";
					}
					
					
					$tc = $blue;

					if ($mol->atoms[$i]->type!="H" || $mol->atoms[$i]->displayH)
					{
						//if($atom!="")$atom .= $ch;
						if(strlen($atom)>1)
							$tc = $darkgrey;
						else
							$tc = $blue;

						if ($sizeX > 128)
						{
							$tc = $c;
							$c = $bgc;
						}

						$_2d = $mol->get2D($mol->atoms[$i]->coord,$angle);
						//Draw a filled circle for atom representation 
						if($atom!="") $str .=drawEllipseSVG ($sizeX/2 - $cX+$_2d->x*$scale,$sizeY/2 - $cY + $_2d->y*$scale, $standardBondLength*0.26, $standardBondLength*0.26, $c );
						
						//Only output chemical symbols when size is over 128 px
						if ($sizeX > 128 && strlen($atom)>0)
						{
							$ts = 0.2*$scale;
							
							$fontSizeFactor = 0.5;
							
							if ($ch=="")
							{
								$fontSize = $standardBondLength*$fontSizeFactor;
								$fontOffset = 0.0;
							}else
							{
								$fontSize = $standardBondLength*$fontSizeFactor;
								$fontOffset = 0.0; //$standardBondLength / 45;
							}
							
							if ($ch!="")
								$chargeText = "<tspan baseline-shift='super' font-size='" . $fontSize/2 . "'>" . $ch . "</tspan>";
							
							
							if ($atom!="")
								$str .=imageCenterStringSVG($fontSize,$sizeX/2 - $cX+$_2d->x*$scale ,$sizeY/2 - $cY+$_2d->y*$scale+$standardBondLength*0.22-$fontOffset,$atom.$chargeText,$tc);
						}
					}
			}

			if ($displayName)
				$str .=imageCenterStringSVG($standardBondLength*0.5,$sizeX/2 ,$sizeY ,$molName,$black);

			$str .= "</g>";


			$head = "<g id='".$molName."' transform='scale(".$scaleGroup.") translate(".$offsetGroupX.",".$offsetGroupY.") scale(1)'>";


			return $head . $str;
	}	
?>
