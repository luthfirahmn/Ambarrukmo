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

			if (empty($this->post("MobilePhoneNo"))  || empty($this->post("Password")) || empty($this->post("MemberLevel")) || empty($this->post("Email"))) {

				$response = array(
					'message' => 'Invalid params',
					'error' => true,
				);
				$this->response($response, 400);
			}

			/* cek */
			$cek_number = $this->db->query(" SELECT * 
											FROM ms_member 
											WHERE MobilePhoneNo = '{$this->post('MobilePhoneNo')}'
											");

			if ($cek_number === false)
				throw new Exception();

			$member = $cek_number->num_rows();

			if ($member != 0) {

				$response = array(
					'message' => 'Phone Number already exists',
					'error' => true,
				);
				$this->response($response, 400);
			}

            /* CEK MEMBER ID */

            date_default_timezone_set('Asia/Jakarta');
            $year = date("Y");

            $query = $this->db->query(" SELECT MemberID , Email
                                        FROM ms_member 
                                        WHERE MID(MemberID , 5, 4 ) = $year 
                                        ORDER BY MemberID DESC LIMIT 1");



            if ($query === false)
                throw new Exception();

            $member = $query->row();
            $memberid = 0;
            if (strtolower($this->post("MemberLevel")) == "reguler") {
                if ($query->num_rows() < 1) {
                    $seq_number = 00000001;
                } else {
                    $get_seq_number = (int) (substr($member->MemberID, 8, 8) + 1 );
                    $seq_number = str_pad($get_seq_number, 8, "0", STR_PAD_LEFT);
                }
                $memberid = (int) 8888 . $year . $seq_number;
            } else {
                pre("error...");
            }

			$data["Email"]           = $this->post("Email");
			$data["Password"]        = $this->bcrypt->hash_password($this->post("Password"));
			$data["CountryPrefixNo"] = $this->post("CountryPrefixNo");
			$data["MobilePhoneNo"]   = $this->post("MobilePhoneNo");
			$data["MemberID"]      	 = $memberid;
			$Datenow 				 = date("Y-m-d H:i:s");

			$data["MemberLevel"]      = $this->post("MemberLevel");
			$data["PhoneNo"]          = $this->post("PhoneNo");
			$data["JoinDate"]         = $this->post("JoinDate");
			$data["FirstName"]        = $this->post("FirstName");
			$data["LastName"]         = $this->post("LastName");
			$data["FullName"]         = $this->post("FirstName").' '.$this->post("LastName");
			$data["Gender"]           = $this->post("Gender");
			$data["BirthPlace"]       = $this->post("BirthPlace");
			$data["BirthDate"]        = $this->post("BirthDate");
			$data["Address1"]         = $this->post("Address1");
			$data["Address2"]         = $this->post("Address2");
			$data["ReligionID"]       = $this->post("ReligionID");
			$data["WorkFieldID"]      = $this->post("WorkFieldID");
			$data["ProvinceID"]       = $this->post("ProvinceID");
			$data["StateID"]          = $this->post("StateID");
			$data["District"]         = $this->post("District");
			$data["SubDistrict"]      = $this->post("SubDistrict");
			$data["ZipCode"]          = $this->post("ZipCode");
			$data["NIDType"]          = $this->post("NIDType");
			$data["NIDNo"]            = $this->post("NIDNo");
			$data["Active"]           = $this->post("Active");
			$data["SBT"]         	  = $Datenow;

			$IDPhoto = $_POST['IDPhoto'];
			$MemberID = $memberid;
			$folderPath = IMAGE_BLOCK_APPS_ROOT_MEMBER;
		    $image_parts = explode(";base64,", $IDPhoto);
		    $image_type_aux = explode("image/", $image_parts[0]);
		    $image_type = $image_type_aux[1];
		    $image_base64 = base64_decode($image_parts[1]);
		    $file = $folderPath . $MemberID . '_' . uniqid() . '.' . $image_type;
			$NewImageWidth 		= 800; //New Width of Image
			$NewImageHeight 	= 800; // New Height of Image
			$Quality 		= 50; //Image Quality
			$checkValidImage = @getimagesize($file);
		    $file_name = explode("/", $file);
		    file_put_contents($file, $image_base64);
		    $UrlFile = $file_name[3];

		    if(file_exists($file)){
			resizeImage($file,$file, $NewImageWidth,$NewImageHeight,$Quality);
	    	}

			$data["IDPhoto"]       = $UrlFile;

			$this->db->save_queries = TRUE;
			$this->db->insert("ms_member", $data);
			
			if ($this->db->insert_id() > 1) {

				$sql 		= $this->db->last_query();
				$page 		= base_url('api/v1/member/add');
				tr_log($sql, $page, $data["MemberID"]);
				

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
				$this->response($response, 400);
			}
		} catch (Exception $e) {
			return $e;
		}
	}

	public function update_put()
	{

		$MemberID = $this->put('MemberID');

		if($MemberID == null)
		{
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}

		else
		{
				date_default_timezone_set('Asia/Jakarta');

				$query = $this->db->query("	SELECT *
											FROM ms_member
											WHERE
												MemberID = '{$MemberID}'
										");
				if($query===FALSE)
					throw new Exception();

				$all_data = $query->row();

				if (!empty($all_data)) 
				{
					$Email 				= $this->put('Email')?$this->put('Email'):$all_data->Email;
					$CountryPrefixNo	= $this->put('CountryPrefixNo')?$this->put('CountryPrefixNo'):$all_data->CountryPrefixNo;
					$MobilePhoneNo		= $this->put('MobilePhoneNo')?$this->put('MobilePhoneNo'):$all_data->MobilePhoneNo;
					
					$PhoneNo		= $this->put('PhoneNo')?$this->put('PhoneNo'):$all_data->PhoneNo;
					$JoinDate		= $this->put('JoinDate')?$this->put('JoinDate'):$all_data->JoinDate;
					$FirstName		= $this->put('FirstName')?$this->put('FirstName'):$all_data->FirstName;
					$LastName		= $this->put('LastName')?$this->put('LastName'):$all_data->LastName;
					$FullName		= $this->put('FirstName').' '.$this->put('LastName')?$this->put('LastName'):$all_data->LastName;
					$Gender			= $this->put('Gender')?$this->put('Gender'):$all_data->Gender;
					$BirthPlace		= $this->put('BirthPlace')?$this->put('BirthPlace'):$all_data->BirthPlace;
					$BirthDate		= $this->put('BirthDate')?$this->put('BirthDate'):$all_data->BirthDate;
					$Address1		= $this->put('Address1')?$this->put('Address1'):$all_data->Address1;	
					$Address2		= $this->put('Address2')?$this->put('Address2'):$all_data->Address2;	
					$ReligionID		= $this->put('ReligionID')?$this->put('ReligionID'):$all_data->ReligionID;	
					$WorkFieldID	= $this->put('WorkFieldID')?$this->put('WorkFieldID'):$all_data->WorkFieldID;
					$ProvinceID		= $this->put('ProvinceID')?$this->put('ProvinceID'):$all_data->ProvinceID;
					$StateID		= $this->put('StateID')?$this->put('StateID'):$all_data->StateID;
					$District		= $this->put('District')?$this->put('District'):$all_data->District;	
					$SubDistrict	= $this->put('SubDistrict')?$this->put('SubDistrict'):$all_data->SubDistrict;	
					$ZipCode		= $this->put('ZipCode')?$this->put('ZipCode'):$all_data->ZipCode;
					$NIDType		= $this->put('NIDType')?$this->put('NIDType'):$all_data->NIDType;	
					$NIDNo			= $this->put('NIDNo')?$this->put('NIDNo'):$all_data->NIDNo;
					$Active			= $this->put('Active')?$this->put('Active'):$all_data->Active;	
					$SBT			= date("Y-m-d H:i:s");

					if ($this->put('IDPhoto') == "") :

	                    $this->db->select("*");
	                    $this->db->from("ms_member");
	                    $this->db->where("MemberID", $MemberID);

	                    $result = $this->db->get()->row();

	                    $data['IDPhoto'] = $result->IDPhoto;

					else :

	                    $IDPhoto = $_POST['IDPhoto'];
						$MemberID = $this->post('MemberID');
						$folderPath = IMAGE_BLOCK_APPS_ROOT_MEMBER;
					    $image_parts = explode(";base64,", $IDPhoto);
					    $image_type_aux = explode("image/", $image_parts[0]);
					    $image_type = $image_type_aux[1];
					    $image_base64 = base64_decode($image_parts[1]);
					    $file = $folderPath . $MemberID . '_' . uniqid() . '.' . $image_type;
						$NewImageWidth 		= 800; //New Width of Image
						$NewImageHeight 	= 800; // New Height of Image
						$Quality 		= 50; //Image Quality
						$checkValidImage = @getimagesize($file);
					    $file_name = explode("/", $file);
					    file_put_contents($file, $image_base64);
					    $UrlFile = $file_name[3];

					    if(file_exists($file)){
							resizeImage($file,$file, $NewImageWidth,$NewImageHeight,$Quality);
				    	}

                   
                   	 	$IDPhoto =  $UrlFile;

                endif;

                $Password = $this->put('Password');
                if ($Password != '') 
                {
                    $bcryptpass             = $this->bcrypt->hash_password($Password);
                }

				$query = $this->db->query("	UPDATE  ms_tenant
							SET
								Email = '{$Email}'
								, CountryPrefixNo = '{$CountryPrefixNo}'
								, MobilePhoneNo = '{$MobilePhoneNo}'
								, PhoneNo = '{$PhoneNo}' 
								, JoinDate = '{$JoinDate}'
								, FirstName = '{$FirstName}'
								, LastName = '{$LastName}'
								, FullName = '{$FullName}'
								, Gender = '{$Gender}'
								, BirthPlace = '{$BirthPlace}'
								, BirthDate = '{$BirthDate}'
								, ReligionID = '{$ReligionID}'
								, WorkFieldID = '{$WorkFieldID}'
								, ProvinceID = '{$ProvinceID}'
								, StateID = '{$StateID}'
								, District = '{$District}'
								, SubDistrict = '{$SubDistrict}'
								, ZipCode = '{$ZipCode}'
								, NIDType = '{$NIDType}'
								, NIDNo = '{$NIDNo}'
								, Active = '{$Active}'
								, SBT = '{$SBT}'
								, IDPhoto = '{$IDPhoto}'
								, Password = '{$bcryptpass}'
							WHERE
								MemberID = '{$MemberID}'
						");			


				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	
				$sql         = $this->db->last_query();
	            $page         = base_url('api/v1/Member/update');
	            tr_log($sql, $page, $this->put("MemberID"));

		            if($result)
					{
						$data = array(
							'massage' => 'success updated..',
							'error' => 'false'
						);
						$this->response($data, 201);	

					}
					else
					{
						
						$data = array(
							'massage' => 'invalid',
							'error' => 'true'
						);
						$this->response($data, 404);
					}
			}
		}
	}


	public function getlist_get()
	{
		

		try{
			$query = $this->db->query(" SELECT *,CONCAT('MEMBER/',IDPhoto) as IDPath 
										FROM ms_member
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

	public function get_get()
	{
		$MemberID = $this->get('MemberID');
		if($MemberID == null)
		{
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}

		try{
			$query = $this->db->query(" SELECT *,CONCAT('MEMBER/',IDPhoto) as IDPath 

										FROM ms_member
										WHERE MemberID = '{$MemberID}'
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


	public function checkNumber_post()
	{
		$query = $this->db->query("SELECT MobilePhoneNo,Email,MemberID
						   FROM ms_member
						   WHERE MobilePhoneNo = '{$this->post("MobilePhoneNo")}'"
						);
		$res = $query->num_rows();

		$rs = $query->row();

		if ($res < 1) 
		{
			$response = array(
							'message' => 'Mobile Phone Not Registered',
							'error'	=> TRUE
							);
			$this->response($response, 200);
		}
		else
		{
			$response = array(
							'message' => 'Mobile Phone Registered',
							'error'	=> FALSE,
							'data' => array(
							"MemberID" => $rs->MemberID,
							"MobilePhoneNo" => $rs->MobilePhoneNo,
							"Email" => $rs->Email
						)
							);
			$this->response($response, 200);
		}

	}



	public function login_post()
	{

		if (empty($this->post("MobilePhoneNo")) || empty($this->post("Password"))) {

			$response = array(
				'message' => 'Invalid params',
				'error' => true,
			);
			$this->response($response, 200);
		}


		try 
		{

			$query = $this->db->query(" SELECT MemberID, MobilePhoneNo, Password, Email
                                     	FROM ms_member 
										WHERE MobilePhoneNo = '{$this->post("MobilePhoneNo")}'");



			if ($query === false)
				throw new Exception();

			$member = $query->row();

			if ($member) 
			{
				$hash = $member->Password;
				$hashp = $this->bcrypt->check_password($this->post("Password"), $hash);

				if ($hashp) 
				{
					$response = array(
						'message' => 'Success Login',
						'error' => false,
						'data' => array(
							"MemberID" => $member->MemberID,
							"MobilePhoneNo" => $member->MobilePhoneNo,
							"Email" => $member->Email
						)
					);
					$this->response($response, 200);
				} 
				else 
				{
					$response = array(
						'message' => 'Invalid Login',
						'error' => true,
					);
					$this->response($response, 200);
				}
			} 
			else 
			{
				$response = array(
					'message' => 'Member Not found',
					'error' => true,
				);
				$this->response($response, 200);
			}
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}



	public function forgetpassword_post()
	{

		$email = $this->post('Email');
		
		/* GET MEMBERID */
		$MemberId = $this->db->query("SELECT MemberID FROM ms_member WHERE Email = '{$email}'")->row()->MemberID;


		$this->db->save_queries = TRUE;
		$this->db->where("Email", $email);
		$this->db->update("ms_member", $data);

		if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

			$sql 		= $this->db->last_query();
			$page 		= base_url('api/v1/member/forgetpassword');
			tr_log($sql, $page, $MemberId);


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

	public function memberphoto_post()
	{

		if (empty($this->post("MemberPhoto")) && empty($this->post("MemberID"))) {

			$response = array(
				'message' => 'Invalid params',
				'error' => true,
			);
			$this->response($response, 200);
		}


		try 
		{
			$MemberPhoto = $_POST['MemberPhoto'];
			$MemberID = $_POST['MemberID'];
			$folderPath = IMAGE_BLOCK_APPS_ROOT_MEMBER_PHOTO;
		    $image_parts = explode(";base64,", $MemberPhoto);
		    $image_type_aux = explode("image/", $image_parts[0]);
		    $image_type = $image_type_aux[1];
		    $image_base64 = base64_decode($image_parts[1]);
		    $file = $folderPath . $MemberID . '_' . uniqid() . '.' . $image_type;
			$NewImageWidth 		= 800; //New Width of Image
			$NewImageHeight 	= 800; // New Height of Image
			$Quality 		= 50; //Image Quality
			$checkValidImage = @getimagesize($file);
		    $file_name = explode("/", $file);
		    file_put_contents($file, $image_base64);
		    $UrlFile = $file_name[3];

		    if(file_exists($file)){
			resizeImage($file,$file, $NewImageWidth,$NewImageHeight,$Quality);
    		}

    		$MemberPhoto =  $UrlFile;
    		$data = array('MemberPhoto' => $MemberPhoto );	

    				 $this->db->where('MemberID', $MemberID );
    		$query = $this->db->update('ms_member',$data);

    		if ($query) 
    		{
    			$response = array(
				'message' => 'Success Upload Photo',
				'error' => false,
				);
				$this->response($response, 200);
    		}
    		else
    		{
    			$response = array(
				'message' => 'Error Upload Photo',
				'error' => false,
				);
				$this->response($response, 400);
    		}
		}
		catch (Exception $e) 
		{
			return $e;
		}

	}

}