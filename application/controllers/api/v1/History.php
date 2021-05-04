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

class History extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}


	public function gethistory_post()
	{
		if (empty($this->post("MemberID"))  ) {

				$response = array(
					'message' => 'Invalid params',
					'error' => true,
				);
				$this->response($response, 400);
			}

		try{
			$MemberID = $this->post('MemberID');

			$query = $this->db->query(" SELECT * 
										,tpoint.DID AS pDID
										,tpoint.MemberID AS pMemberID
										,tpoint.VoucherID AS pVoucherID
										,tpoint.VoucherCode AS pVoucherCode
										,CONCAT('VOUCHER/',VoucherIMG) AS VoucherPath
										,CONCAT(DATE_FORMAT(VoucherBT, '%d %M'), ' - ' ,DATE_FORMAT(tpoint.ExpiredTime, '%d %M %Y')) AS ExpiredVoucher
										FROM tr_point AS tpoint
										LEFT JOIN ms_voucher AS voucher ON voucher.DID = tpoint.VoucherID

										WHERE tpoint.MemberID ='{$MemberID}'
										AND tpoint.VoucherID  != '0'

			");

			$result = $query->result();


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



}