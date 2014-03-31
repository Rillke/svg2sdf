<?php

class molecule
{
	public $atoms;
	public $bonds;
	public $name;
	public $angleX;
	public $angleY;
	
	function __construct($name,$atoms=array(),$bonds=array())
	{
		$this->name = $name;
		$this->atoms = $atoms;
		$this->bonds = $bonds;
		$this->angleX = 0.0;
		$this->angleY = 0.0;
		$this->angleZ = 0.0;
	}

	public function addAtom(atom $a)
	{
		array_push($this->atoms,$a);
	}
	
	public function addBond(bond $b)
	{
		array_push($this->bonds,$b);
	}
	
	public function getBondedAtoms($a)
	{
		$ret = array();
		
		foreach($this->bonds as $bond)
		{
			if ($a==$bond->atom1)
				$ret[] = $bond->atom2;
			elseif($a==$bond->atom2)
				$ret[] = $bond->atom1;
		}
		return $ret;
		
	}
	
	public function getLongestAxis()
	{
		$extremes = $this->get2DExtremeCoords();
		
		$maxX = $extremes[1]->x;
		$minX = $extremes[0]->x;
		$maxY = $extremes[1]->y;
		$minY = $extremes[0]->y;
				
		$distX = Abs($maxX-$minX);
		$distY = Abs($maxY-$minY);

		if ($distX > $distY)
			return $distX;
		
		return $distY;
	}
	
	public function getAvarageBondLength()
	{
		$totalBondLength = 0.0;
		$bonds = 0;
		for($i=0;$i<count($this->bonds);$i++)
		{
			$atom1 = $this->atoms[$this->bonds[$i]->atom1];
			$atom2 = $this->atoms[$this->bonds[$i]->atom2];
			if ($atom1->type == "C" && $atom2->type == "C" )
			{
				$_2d_1 = $this->get2D($atom1->coord,0);
				$_2d_2 = $this->get2D($atom2->coord,0);
				
				$x1 = $_2d_1->x;
				$x2 = $_2d_2->x;
				$y1 = $_2d_1->y;
				$y2 = $_2d_2->y;
				$totalBondLength += distance($x1,$y1,$x2,$y2);
				$bonds++;
			}
			
		}
		//if no carbon carbon bonds
		if ($bonds==0) return 1.0;
		
		return $totalBondLength / $bonds;
	}

	
	public function is3D()
	{
		$sumz = 0;
		foreach($this->atoms as $atom)
		{
			if ($atom->coord->z=="")
				return true;
			$sumz += $atom->coord->z;
		}
		if (abs($sumz)>0.0)
			return true;
		return false;
	}
	
	public function get2D(coord3D $coord,$angle=0)
	{
		$a = $coord;
		$dx = $a->x * cos($angle)-$a->y* sin($angle);
		$dy = $a->x * sin($angle)+$a->y* cos($angle);
		return new coord2D($dx,$dy);	
	}

	public function get2DExtremeCoords($angle=0)
	{
		$_2d = $this->get2D($this->atoms[0]->coord);
		$maxX = $_2d->x;
		$minX = $_2d->x;
		$maxY = $_2d->y;
		$minY = $_2d->y;
		for($i=0;$i<count($this->atoms);$i++)
		{
			$_2d = $this->get2D($this->atoms[$i]->coord);
			
		 	$maxX = Max($_2d->x,$maxX);
			$minX = Min($_2d->x,$minX);
			$maxY = Max($_2d->y,$maxY);
			$minY = Min($_2d->y,$minY);
			
			
		}
		
		return array(new coord2D($minX,$minY),new coord2D($maxX,$maxY));
		
	}
	
	public function get2DMassCenter($atoms="")
	{
		$x = 0;
		$y = 0;
		
		if ($atoms=="")
			$atoms = $this->atoms;
		
		foreach($atoms as $atom)
		{
			if (is_object($atom))
				$_2d = $this->get2D($atom->coord);
			else
				$_2d = $this->get2D($atoms[$atom]->coord);
			$x+=$_2d->x;
			$y+=$_2d->y;
		}
		return new coord2D($x/count($atoms),$y/count($atoms));
	}
}

?>