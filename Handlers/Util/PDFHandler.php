<?php
/**
 * PDF Handler
 * can only implement drivers
 * can only use other handlers if they are passed by reference
 * Do not reference Globals except for lib paths (explicitly pass values to functions)
 * can implement base class if and only if base class used by more than one handlers
*
* @author 
* @version 1.0
* @package insideArtCenter
* @subpackage common
* @date 6/14/12
*/
namespace Handlers\Util;

define('FPDF_FONTPATH','3rdParty/fpdf/font');

require_once '3rdParty/fpdf/fpdf_modded.php';

class PDFHandler
{
    private $objPDF;
    
    public function __construct()
    {
        $this->objPDF = new \FPDF();
        $this->objPDF->AliasNbPages();
        $this->objPDF->AddPage();
        $this->objPDF->SetFont('Arial','B',16);
    }
    
    public function Output($NAME="")
	{
		// destination: I | D | F | S
	    $this->objPDF->Output("I", $NAME);
	}
	
	public function Save($NAME="")
	{
	    // destination: I | D | F | S
	    $this->objPDF->Output("F", $NAME);
	}
		
	public function To_String($NAME="")
	{
	    // destination: I | D | F | S
	    $this->objPDF->Output("S", $NAME);
	}
	// Page header  -- move to driver which is fpdf class extended
	public function Header()
	{
	    // Logo
	    $this->objPDF->Image('logo.png',10,6,30);
	    // Arial bold 15
	    $this->objPDF->SetFont('Arial','B',15);
	    // Move to the right
	    $this->objPDF->Cell(80);
	    // Title
	    $this->objPDF->Cell(30,10,'Title',1,0,'C');
	    // Line break
	    $this->objPDF->Ln(20);
	}
	
	// Page footer  -- move to driver which is fpdf class extended
	public function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->objPDF->SetY(-15);
	    // Arial italic 8
	    $this->objPDF->SetFont('Arial','I',8);
	    // Page number
	    $this->objPDF->Cell(0,10,'Page '.$this->objPDF->PageNo().'/{nb}',0,0,'C');
	}
	
	public function Set_Font($FAMILY=NULL, $STYLE=NULL, $SIZE=16, $COLOR=NULL)
	{
	    // style:  B, I, U concatenate for all 3
	    // family: Courier | Helvetica | Arial | Times | Symbol | ZapfDingbats 
	    // color:  #ffffff
	    
	    if(isset($COLOR))
	    {
	        [$r, $g, $b] = sscanf($COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetTextColor($r, $g, $b);
	    }
	    	    
	    if (!isset($FAMILY) && !isset($STYLE)) 
	    {
	        $this->objPDF->SetFontSize($SIZE);
	    }else{
	        $this->objPDF->SetFont($FAMILY, $STYLE, $SIZE);
	    }
	        
	}
	
	public function image($FILE, $X=NULL, $Y=NULL, $WIDTH=NULL, $HEIGHT=NULL, $TYPE=NULL, $LINK=NULL)
	{
	    // width | height: negative value represents DPI
	    // type: JPG | JPEG | PNG | GIF
	    $this->objPDF->Image($FILE, $X, $Y, $WIDTH, $HEIGHT, $TYPE, $LINK);
	}
	
	public function line($X1, $Y1, $X2, $Y2, $COLOR="", $LINE_WIDTH="")
	{
	    if($COLOR != "")
	    {
	        list($r, $g, $b) = sscanf($COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetDrawColor($r, $g, $b);
	    }
	    
	    if($LINE_WIDTH != "")
	    {
	        $this->objPDF->SetLineWidth($LINE_WIDTH);
	    }
	    
	    $this->objPDF->Line($X1, $Y1, $X2, $Y2);
	}
	
	public function rect($X, $Y, $WIDTH, $HEIGHT, $STYLE=NULL, $COLOR="", $BACKGROUND_COLOR="", $LINE_WIDTH="")
	{
	    // style: D , F      ###  It can be drawn (border only), filled (with no border) or both. 
	    if($COLOR != "")
	    {
	        list($r, $g, $b) = sscanf($COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetDrawColor($r, $g, $b);
	    }
	    
	    if($BACKGROUND_COLOR != "")
	    {
	        list($r, $g, $b) = sscanf($BACKGROUND_COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetFillColor($r, $g, $b);
	    }
	    
	    if($LINE_WIDTH != "")
	    {
	        $this->objPDF->SetLineWidth($LINE_WIDTH);
	    }
	    
	    $this->objPDF->Rect($X, $Y, $WIDTH, $HEIGHT, $STYLE);
	}
	
	public function TextBox($TEXT, $WIDTH=NULL, $HEIGHT=NULL, $BORDER="none", $NEXT_TEXT_POS="right", $TEXT_ALIGN="left", $BACKGROUND_FILLED=NULL, $BACKGROUND_COLOR = "", $LINK=NULL)
	{
	    // width:  If 0, the cell extends up to the right margin. 
	    $borderMap = ["none" => 0, "full" => 1, "left" => "L", "top" => "T", "right" => "R", "bottom" => "B"];
	    $alignMap = ["left" => "L", "center" => "C", "right" => "R"];
	    $nextMap = ["right" => 0, "newline" => 1, "below" => 2];
	    
	    if($BACKGROUND_COLOR != "")
	    {
	        list($r, $g, $b) = sscanf($BACKGROUND_COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetFillColor($r, $g, $b);
	    }
	    
	    $this->objPDF->Cell($WIDTH, $HEIGHT, $TEXT, $borderMap[$BORDER], $nextMap[$NEXT_TEXT_POS], $alignMap[$TEXT_ALIGN], $BACKGROUND_FILLED, $LINK);
	}

	public function TextArea($TEXT, $WIDTH=NULL, $HEIGHT=NULL, $BORDER="none", $NEXT_TEXT_POS="below", $TEXT_ALIGN="justify", $BACKGROUND_FILLED=false, $BACKGROUND_COLOR = "", $LINK="")
	{
	    // width:  If 0, the cell extends up to the right margin.
	    $borderMap = ["none" => 0, "full" => 1, "left" => "L", "top" => "T", "right" => "R", "bottom" => "B"];
	    $alignMap = ["left" => "L", "center" => "C", "right" => "R", "justify" => "J"];
	    $nextMap = ["right" => 0, "newline" => 1, "below" => 2];
	    
	    if($BACKGROUND_COLOR != "")
	    {
	        [$r, $g, $b] = sscanf($BACKGROUND_COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetFillColor($r, $g, $b);
	    }
	    
	    ///MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
	    $this->objPDF->MultiCell($WIDTH, $HEIGHT, $TEXT, $borderMap[$BORDER], $alignMap[$TEXT_ALIGN], $BACKGROUND_FILLED, $nextMap[$NEXT_TEXT_POS]);
	}
	
	public function New_Line($HEIGHT=NULL)
	{
	    $this->objPDF->Ln($HEIGHT);
	}
	
	public function Add_Page($ORIENTATION="", $SIZE="", $ROTATION="")
	{
	    // orientation:  P | Portrait | L | Landscape
	    // size: A3 | A4 | A5 | Letter | Legal
	    // rotation (angle): 0 | 90 | 180 | 270
	    if ($ORIENTATION != "")
	    {
	        if ($SIZE != "")
	        {
	            if ($ROTATION != "")
	            {
	                $this->objPDF->AddPage($ORIENTATION, $SIZE, $ROTATION);
	            }else{
	                $this->objPDF->AddPage($ORIENTATION, $SIZE);
	            }
	        }else{
	            $this->objPDF->AddPage($ORIENTATION);
	        }
	    }	    
	}
	
	public function Set_Margin($LEFT="", $TOP="", $RIGHT="")
	{
	    if ($LEFT != "")
	    {
	        if ($SIZE != "")
	        {
	            if ($TOP != "")
	            {
	                $this->objPDF->SetMargins($LEFT, $TOP, $RIGHT);
	            }else{
	                $this->objPDF->SetMargins($LEFT, $TOP);
	            }
	        }else{
	            $this->objPDF->SetMargins($LEFT);
	        }
	    }	    
	}
	
	public function Set_Position($X=null, $Y=null)
	{
	    // negative values are relative from the bottom
	    if(empty($X))
	    {
	        $this->objPDF->SetY($Y);
	    }elseif(empty($Y)){
	        $this->objPDF->SetX($X);
	    }else{
	       $this->objPDF->SetXY($X, $Y);
	    }
	}
	
	public function Get_Position()
	{
	    // negative values are relative from the bottom
	    $ret[] = $this->objPDF->GetX();
	    $ret[] = $this->objPDF->GetY();
	    
	    return $ret;
	}
	
	// Simple table
	public function BasicTable($header, $data)
	{
		// Header
		foreach($header as $col)
		{
		    $this->objPDF->Cell(40,7,$col,1);
		    $this->objPDF->Ln();
			// Data
			foreach($data as $row)
			{
				foreach($row as $col)
				    $this->objPDF->Cell(40,6,$col,1);
				    $this->objPDF->Ln();
			}
		}
	}
	
	// Better table
	public function ImprovedTable($header, $data)
	{
		// Column widths
		$w = array(40, 35, 40, 45);
		// Header
		for($i=0;$i<count($header);$i++)
		    $this->objPDF->Cell($w[$i],7,$header[$i],1,0,'C');
		    $this->objPDF->Ln();
			// Data
			foreach($data as $row)
			{
			    $this->objPDF->Cell($w[0],6,$row[0],'LR');
			    $this->objPDF->Cell($w[1],6,$row[1],'LR');
			    $this->objPDF->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
			    $this->objPDF->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			    $this->objPDF->Ln();
			}
			// Closing line
			$this->objPDF->Cell(array_sum($w),0,'','T');
	}
	
	// Colored table
	public function FancyTable($header, $data, $COLUMN_WIDTHS, $COLUMN_ALIGNS="", $COLOR="", $BORDER_COLOR="")
	{
		// Colors, line width and bold font
	    if($COLOR != "")
	    {
	        list($r, $g, $b) = sscanf($COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetFillColor($r, $g, $b);
	    }else{
	       $this->objPDF->SetFillColor(0,0,0);
	    }
	    
	    $this->objPDF->SetTextColor(255);
	    $border = false;
	    if($BORDER_COLOR != "")
	    {
	        list($r, $g, $b) = sscanf($BORDER_COLOR, "#%02x%02x%02x");
	        $this->objPDF->SetDrawColor($r, $g, $b);
	        $border = true;
	    }else{
	        $this->objPDF->SetDrawColor(0,0,0);
	    }
	    
	    $this->objPDF->SetLineWidth(.3);
	    $this->objPDF->SetFont('','B');
		// Header
	    
		for($i=0;$i<count($header);$i++)
		{
		    $w = $COLUMN_WIDTHS[$i] ?? 30;
		    $b = ($border)?1:0;
		    $this->objPDF->Cell($w,7,$header[$i],$b,0,'C',true);
		}
		
	    $this->objPDF->Ln();
		// Color and font restoration
	    $this->objPDF->SetFillColor(235,235,235);
	    $this->objPDF->SetTextColor(0);
	    $this->objPDF->SetFont('');
		// Data
		$fill = false;
		foreach($data as $row)
		{
		    $c = 0;
		    foreach($row as $item)
		    {
		        $w = $COLUMN_WIDTHS[$c] ?? 30;
		        $a = $COLUMN_ALIGNS[$c] ?? "L";
		        $b = ($border)?"LR":"";
		        $this->objPDF->Cell($w,6,$item,$b,0,$a,$fill);
		        $c++;
		    }

		    $this->objPDF->Ln();
			$fill = !$fill;
		}
		// Closing line
		$b = ($border)?"T":"";
		$this->objPDF->Cell(array_sum($COLUMN_WIDTHS),0,'',$b);

	}
	
	public function Invoice($DATA)
	{
	    $this->Set_Font("Arial", "", 30);
	    $this->TextBox($DATA["invoice"]["companyName"], 85, 10, "");
	    $this->Set_Position(-70);
	    $this->Set_Font("Arial", "B");
	    $this->TextBox("INVOICE", 60, 10, "", "newline", "right");
	    
	    $this->New_Line();
	    
	    $this->Set_Font("Arial", "", 12);
	    $this->TextBox($DATA["invoice"]["address"], 85, 7);
	    $this->Set_Position(-60);
	    $this->TextBox("Date", 20, 7, "", "", "right");
	    $this->TextBox($DATA["invoice"]["date"], 30, 7, "", "newline", "right");
	    
	    $tmp = $DATA["invoice"]["city"]. ", " . $DATA["invoice"]["state"].", ".$DATA["invoice"]["zip"];
	    $this->TextBox($tmp, 85, 7);
	    
	    $this->Set_Position(-60);
	    $this->TextBox("Invoice #", 20, 7, "", "", "right");
	    $this->TextBox($DATA["invoice"]["invoiceNUM"], 30, 7, "", "newline", "right");
	    
	    $this->TextBox($DATA["invoice"]["phone"], 85, 7, "");
	    $this->Set_Position(-60);
	    $this->TextBox("Due Date", 20, 7, "", "", "right");
	    $this->TextBox($DATA["invoice"]["due_date"], 30, 7, "", "newline", "right");
	    $this->TextBox($DATA["invoice"]["email"], 85, 7, "", "newline");
	    $this->TextBox($DATA["invoice"]["website"], 85, 7, "", "newline");
	    
	    $this->New_Line();
	    
	    $this->Set_Font("Arial", "", 12, "#FFFFFF");
	    $this->TextBox("BILL TO", 60, 7, "", "newline", "", true, "#00AEF0");
	    $this->Set_Font("Arial", "", 12, "#000000");
	    $this->TextBox($DATA["invoice"]["client_name"], 20, 7, "", "newline", "", false, "#fffff");
	    $this->TextBox($DATA["invoice"]["client_company"], 20, 7, "", "newline");
	    $this->TextBox($DATA["invoice"]["client_address"], 20, 7, "", "newline");
	    $tmp = $DATA["invoice"]["client_city"]. ", " . $DATA["invoice"]["client_state"].", ".$DATA["invoice"]["client_zip"];
	    $this->TextBox($tmp, 20, 7, "", "newline");
	    $this->TextBox($DATA["invoice"]["client_phone"], 20, 7, "", "newline");
	    $this->TextBox($DATA["invoice"]["client_email"], 20, 7, "", "newline");
	    
	    $this->New_Line();
	    
	    $this->FancyTable($DATA["invitemHeaders"], $DATA["invitems"], $DATA["invitemCol"]["widths"], $DATA["invitemCol"]["aligns"], "#00AEF0", "#ECECEC");
	    
	    $this->New_Line(4);
	    
	    $this->Set_Position(-60);
	    $this->TextBox("Taxable Total", 20, 7, "", "", "right");
	    $this->TextBox($DATA["totals"]["taxableTotal"], 30, 7, "", "newline", "right");
	    
	    $this->Set_Position(-60);
	    $this->TextBox("Subtotal", 20, 7, "", "", "right");
	    $this->TextBox($DATA["totals"]["subTotal"], 30, 7, "", "newline", "right");
	    
	    $this->Set_Position(-60);
	    $this->TextBox("Tax", 20, 7, "", "", "right");
	    $this->TextBox($DATA["totals"]["tax"], 30, 7, "", "newline", "right");
	    
	    $this->Set_Position(-60);
	    $this->TextBox("Total Due", 20, 7, "", "", "right");
	    $this->TextBox($DATA["totals"]["totalDue"], 30, 7, "", "newline", "right");
	
	}
}
?>