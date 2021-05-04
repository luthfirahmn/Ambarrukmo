<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
 
class Quotation_pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }

   //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'kopatas.jpg';
        $this->Image($image_file, 20, 5, 180, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $image_file = K_PATH_IMAGES.'kopbawah.jpg';
        $this->Image($image_file, 20, 265, 180, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
?>

