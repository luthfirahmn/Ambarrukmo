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

class MemberBOB extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');
		$this->load->library('Token');

		date_default_timezone_set('Asia/Jakarta');
	}

	
	public function getlist_get()
	{
		/*VALIDATE TOKEN*/

		$Token = $this->get('Token');
		$this->token->ValidateToken($Token);

		/*START*/

		try{
			$Datenow = date("Y-m-d H:i:s");
			$query = $this->db->query(" SELECT *

										FROM ms_member
										/*WHERE SBT > '{$Datenow}'*/
										ORDER BY SBT ASC
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->result();
				foreach($result as $key => $val){
			       $val->Path = base64_encode("upload/MEMBER/").$val->IDPhoto;
			     }
					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);


				/*Log API*/
				$LogType     	= 'OUT';
				$LogRequest  	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$LogResponse 	=  json_encode($result);
                $LogIP        	= $this->input->ip_address();
                log_api($LogType, $LogRequest, $LogResponse, $LogIP);

				if($result)
				{
					$response = $this->response($data, 200);

				}
				else
				{
					$response = $this->response($data, 404);
				}

		

		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}
		

	}

	public function get_get()
	{
		/*VALIDATE TOKEN*/

		$Token = $this->get('Token');
		$this->token->ValidateToken($Token);

		/*START*/
		$MemberID = $this->get('MemberID');
		if($MemberID == null)
		{
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}

		try
		{

			$Datenow = date("Y-m-d H:i:s");
			$query = $this->db->query(" SELECT *

										FROM ms_member
										WHERE MemberID = '{$MemberID}'
										/*AND SBT > '{$Datenow}'*/
										ORDER BY SBT ASC
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->result();
				foreach($result as $key => $val){
			       $val->Path = base64_encode("upload/MEMBER/").$val->IDPhoto;
			     }
					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
					/*Log API*/
					$LogType     	= 'OUT';
					$LogRequest  	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$LogResponse 	=  json_encode($result);
	                $LogIP        	= $this->input->ip_address();
	                log_api($LogType, $LogRequest, $LogResponse, $LogIP);
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


	public function add_post()
	{


		/*VALIDATE TOKEN*/

		$Token = $this->post('Token');
		$this->token->ValidateToken($Token);

		/*START*/
		
		$post_memberid = $this->post('MemberID');


	    /*POST DATA*/

	    $query = $this->db->query("	SELECT *
											FROM ms_member
											WHERE
												MemberID = '{$post_memberid}'
										");
		if($query===FALSE)
			throw new Exception();

		$all_data = $query->row();

		$MemberID 			= $this->post('MemberID')?$this->post('MemberID'):$all_data->MemberID;
		$MemberLevel		= $this->post('MemberLevel')?$this->post('MemberLevel'):$all_data->MemberLevel;
		$Email 				= $this->post('Email')?$this->post('Email'):$all_data->Email;
		$Password       	= $this->bcrypt->hash_password($this->post("Password"))?$this->bcrypt->hash_password($this->post("Password")):$all_data->Password;
		$CountryPrefixNo	= $this->post('CountryPrefixNo')?$this->post('CountryPrefixNo'):$all_data->CountryPrefixNo;
		$MobilePhoneNo		= $this->post('MobilePhoneNo')?$this->post('MobilePhoneNo'):$all_data->MobilePhoneNo;
		
		
		$PhoneNo			= $this->post('PhoneNo')?$this->post('PhoneNo'):$all_data->PhoneNo;
		$JoinDate			= date("Y-m-d H:i:s")?:$all_data->JoinDate;
		$FirstName			= $this->post('FirstName')?$this->post('FirstName'):$all_data->FirstName;
		$LastName			= $this->post('LastName')?$this->post('LastName'):$all_data->LastName;
		$FullName			= $this->post('FirstName').' '.$this->post('LastName')?$this->post('LastName'):$all_data->LastName;
		$Gender				= $this->post('Gender')?$this->post('Gender'):$all_data->Gender;
		$BirthPlace			= $this->post('BirthPlace')?$this->post('BirthPlace'):$all_data->BirthPlace;
		$BirthDate			= $this->post('BirthDate')?$this->post('BirthDate'):$all_data->BirthDate;
		$Address1			= $this->post('Address1')?$this->post('Address1'):$all_data->Address1;	
		$Address2			= $this->post('Address2')?$this->post('Address2'):$all_data->Address2;	
		$ReligionID			= $this->post('ReligionID')?$this->post('ReligionID'):$all_data->ReligionID;	
		$WorkFieldID		= $this->post('WorkFieldID')?$this->post('WorkFieldID'):$all_data->WorkFieldID;
		$ProvinceID			= $this->post('ProvinceID')?$this->post('ProvinceID'):$all_data->ProvinceID;
		$StateID			= $this->post('StateID')?$this->post('StateID'):$all_data->StateID;
		$District			= $this->post('District')?$this->post('District'):$all_data->District;	
		$SubDistrict		= $this->post('SubDistrict')?$this->post('SubDistrict'):$all_data->SubDistrict;	
		$ZipCode			= $this->post('ZipCode')?$this->post('ZipCode'):$all_data->ZipCode;
		$NIDType			= $this->post('NIDType')?$this->post('NIDType'):$all_data->NIDType;	
		$NIDNo				= $this->post('NIDNo')?$this->post('NIDNo'):$all_data->NIDNo;
		/*$Active				= $this->post('Active')?$this->post('Active'):$all_data->Active;*/	
		$SBT				= date("Y-m-d H:i:s");


		/*Photo Base64*/

		$IDPhoto			= $this->post('IDPhoto')?$this->post('IDPhoto'):$all_data->IDPhoto;


		/*UPLOAD*/

/*		if ($_FILES['IDPhoto']['name'] == "") :

            $this->db->select("*");
            $this->db->from("ms_member");
            $this->db->where("MemberID", $MemberID);

            $result = $this->db->get()->row();

            $IDPhoto = $result->IDPhoto;

		else :

            $filename         = isset($_FILES['IDPhoto']['name']) ? $_FILES['IDPhoto']['name'] : NULL;
            $info             = pathinfo($filename);
            $image_name     = url_title(basename($filename, '.' . $info['extension']));
            $random         = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
            $image_filename = $image_name . '_' . $random . '.' . $info['extension'];

	        if (!file_exists(IMAGE_BLOCK_APPS_ROOT_MEMBER . $image_filename)) 
	        {
	                $image_filename = $image_filename;
	        }

            $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_MEMBER;
            $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
            $configImage['max_size']        = 500;
            $configImage['file_name']       = $image_filename;
            $configImage['overwrite']       = FALSE;


            $this->load->library('upload', $configImage);
            $this->upload->initialize($configImage);


            if (!$this->upload->do_upload("IDPhoto")) {
            	$response = array(
					'message' => 'failed..' . $this->upload->display_errors('', ''),
					'error' => true,
				);
				$this->response($response, 400);
	                  
            }


            $file   = IMAGE_BLOCK_APPS_ROOT_MEMBER . $image_filename;

            $NewImageWidth          = 400;
            $NewImageHeight         = 400;
            $Quality                = 50;

            resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);

            // $path = IMAGE_BLOCK_APPS_ROOT_MEMBER. $image_filename;
            // $type = pathinfo($path, PATHINFO_EXTENSION);
            // $dataimg = file_get_contents($path);
            // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
            $IDPhoto =  $image_filename;

        endif;*/

		/*COUNT MEMBER*/
		$query = $this->db->query(" SELECT * 
						  			FROM ms_member 
						  			WHERE MemberID = '{$post_memberid}'
		");
		$num_rows = $query->num_rows();

	    /*SELECT MEMBER*/
		$query = $this->db->query(" SELECT * 
						  			FROM ms_member 
		");
		$result = $query->result();

		foreach ($result as $row) 
		{
			$db_memberid =  $row->MemberID;

			if ($num_rows < 1) {

				$data = array(
					'MemberID' 			=> $MemberID,
					'IDPhoto' 			=> $IDPhoto,
					'MemberLevel' 		=> $MemberLevel,
					'Email' 			=> $Email,
					'Password' 			=> $Password,
					'CountryPrefixNo' 	=> $CountryPrefixNo,
					'MobilePhoneNo'		=> $MobilePhoneNo,
					'PhoneNo' 			=> $PhoneNo,
					'JoinDate' 			=> $JoinDate,
					'FirstName' 		=> $FirstName,
					'LastName' 			=> $LastName,
					'FullName' 			=> $FullName,
					'Gender' 			=> $Gender,
					'BirthPlace' 		=> $BirthPlace,
					'BirthDate' 		=> $BirthDate,
					'Address1' 			=> $Address1,
					'Address2' 			=> $Address2,
					'ReligionID' 		=> $ReligionID,
					'WorkFieldID' 		=> $WorkFieldID,
					'ProvinceID' 		=> $ProvinceID,
					'StateID' 			=> $StateID,
					'District' 			=> $District,
					'SubDistrict' 		=> $SubDistrict,
					'ZipCode' 			=> $ZipCode,
					'NIDType' 			=> $NIDType,
					'NIDNo' 			=> $NIDNo,
					/*'Active' 			=> $Active,*/
					'SBT' 				=> $SBT,
				 );
				$res = $this->db->insert('ms_member',$data);
				/*Log API*/
				if ($res) 
				{
					
					$LogType     	= 'IN';
					$LogRequest  	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$LogResponse 	=  json_encode($data);
	                $LogIP        	= $this->input->ip_address();
	                log_api($LogType, $LogRequest, $LogResponse, $LogIP);
				}
				
			}
			else
			{
				$data = array(
					'MemberLevel' 		=> $MemberLevel,
					'IDPhoto' 			=> $IDPhoto,
					'Email' 			=> $Email,
					'Password' 			=> $Password,
					'CountryPrefixNo' 	=> $CountryPrefixNo,
					'MobilePhoneNo'		=> $MobilePhoneNo,
					'PhoneNo' 			=> $PhoneNo,
					'JoinDate' 			=> $JoinDate,
					'FirstName' 		=> $FirstName,
					'LastName' 			=> $LastName,
					'FullName' 			=> $FullName,
					'Gender' 			=> $Gender,
					'BirthPlace' 		=> $BirthPlace,
					'BirthDate' 		=> $BirthDate,
					'Address1' 			=> $Address1,
					'Address2' 			=> $Address2,
					'ReligionID' 		=> $ReligionID,
					'WorkFieldID' 		=> $WorkFieldID,
					'ProvinceID' 		=> $ProvinceID,
					'StateID' 			=> $StateID,
					'District' 			=> $District,
					'SubDistrict' 		=> $SubDistrict,
					'ZipCode' 			=> $ZipCode,
					'NIDType' 			=> $NIDType,
					'NIDNo' 			=> $NIDNo,
					/*'Active' 			=> $Active,*/
					'SBT' 				=> $SBT,
				 );
				$res = $this->db->where('MemberID',$post_memberid)
						 ->update('ms_member',$data);

				/*Log API*/
				if ($res) 
				{
					
					$LogType     	= 'IN';
					$LogRequest  	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$LogResponse 	=  json_encode($data);
	                $LogIP        	= $this->input->ip_address();
	                log_api($LogType, $LogRequest, $LogResponse, $LogIP);
				}
			}
			if ($res) 
			{
				$data = array(
							'massage' => 'Success',
							'error' => 'false'
						);
				$this->response($data, 200);
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
