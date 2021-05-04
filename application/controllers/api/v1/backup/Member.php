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

class Member extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');
	}

	public function add_post()
	{
		try {

			//sendOTP("taufikagusetiana@gmail.com",1234);

			if (empty($this->post("Email"))  || empty($this->post("Password")) || empty($this->post("CountryPrefixNo")) || empty($this->post("MobilePhoneNo"))) {

				$response = array(
					'message' => 'Invalid params',
					'error' => true,
				);
				$this->response($response, 200);
			}

			/* cek */
			$cek_email = $this->db->query(" SELECT * 
											FROM ms_member 
											WHERE Email = '{$this->post('Email')}'
											");

			if ($cek_email === false)
				throw new Exception();

			$member = $cek_email->num_rows();

			if ($member != 0) {

				$response = array(
					'message' => 'Email already exists',
					'error' => true,
				);
				$this->response($response, 200);
			}
			/* cek */

			date_default_timezone_set('Asia/Jakarta');
			$year = date("Y");

			$query = $this->db->query(" SELECT MemberID , Email
                                        FROM ms_member 
                                        WHERE MID(MemberID , 3, 4 ) = $year 
										ORDER BY MemberID DESC LIMIT 1");



			if ($query === false)
				throw new Exception();

			$member = $query->row();

			if ($query->num_rows() < 1) {
				$seq_number = "00001";
			} else {
				$get_seq_number = (substr($member->MemberID, 6, 5) + 1);
				$seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
			}

			$data["Email"]          = $this->post("Email");
			$data["Password"]       = $this->bcrypt->hash_password($this->post("Password"));
			$data["CountryPrefixNo"] = $this->post("CountryPrefixNo");
			$data["MobilePhoneNo"]  = $this->post("MobilePhoneNo");
			$data["MemberID"]      	= "AR" . $year . $seq_number;
			$Datenow 				= date("Y-m-d H:i:s");
			$newtimestamp 			= strtotime($Datenow . '+ 15 minutes');
			$data["OTPExpired"]		= date('Y-m-d H:i:s', $newtimestamp);
			$data["OTP"]            = rand(1000, 9999);

			$this->db->save_queries = TRUE;
			$this->db->insert("ms_member", $data);

			if ($this->db->insert_id() > 1) {

				$sql 		= $this->db->last_query();
				$page 		= base_url('api/v1/member/add');
				tr_log($sql, $page, $data["MemberID"]);

				$send_email     = sendOTP("OTP", $this->post("Email"), $data["OTP"]);

				$response = array(
					'message' => 'Insert success..',
					'error' => false,
					'data' => $data
				);
				$this->response($response, 200);
			} else {
				$response = array(
					'message' => 'failed..',
					'error' => true,
				);
				$this->response($response, 200);
			}
		} catch (Exception $e) {
			return $e;
		}
	}

	public function update_post()
	{
		try {

			$data['FirstName'] 	= $this->post('FirstName');
			$data['LastName'] 	= $this->post('LastName');
			$data['FullName'] 	= $this->post('FullName');
			$data['Gender']   	= $this->post('Gender');
			$data['Address1'] 	= $this->post('Address1');
			$data['Address2'] 	= $this->post('Address2');

			$MemberId = $this->post("MemberID");

			$this->db->save_queries = TRUE;
			$this->db->where("MemberID", $MemberId);
			$this->db->update("ms_member", $data);

			if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

				$sql 		= $this->db->last_query();
				$page 		= base_url('api/v1/member/update');
				tr_log($sql, $page, $MemberId);

				$response = array(
					'message' => 'Data Updated...',
					'error' => false,
				);
				$this->response($response, 200);
			} else {
				$response = array(
					'message' => 'Data Not Updated...',
					'error' => true,
				);
				$this->response($response, 200);
			}
		} catch (Exception $e) {
			return $e;
		}
	}



	public function login_post()
	{

		if (empty($this->post("Email")) || empty($this->post("Password"))) {

			$response = array(
				'message' => 'Invalid params',
				'error' => true,
			);
			$this->response($response, 200);
		}


		try {

			$query = $this->db->query(" SELECT MemberID, Email, Password
                                     FROM ms_member 
									 WHERE email = '{$this->post("Email")}'");



			if ($query === false)
				throw new Exception();

			$member = $query->row();

			if ($member) {
				$hash = $member->Password;
				$hashp = $this->bcrypt->check_password($this->post("Password"), $hash);

				if ($hashp) {
					$response = array(
						'message' => 'Success Login',
						'error' => false,
						'data' => array(
							"MemberID" => $member->MemberID,
							"Email" => $member->Email
						)
					);
					$this->response($response, 200);
				} else {
					$response = array(
						'message' => 'Invalid Login',
						'error' => true,
					);
					$this->response($response, 200);
				}
			} else {

				$response = array(
					'message' => 'Member Not found',
					'error' => true,
				);
				$this->response($response, 200);
			}
		} catch (Exception $e) {
			return $e;
		}
	}


	public function get_get()
	{
		$id = $this->get('MemberID');

		if ($id == null) {
			try {
				$query = $this->db->query(" SELECT * 
											FROM ms_member	
											-- WHERE NULLIF (deleted_by , '') IS NULL  
											-- AND NULLIF (deleted_at , '') IS NULL 
										");

				if ($query === FALSE)
					throw new Exception();

				$result = $query->result();

				$data = array(
					'message' => 'success',
					'error' => 'false',
					'data' => $result
				);

				if ($result) {
					$this->response($data, 200);
				} else {
					$this->response($data, 404);
				}
			} catch (Exception $e) {

				print_r($this->db->_error_number());
				die;
			}
		} else {
			try {
				$query = $this->db->query(" SELECT * 
											FROM ms_member	
											-- WHERE NULLIF (deleted_by , '') IS NULL  
											-- AND NULLIF (deleted_at , '') IS NULL 
											WHERE MemberID = '{$id}'
										");

				if ($query === FALSE)
					throw new Exception();

				$result = $query->result();

				$data = array(
					'message' => 'success',
					'error' => 'flase',
					'data' => $result
				);

				if ($result) {
					$this->response($data, 200);
				} else {
					$this->response($data, 404);
				}
			} catch (Exception $e) {

				print_r($this->db->_error_number());
				die;
			}
		}
	}

	public function forgetpassword_post()
	{

		$email = $this->post('Email');

		/* GET MEMBERID */
		$MemberId = $this->db->query("SELECT MemberID FROM ms_member WHERE Email = '{$email}'")->row()->MemberID;

		$rand  = rand(100000, 999999);
		$data["Password"]     = $this->bcrypt->hash_password($rand);

		$this->db->save_queries = TRUE;
		$this->db->where("Email", $email);
		$this->db->update("ms_member", $data);

		if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

			$sql 		= $this->db->last_query();
			$page 		= base_url('api/v1/member/forgetpassword');
			tr_log($sql, $page, $MemberId);

			$send_email     = sendOTP("ForgetPass", $this->post("Email"),  $rand);

			$response = array(
				'message' => 'success',
				'error' => false,
			);
			$this->response($response, 200);
		} else {

			$response = array(
				'message' => 'Error update password',
				'error' => true,
			);
			$this->response($response, 200);
		}
	}

	public function changepassword_post()
	{
		$MemberId = $this->post('MemberId');
		$old_password = $this->post('Old_Password');

		$this->db->select("Password");
		$this->db->from("ms_member");
		$this->db->where("MemberId", $MemberId);
		$query = $this->db->get();

		$member  = $query->row();
		if ($member) {
			$hash = $member->Password;

			$hashp = $this->bcrypt->check_password($old_password, $hash);

			if ($hashp) {

				$data['Password'] = $this->bcrypt->hash_password($this->post('New_Password'));

				$this->db->save_queries = TRUE;
				$this->db->where("MemberID", $MemberId);
				$this->db->update("ms_member", $data);

				if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

					$sql 		= $this->db->last_query();
					$page 		= base_url('api/v1/member/changepassword');
					tr_log($sql, $page, $MemberId);

					$response = array(
						'message' => 'Password Updated',
						'error' => false,
					);
					$this->response($response, 200);
				} else {
					$response = array(
						'message' => 'Password Not Updated ',
						'error' => true,
					);
					$this->response($response, 200);
				}
			} else {

				$response = array(
					'message' => 'Password Not Matching ',
					'error' => true,
				);
				$this->response($response, 200);
			}
		}

		$response = array(
			'message' => 'Member not found',
			'error' => true,
		);
		$this->response($response, 200);
	}
}
