<?php

function getSVGHeader()
{
	$str =  "<?xml version=\"1.0\" standalone=\"no\"?><!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">";
	$str .=   "<svg width=\"100%\" height=\"100%\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">";
	return $str;
}

function getSVGTail()
{
	return "</svg>";
}


function imageStringSVG( $size, $x, $y, $text, $color, $angle = 0, $alpha = 1.0 )
{
	$ret = "";
	// detect if mac is used and if so don't make it transparant
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if ( eregi( "mac", $agent ) )
		$alpha = 1.0;

	$ret .= "<text fill-opacity=\"" . $alpha . "\" id=\"TextElement\" transform=\"rotate(" . $angle . ")\" x=\"" . $x . "\" y=\"" . $y . "\"  fill=\"" . $color . "\" style=\" font-family:sans-serif;font-size:" . $size . "px\">" . $text . "</text>\n";
	return $ret;
}

function imageCenterStringSVG( $size, $x, $y, $text, $color, $angle = 0, $alpha = 1.0 )
{
	$ret = "";
	// detect if mac is used and if so don't make it transparant
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if ( eregi( "mac", $agent ) )
		$alpha = 1.0;

	$ret .= "<text fill-opacity=\"" . $alpha . "\" id=\"TextElement\" transform=\"rotate(" . $angle . ")\" x=\"" . $x . "\" y=\"" . $y . "\"  fill=\"" . $color . "\" style=\"text-anchor:middle;  font-family:sans-serif;font-size:" . $size . "px\">" . $text . "</text>\n";
	return $ret;
}

function twoColorLineSVG( $start_x, $start_y, $end_x, $end_y, $color1, $color2, $thickness = 1. )
{
	$ret = "";
	$mid_x = ( $start_x + $end_x ) / 2.0;
	$mid_y = ( $start_y + $end_y ) / 2.0;
	$ret .= filledBarSVG( $start_x, $start_y, $mid_x, $mid_y, $color1, $thickness );
	$ret .= filledBarSVG( $mid_x, $mid_y, $end_x, $end_y, $color2, $thickness );
	return $ret;
}


function dickelinieSVG( $start_x, $start_y, $end_x, $end_y, $color1, $color2, $thickness, $lineThickness )
{
	$ret = "";
	$angle = ( atan2( ( $start_y - $end_y ), ( $end_x - $start_x ) ) );

	$dist_x = $thickness * ( sin( $angle ) );
	$dist_y = $thickness * ( cos( $angle ) );

	$p1x = ceil( ( $start_x + $dist_x ) );
	$p1y = ceil( ( $start_y + $dist_y ) );
	$p2x = ceil( ( $end_x + $dist_x ) );
	$p2y = ceil( ( $end_y + $dist_y ) );
	$p3x = ceil( ( $end_x - $dist_x ) );
	$p3y = ceil( ( $end_y - $dist_y ) );
	$p4x = ceil( ( $start_x - $dist_x ) );
	$p4y = ceil( ( $start_y - $dist_y ) );

	$array = array( 0 => $p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y );
	$ret .= twoColorLineSVG( $p1x, $p1y, $p2x, $p2y, $color1, $color2, $lineThickness );
	$ret .= twoColorLineSVG( $p4x, $p4y, $p3x, $p3y, $color1, $color2, $lineThickness );
	return $ret;
}


function filledBarSVG( $start_x, $start_y, $end_x, $end_y, $color, $thickness, $alpha = 1.0 )
{
	return drawLineSVG( $start_x, $start_y, $end_x, $end_y, $color, $thickness, $alpha );
}

function drawLineSVG( $start_x, $start_y, $end_x, $end_y, $color, $thickness, $alpha = 1.0 )
{
	$ret = "";
	$ret .=  "<line x1=\"" . $start_x . "\" x2=\"" . $end_x . "\" y1=\"" . $start_y . "\" y2=\"" . $end_y . "\" ";
	$ret .=  "style=\"stroke-opacity:" . $alpha . "; stroke-width: " . $thickness . "; stroke: " . $color . "\" />\n";
	return $ret;
}

function drawPolygonSVG( $coords, $color, $alpha = 1.0 )
{
	$ret = "";
	$ret .=   "<polygon points=\"";
	foreach ( $coords as $coord )
	{
		$ret .=   " " . $coord[0] . "," . $coord[1] . "";
	}
	$ret .=   "\" fill-opacity=\"" . $alpha . "\"  style=\"fill:" . $color . "\" />\n";
	return $ret;
}

function drawPolygonSVGPattern( $coords, $color, $alpha = 1.0 )
{
	$ret = "";
	$ret .=   "<polygon points=\"";
	foreach ( $coords as $coord )
	{
		$ret .=   " " . $coord[0] . "," . $coord[1] . "";
	}
	$ret .=   "\" fill-opacity=\"" . $alpha . "\"  style=\"fill:none;stroke:" . $color . ";\" />\n";
	return $ret;
}



function drawEllipseSVG( $cx, $cy, $rx, $ry, $color, $alpha = 1.0 )
{
	return "<ellipse cx=\"" . $cx . "\" cy=\"" . $cy . "\" rx=\"" . $rx . "\" ry=\"" . $ry . "\" fill-opacity=\"" . $alpha . "\" style=\"fill:" . $color . "\"/>\n";
}

function drawRectangleSVG( $x, $y, $w, $h, $color, $alpha = 1.0 )
{
	return "<rect x=\"" . $x . "\" y=\"" . $y . "\" width=\"" . $w . "\" height=\"" . $h . "\" fill-opacity=\"" . $alpha . "\" fill=\"" . $color . "\"/>\n";
}


function upDownLineSVG( $start_x, $start_y, $end_x, $end_y, $color1, $color2, $thickness )
{
	$ret = "";
	$mid_x = ( $start_x + $end_x ) / 2;
	$mid_y = ( $start_y + $end_y ) / 2;


	$ret .=	downLineSVG( $mid_x, $mid_y, $start_x, $start_y, $color1, $color1, $thickness );
	$ret .= upLineSVG( $mid_x, $mid_y, $end_x, $end_y, $color2, $color2, $thickness );
	return $ret;
}


function downLineSVG( $start_x, $start_y, $end_x, $end_y, $color1, $color2, $thickness )
{
	$angle = ( atan2( ( $start_y - $end_y ), ( $end_x - $start_x ) ) );

	$dist_x = $thickness * ( sin( $angle ) );
	$dist_y = $thickness * ( cos( $angle ) );

	$p1x = ceil( ( $start_x ) );
	$p1y = ceil( ( $start_y ) );
	$p2x = ceil( ( $end_x + $dist_x ) );
	$p2y = ceil( ( $end_y + $dist_y ) );
	$p3x = ceil( ( $end_x - $dist_x ) );
	$p3y = ceil( ( $end_y - $dist_y ) );

	$array = array( array( $p1x, $p1y ), array( $p2x, $p2y ), array( $p3x, $p3y ) );
	return drawPolygonSVGPattern ( $array, $color1 );
}





function upLineSVG( $start_x, $start_y, $end_x, $end_y, $color1, $color2, $thickness )
{
	$angle = ( atan2( ( $start_y - $end_y ), ( $end_x - $start_x ) ) );

	$dist_x = $thickness * ( sin( $angle ) );
	$dist_y = $thickness * ( cos( $angle ) );

	$p1x = ceil( ( $start_x ) );
	$p1y = ceil( ( $start_y ) );
	$p2x = ceil( ( $end_x + $dist_x ) );
	$p2y = ceil( ( $end_y + $dist_y ) );
	$p3x = ceil( ( $end_x - $dist_x ) );
	$p3y = ceil( ( $end_y - $dist_y ) );

	$array = array( array( $p1x, $p1y ), array( $p2x, $p2y ), array( $p3x, $p3y ) );

	return drawPolygonSVG ( $array, $color1 );
}


?>