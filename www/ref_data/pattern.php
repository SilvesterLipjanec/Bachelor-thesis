#!/usr/local/bin/php
<?php

error_reporting(E_ALL);

$author = "Silvester Lipjanec";
$copy = "(c) 2017 " . $author;
$title = "print pattern";

function mm2pt($mm)
{
	return($mm / 25.4 * 72);
}

function box($p, $x, $y, $w, $h)
{
	pdf_moveto($p, $x, $y);
	PDF_rect($p, $x, $y, $w, $h);
	PDF_fill($p);
}


function palette($p, $sx, $sy, $paperW, $paperH,$w,$type,$width)
{
	$ex = $paperW - $sx; //end X position
	$ey = $paperH - $sy; //end Y position
	$sx = $sx + 2*$w; //set start X position
	$sy = $sy + 3*$w; //set start Y position
	$h = $w; 		  //height = width 
	
	$x = $sx;		  //current X position
	$y = $sy;		  //current Y position

	$maxY = 0;
	while($y < $ey)
	{ 
		$maxY++;
		$y = $y + $w;			
	}
	
	$maxX = 0;
	while ($x < $ex)
	{	
		$maxX++;
		$x = $x + $w;
	}
	
	$maxX -= 3;
	$maxY -= 3;

	$sucetR = 0;
	$pocet = 0;
	$sucetG =  0;
	$sucetB = 0;
	$file = 'new_patter_ref'.$width.'.txt';
	//$myfile = fopen($file, "w"); 
	if($type == 'RGB')
	{ 

		$step = pow(1/(($maxX) * ($maxY)),1/3);	//step for incrementing color 
		
		$x = $sx;
		$y = $sy; 	
		$c = 0;
		$rows = 1;
		for ($r = 0.0; $r <= 1.0 ; $r+= $step) {
			for ($g = 0.0; $g <= 1.0 ; $g+= $step) {
				for($b = 0.0; $b <= 1.0 ; $b+= $step){ 
					PDF_setcolor($p, "fill", $type , $r, $g, $b, 0);
					$red = round($r*255);
					$green = round($g*255);
					$blue = round($b*255);
					$sucetR = $sucetR + $red;
					$sucetG = $sucetG + $green;
					$sucetB = $sucetB + $blue;
					$pocet += 1;
					$str = $red.' '.$green.' '.$blue."\n";
					//fwrite($myfile,$str);
					box($p, $x, $y, $w, $h);
					$x = $x + $w;
					$c++;
					if($c >= $maxX)    //new line 
					{ 
						$rows++;
						$c = 0;
						$x = $sx; 
						$y += $w;
					}	
				}
			}
		}
		$priemerR = "priemer  red ".$sucetR/$pocet."\n";
		$priemerG = "priemer  green ".$sucetG/$pocet."\n";
		$priemerB = "priemer  blue ".$sucetB/$pocet."\n";
		$pocet_str = "pocet ".$pocet;
		
		//fwrite($myfile,$priemerR );
		//fwrite($myfile,$priemerG );
		//fwrite($myfile,$priemerB );
		//fwrite($myfile,$pocet_str);
	}
	
	else{ 
		$step = pow(1/(($maxX) * ($maxY)),1/4);	//step for incrementing color 
		$x = $sx;
		$y = $sy; 	
		$cn = 0;
		$rows = 1;
		for ($c = 0.0; $c <= 1.0 ; $c += $step) {
			for ($m = 0.0; $m <= 1.0 ; $m += $step) {
				for($yk = 0.0; $yk <= 1.0 ; $yk += $step){ 
					for($k = 0.0; $k <= 1.0 ; $k += $step){ 	
						PDF_setcolor($p, "fill", $type , $c, $m, $yk, $k);
						box($p, $x, $y, $w, $h);
						$x = $x + $w;
						$cn++;
						if($cn >= $maxX)    //new line 
						{ 
							$rows++;
							$cn = 0;
							$x = $sx; 
							$y += $w;
						}
					}	
				}
			}
		}

	}
	//fclose($myfile);
	return array($maxX,$rows);

}
function edges($p, $sx, $sy, $paperW, $paperH,$w,$xy)
{ 
	$xy[0]+= 2; //set 
	$xy[1]+= 2;

	$x = $sx;	
	$y = $sy;
	$h = $w;
	$lineW = 0.1 * $w;
	$ex = $paperW - $sx - 2*$w;
	$ey = $paperH - $sy - 2*$w;
	PDF_setlinewidth ( $p , $lineW );
	pdf_setcolor($p, "fill", "rgb", 0, 0, 0, 0);
	//write TOP vertical lines
	$file = 'new_pattern_ref_'.$w.'.txt';
	//$myfile = fopen($file, "w"); 
	$cx = 0;
	while ($cx <= $xy[0])
	{
		$cx++;
		$x = $x + $w;
		pdf_moveto($p, $x, $y);
		PDF_lineto($p, $x, $y+$h);
		PDF_stroke($p);
		$xx = $x;
	}
	$pocetx = 'x '.$cx."\n";
	//fwrite($myfile,$pocetx);
	$x = $sx;
	$y = $sy;
	//write horizontal lines
	for($i = 0 ; $i < 2 ; $i ++)
	{
		$cy = 0;
		while($cy <= $xy[1])
		{ 
			$cy++;
			$y = $y + $h;
			pdf_moveto($p, $x, $y);
			PDF_lineto($p, $x+$w, $y);
			PDF_stroke($p);
			$yy = $y;
		}
		$x = $xx;
		$y = $sy;
	}
	$pocety = 'y '.$cy."\n";
	//fwrite($myfile,$pocety);
	//fclose($myfile);
	$x = $sx;
	$y = $yy;
	//write BOTTOM vertical lines
	$cx = 0;
	while ($cx <= $xy[0])	
	{	
		$cx++;
		$x = $x + $w;
		pdf_moveto($p, $x, $y);
		PDF_lineto($p, $x, $y+$h);
		PDF_stroke($p);
	}
	
}

function text($p, $copy, $title, $type, $width, $ypos)
{
	$fs = 12;
	$at = " 0123456789 ";

	$font = pdf_load_font($p, "Helvetica", "iso8859-2", "");

	pdf_setfont($p, $font, $fs);
	pdf_setcolor($p, "fill", "rgb", 0, 0, 0, 0);
	$w = pdf_stringwidth($p, $copy, $font, $fs);
	$title = $type." ".$title;
	pdf_show_xy($p, $title, mm2pt(10), $ypos + $fs);
	pdf_show_xy($p, "width: ".$width." mm", mm2pt(80), $ypos + $fs);
	pdf_show_xy($p, $copy, mm2pt(200) - $w, $ypos + $fs);
}
function parseArg($argv,$argc){
	$width = 1;
	$type  = "RGB";
	if($argc > 3)
		return -1;
	if($argc == 2){
		if(is_numeric($argv[1])){
			if($argv[1] <= 0 || $argv[1] > 30){
				return -1;
			}
			else $width = $argv[1];
		}
		else
		{
			if($argv[1] == '--cmyk' || $argv[1] == '-c' )
				$type = "CMYK";
			else if($argv[1] == '--rgb' || $argv[1] == '-r' )
				$type = "RGB";
			else return -1;
		}
	}
	else if($argc == 3){
		if(is_numeric($argv[1])){
			if($argv[1] <= 0 || $argv[1] > 30){
				return -1;
			}
			else $width = $argv[1];
		}
		else return -1;

		if($argv[2] == '--cmyk' || $argv[2] == '-c' )
			$type = "CMYK";
		else if($argv[2] == '--rgb' || $argv[2] == '-r' )
			$type = "RGB";
		else return -1;
		
	}
	$params = array($width,$type);
	return $params;
}
try {
	$paperH = 842;
	$paperW = 595;
	$sx = mm2pt(15);
	$sy = mm2pt(15);
	$ypos = mm2pt(180);
	$params = parseArg($argv,$argc);
	if($params == -1){
		fprintf(STDERR, "Error: Bad parameters!\n");
		exit(2);
	}
	$width = $params[0];
	$type = $params[1];
	$w = mm2pt($width);
	$name = $type . $width . ".pdf"; 	

	$p = pdf_new();
	pdf_begin_document($p,$name, "");
	pdf_set_info($p, "Title", $title);
	pdf_set_info($p, "Author", $author);
	pdf_set_info($p, "Creator", "pattern.php");
	pdf_begin_page_ext($p, $paperW, $paperH, "topdown");

	text($p, $copy, $title, $type, $width,$ypos);
	
	$xy = palette($p,$sx,$sy, $paperW , $paperH/2, $w,$type,$width);
	edges($p,$sx,$sy, $paperW , $paperH, $w,$xy);
	pdf_end_page_ext($p, "");
	pdf_end_document($p, "");
	pdf_delete($p);
} catch (exception $e) {
	$t = $e->gettrace();
	fprintf(STDERR, "Error: %s(): %s in %s on line %d\n",
	    $t[0]["function"], $e->getmessage(), $t[0]["file"], $t[0]["line"]);
	exit(1);
}
