<?php defined('BASEPATH') or exit('No direct script access allowed');

// Load library phpexcel
include APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';

class ReportPoint extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/login');
        }
        $this->user = $this->session->userdata('user');
    }


     public function index()
    {

    	/*Get Member*/
    	$this->db->select("*");
		$this->db->from("tr_point as point");
		$this->db->join("ms_member as member", "member.MemberID = point.MemberID");
		$this->db->group_by('point.MemberID');
		$data = $this->db->get();

        /** DATA SEND TO VIEW */
        $data_content                 = array();
        $data_content['title']        = 'Report Customer History Transaction Point';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Report Transaction Point';
        $data_content['data_tabel']   = 'Filter Result';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['memberlist']	  =	$data->result();

        $config["content_file"] = "report_point/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


 	public function filter(){
 		$output = '';

  		$fromdate = $this->input->post('fromdate');
  		$todate = $this->input->post('todate');
  		$MemberID = $this->input->post('MemberID');

  		/*GET PARAM*/


  		/*QUERY*/
		$this->db->select("*");
		$this->db->from("tr_point as point");
		$this->db->join("ms_member as member", "member.MemberID = point.MemberID","LEFT");
		$this->db->join("rule_bill as bill", "bill.DID = point.RuleID","LEFT");
		$this->db->join("rule_category as category", "category.DID = point.RuleID","LEFT");
		$this->db->group_start();
		$this->db->where('TRXDate >=', $fromdate);
		$this->db->where('TRXDate <=', $todate);
		$this->db->group_end();
		if ($MemberID != '') {
		$this->db->where('point.MemberID', $MemberID);
		}
		$this->db->order_by('point.TRXDate', 'ASC');
		$data = $this->db->get();

		/*COUNT TOTAL POINT - EXPIRED*/
		$this->db->select_sum('Point');
		$this->db->where('TRXDate >=', $fromdate);
		$this->db->where('TRXDate <=', $todate);
		if ($MemberID != '') {
		$this->db->where('MemberID', $MemberID);
		}
		$this->db->where('ExpiredStatus !=','1');
		$rs = $this->db->get('tr_point');
		$extotal = $rs->row()->Point;

		/*COUNT TOTAL POINT*/
		$this->db->select_sum('Point');
		$this->db->where('TRXDate >=', $fromdate);
		$this->db->where('TRXDate <=', $todate);
		if ($MemberID != '') {
		$this->db->where('MemberID', $MemberID);
		}
		$rs2 = $this->db->get('tr_point');
		$totalpoint = $rs2->row()->Point;

		/*COUNT EXPIRED STATUS*/
		$this->db->select('ExpiredStatus');
		$this->db->where('TRXDate >=', $fromdate);
		$this->db->where('TRXDate <=', $todate);
		$this->db->where('ExpiredStatus !=','0');
		if ($MemberID != '') {
		$this->db->where('MemberID', $MemberID);
		}
		$rs3 = $this->db->get('tr_point');
		$expired = $rs3->num_rows();


	 	$output .= '
		    <table class="table table-striped">
	            <thead>
		            <tr>
		              <th>No</th>
		              <th>Member ID</th>
		              <th>Member Name</th>
		              <th>TRX Date</th>
		              <th>TRX Note</th>
		              <th>Photo</th>
		              <th>TRX Amount</th>
		              <th>Point</th>
		              <th>Rule Type</th>
		              <th>Rule ID</th>
		              <th>Rule Title</th>
		              <th>Voucher ID</th>
		              <th>Voucher Code</th>
		              <th>Voucher Used</th>
		              <th>Buy Time</th>
		              <th>Use Time</th>
		              <th>Expired Status</th>
		              <th>Expired Time</th>
		            </tr>
	            </thead>
	            <tfoot>
	            	<tr>
			            <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th>Available Point</th>
			              <th>('.$totalpoint.' - '.$expired.' = '.$extotal.')</th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
			              <th></th>
	              </tr>
		         </tfoot>
		  ';
	  if($data->num_rows() > 0)
	  {

        $no = 1;
		   foreach($data->result() as $row)
		   {

		    $output .= '
		     <tbody>
	            <tr>
	              <td>'.$no++ .'</td>
	              <td>'.$row->MemberID.'</td>
	              <td>'.$row->FirstName.' '.$row->LastName.'</td>
	              <td>'.date("d-M-Y H:i:s", strtotime($row->TRXDate)).'</td>
	              <td>'.$row->TRXNote.'</td>
	              <td>'.$row->TRXPhoto.'</td>
	              <td>'.number_format($row->TRXAmount,2,",",".").'</td>
	              <td>'.$row->Point.'</td>
	              <td>'.$row->RuleType.'</td>
	              <td>'.$row->RuleID.'</td>
	              <td>'.$row->RuleTitle.'</td>
	              <td>'.$row->VoucherID.'</td>
	              <td>'.$row->VoucherCode.'</td>
	              <td>'.$row->VoucherUsed.'</td>
	              <td>'.date("d-M-Y H:i:s", strtotime($row->VoucherBT)).'</td>
	              <td>'.date("d-M-Y H:i:s", strtotime($row->VoucherUT)).'</td>
	              <td>'.$row->ExpiredStatus.'</td>
	              <td>'.date("d-M-Y H:i:s", strtotime($row->ExpiredTime)).'</td>
	            </tr>
            </tbody>
		    ';
		   }
	  }
	  else
	  {
	   $output .= '<tr>
	       <td colspan="10" style="text-align:center;">Data Not Found</td>
	      </tr>';
	  }
	  $output .= '</table>';
	  echo $output;
	}



	public function export()
	{
	// Create new Spreadsheet object
	$excel = new PHPExcel();

	//auto width cell
	foreach(range('A','R') as $columnID) {
	    $excel->getActiveSheet()->getColumnDimension($columnID)
	          ->setAutoSize(true);

	 	
	}

   /* $row = 7;
    $latestBLColumn = $excel->getActiveSheet()->getHighestDataColumn();
	$range = 'B'.$row.':'.$latestBLColumn.$row;
	$excel->getActiveSheet()
		    ->getStyle($range)
		    ->getNumberFormat()
		    ->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );*/

	/*STYLE */
	$style_col = array(
      'font' => array('bold' => true), // Set font nya jadi bold
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      ),
      'borders' => array(
        'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
      )
    );

	/*STYLE HEADER*/
	$styleTittle = array(
	  'font' => array('bold' => true), // Set font nya jadi bold
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
      )
  	);

	$excel->getActiveSheet()->getStyle('A6:R6')->applyFromArray($style_col);
	$excel->getActiveSheet()->getStyle('A1')->applyFromArray($styleTittle);
	$excel->getActiveSheet()->getStyle('B2:C4')->applyFromArray($styleTittle);
	$excel->getActiveSheet()->mergeCells('A1:R1');

	// Set document properties
	$excel->getProperties()->setCreator('AMBARRUKMO')
	->setLastModifiedBy('AMBARRUKMO')
	->setTitle('Office 2007 XLSX Test Document')
	->setSubject('Office 2007 XLSX Test Document')
	->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
	->setKeywords('office 2007 openxml php')
	->setCategory('result file');

	// Add some data
	$excel->setActiveSheetIndex(0)
	->setCellValue('A1', 'REPORT CUSTOMER HISTORY TRANSACTION POINT')
	->setCellValue('A6', 'NO')
	->setCellValue('B6', 'MEMBER ID')
	->setCellValue('C6', 'MEMBER NAME')
	->setCellValue('D6', 'TRX DATE')
	->setCellValue('E6', 'TRX NOTE')
	->setCellValue('F6', 'TRX PHOTO')
	->setCellValue('G6', 'TRX AMOUNT')
	->setCellValue('H6', 'POINT')
	->setCellValue('I6', 'RULE TYPE')
	->setCellValue('J6', 'RULE ID')
	->setCellValue('K6', 'RULE TITLE')
	->setCellValue('L6', 'VOUCHER ID')
	->setCellValue('M6', 'VOUCHER CODE')
	->setCellValue('N6', 'VOUCHER USE')
	->setCellValue('O6', 'BUY TIME')
	->setCellValue('P6', 'USE TIME')
	->setCellValue('Q6', 'EXPIRED STATUS')
	->setCellValue('R6', 'EXPIRED TIME')

	;

	$fromdate = $this->input->post('fromdate_fill');
  	$todate = $this->input->post('todate_fill');
  	$MemberID = $this->input->post('member_fill');

  	$excel->setActiveSheetIndex(0)
	->setCellValue('B2', 'FROM DATE')
	->setCellValue('C2', date("d-M-Y H:i:s", strtotime($fromdate)))
	->setCellValue('B3', 'TO DATE')
	->setCellValue('C3', date("d-M-Y H:i:s", strtotime($todate)))
	->setCellValue('B4', 'MEMBER ID')
	->setCellValue('C4', $MemberID)
	;

	/*QUERY*/
		$this->db->select("*");
		$this->db->from("tr_point as point");
		$this->db->join("ms_member as member", "member.MemberID = point.MemberID","LEFT");
		$this->db->join("rule_bill as bill", "bill.DID = point.RuleID","LEFT");
		$this->db->join("rule_category as category", "category.DID = point.RuleID","LEFT");
		$this->db->group_start();
		$this->db->where('TRXDate >=', $fromdate);
		$this->db->where('TRXDate <=', $todate);
		$this->db->group_end();
		if ($MemberID != '') {
		$this->db->where('point.MemberID', $MemberID);
		}
		$this->db->order_by('point.DID', 'DESC');
		$data = $this->db->get()->result();

	// Miscellaneous glyphs, UTF-8
	$i=7; $no=1; foreach($data as $data) {

	$excel->setActiveSheetIndex(0)
	->setCellValue('A'.$i, $no)
	->setCellValue('B'.$i, $data->MemberID)
	->setCellValue('C'.$i, $data->FirstName.' '.$data->LastName)
	->setCellValue('D'.$i, $data->TRXDate)
	->setCellValue('E'.$i, $data->TRXNote)
	->setCellValue('F'.$i, $data->TRXPhoto)
	->setCellValue('G'.$i, $data->TRXAmount)
	->setCellValue('H'.$i, $data->Point)
	->setCellValue('I'.$i, $data->RuleType)
	->setCellValue('J'.$i, $data->RuleID)
	->setCellValue('K'.$i, $data->RuleTitle)
	->setCellValue('L'.$i, $data->VoucherID)
	->setCellValue('M'.$i, $data->VoucherCode)
	->setCellValue('N'.$i, $data->VoucherUsed)
	->setCellValue('O'.$i, $data->VoucherBT)
	->setCellValue('P'.$i, $data->VoucherUT)
	->setCellValue('Q'.$i, $data->ExpiredStatus)
	->setCellValue('R'.$i, $data->ExpiredTime)

	;
	$i++;
	$no++;
	}


	 // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    // Set orientasi kertas jadi LANDSCAPE
    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    // Set judul file excel nya
    $excel->getActiveSheet(0)->setTitle("REPORT TRANSACTION POINT");
    $excel->setActiveSheetIndex(0);
    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="REPORT TRANSACTION POINT.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $write->save('php://output');
	}




}

