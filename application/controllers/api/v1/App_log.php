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

class App_log extends REST_Controller
{
	function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');
	}

	public function foo_get(){
		pre('this attraction controller..');
	}


	public function add_post()
	{
		$MemberID = $this->post('MemberID');
		$AppAction = $this->post('AppAction');

		if (empty($MemberID)  || empty($AppAction)) 
		{
			$response = array(
				'message' => 'Invalid params',
				'error' => true,
			);
			$this->response($response, 400);
		}

		$query = $this->db->query("SELECT MemberID 
								   FROM ms_member 
								   WHERE DID = '{$MemberID}'"
								  )->row()->MemberID;
		if ($query === false)
		{
			$response = array(
				'message' => 'Member not registered',
				'error' => true,
			);
			$this->response($response, 400);
		}
		else
		{

			$data["AppAction"]           = $AppAction;
			$data["MemberID"]            = $MemberID;
			$data["MemberCode"]          = $query;
			$data["RBT"]           		 = date('Y-m-d H:i:sa');

			$this->db->insert('app_log',$data);

			$response = array(
				'message' => 'oke',
				'error' => false,
			);
			$this->response($response, 200);
		}
		
	}

	public function getall_get()
	{
		

		try{
			$query = $this->db->query(" SELECT *
										FROM app_log
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->result();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
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

	public function opentimeAVG_get()
	{

		try
		{
			$query = $this->db->query("SELECT COUNT(*) * 1.0 / count(DISTINCT date(RBT)) Average
									   FROM app_log
									   WHERE AppAction = 'LOGIN'
									   ");
			if($query === FALSE)
				throw new Exception();

				$result = $query->result();

				$data = array(
							  'massage' => 'success',
							  'error' 	=> 'false',
							  'data'	=>	$result
							   );
				
				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 400);
				}
		}
		catch(Exception $e)
		{
			print_r($this->db->_error_number());die;
		}
	}
		




}