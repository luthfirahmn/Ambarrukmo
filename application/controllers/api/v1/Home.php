<?php defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Home extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index_post()
	{
		if (empty($this->post("MemberID"))  ) {

				$response = array(
					'message' => 'Invalid params',
					'error' => true,
				);
				$this->response($response, 400);
			}

		try{
			$sliderhead = $this->db->query("SELECT * ,CONCAT('HOME_SLIDER/',ImagePath) as ImageSlider 
											  FROM ms_slider 
											  WHERE SliderGroup = 'Header'
											  AND Active != '0'
											  ORDER BY OrderNo ASC
									");

			$header = $sliderhead->result();

			$sliderfoot = $this->db->query("SELECT * ,CONCAT('HOME_SLIDER/',ImagePath) as ImageSlider 
											 FROM ms_slider
											 WHERE SliderGroup = 'Footer'
											 AND Active != '0'
											 ORDER BY OrderNo ASC
									");

			$footer = $sliderfoot->result();

			$date = date('Y-m-d');
			$voucher = $this->db->query("SELECT *,CONCAT('VOUCHER/',VoucherIMG) as VoucherPath 
										 FROM ms_voucher
										 WHERE Active != '0' 
										 AND ExpiredTime >= '{$date}'
										 AND QtyUsed <= Qty
										 ORDER BY RBT DESC
									");

			$rsvoucher = $voucher->result();

			$MemberID = $this->post('MemberID');
			$getmember = $this->db->query("SELECT FullName,TotalPoint
										 FROM ms_member
										 WHERE MemberID = '{$MemberID}' 
									");

			$member = $getmember->row();

			$data = array(
				'massage' 	=> 'success',
				'error' 	=> 'false',
				'header' 	=> $header,
				'footer' 	=> $footer,
				'voucher'	=> $rsvoucher,
				'member'	=> $member
			);

			if($header OR $footer OR $rsvoucher OR $member)
			{
				$this->response($data, 200);
			}
			else
			{
				$this->response($data, 404);
			}

		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}
		

	}

	public function sliderdetail_post()
	{
		if (empty($this->post("DID"))  ) {

				$response = array(
					'message' => 'Invalid params',
					'error' => true,
				);
				$this->response($response, 400);
			}

		try{
			$DID = $this->post('DID');

			$query = $this->db->query("SELECT * ,CONCAT('HOME_SLIDER/',ImagePath) as ImageSlider 
											 FROM ms_slider
											 WHERE DID = '{$DID}'
									");

			$result = $query->row();


			$data = array(
				'massage' 	=> 'success',
				'error' 	=> 'false',
				'result' 	=> $result,
			);

			if($result)
			{
				$this->response($data, 200);
			}
			else
			{
				$this->response($data, 404);
			}

		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}
	}



	/*
	$desc = $result->Description;
			$shortdesc = $result->ShortDescription;

			$Description = htmlentities($desc);
			$ShortDescription = htmlentities($shortdesc);


			$data = array(
				'massage' 	=> 'success',
				'error' 	=> 'false',
				'result' 	=>  array('DID' 				=> $result->DID,
		                              'SliderGroup' 		=> $result->SliderGroup, 
		                              'ImagePath' 			=> $result->ImagePath,
		                              'ImageSlider' 		=> $result->ImageSlider,
		                              'VideoPath' 			=> $result->VideoPath,
		                              'ShortDescription' 	=> $ShortDescription,
		                              'Description' 		=> $Description,
		                              'OrderNo' 			=> $result->OrderNo,
		                              'Active'				=> $result->Active,
		                              'RBU' 				=> $result->RBU,
		                              'RBT' 				=> $result->RBT,
                          ),
			);
	*/

}