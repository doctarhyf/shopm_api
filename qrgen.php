<?php

require_once('helper_funcs.php');
require_once("defs.php");
require_once("db.php");
require_once("pdf_renderer.php");

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',9);
$pdf->SetTextColor(255,0,0);
//$pdf->Cell(40,10,'Hello World!');

$pw = 210;
$ph = 297;

$dir    = 'qritems';
$files = scandir($dir);

$files = array_slice($files, 2);

//print_r($files);


//line to load file
$numRows = 4;
$numCols = 3;
$xCount = 0;
$yCount = 0;
$w = $pw / $numCols;
$h = $ph / $numRows;

$lblXPos = 0;
$lblYPos = 0;

foreach($files as $k){
	

	if($xCount == $numCols){
		$xCount = 0;
		$yCount ++;
	}	
	
	if($yCount == $numRows){
		$pdf->AddPage();
		$yCount = 0;
	}
	
	$fname = 'qritems/' . $k;
	$pdf->Image($fname,$xCount * $w,$yCount * $h, $w, $h);
	
	$pdf->SetXY(($xCount * $w) + 5, $yCount * $h );
	
	
	$pieces = explode("_", $k);
	$itemName = $pieces[0]; // piece1
	
	
		
	
	$pdf->Cell($w,10,$itemName);
	
	$xCount++;
	
}

$pdf->Output();

//echo 'okay';


?>