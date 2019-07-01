<?php

/*
for($i = 0; $i < 4; $i++){

    $zero = "";
    if($i < 10) { $zero = "0"; }

    echo "&lt;item&gt;" . ($i+2016) . "&lt;/item&gt; <br/>";

}*/


require('fpdf181/fpdf.php');



class PDF extends FPDF
{

    // Page header

    private $docName;

function Header()
{
    // Logo
    $this->Image('img/logo.jpeg',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,"JENOVIC",0,0,'C');
    // Line break
    $this->Ln(26);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb} : ' . $this->docName ,0,0,'C');
}

// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

//SHOPM
function RepportTable($title, $date, $totItems, $totCash, $header, $data, $isMonthly = false)
{
    //repport title

    $this->docName = $title . ' - ' . $date;

    $this->Cell(190,7,'Titre : ' . $title,0);
    $this->Ln();
    $this->Cell(190,7,'Date : ' . $date,0);
    $this->Ln();
    $this->Cell(190,7,'Total Articles vendu : ' . $totItems,0);
    $this->Ln();
    $this->Cell(190,7,'Total Revenu : ' . $totCash,0);
    $this->Ln();
    if($isMonthly){
        $this->Cell(190,7,'Dime : ' . $totCash * .1,0);
        $this->Ln();
    }
    $this->Ln();
    // Header
    $cellw = array(63.33,31.66,31.66,31.66,31.66);
    $i = 0;
    foreach($header as $col){
        
        $this->Cell($cellw[$i],7,$col,1);
        

        $i ++;
    }
    $this->Ln();
    // Data
    
    foreach($data as $row)
    {
    	
    	
    		//print_r($data);
        $i = 0;
        foreach($row as $col){
            $this->Cell($cellw[$i],6,$col,1);
            $i++;
        }
        
        $this->Ln();

        
    }

    
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(40, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(40, 35, 40, 45);
    for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

/*
$pdf = new PDF();
// Column headings
$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
// Data loading
$data = $pdf->LoadData('countries.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output();*/




?>