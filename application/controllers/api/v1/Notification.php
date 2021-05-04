<?php defined('BASEPATH') or exit('No direct script access allowed');

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

class Notification extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');
		//$this->load->model('member_model', 'article', TRUE);
	}
	/* code respone 
	  	200 => ok ,
		201 => created ,
		400 => bad request ,
		401 => unauthorized ,
		403 => forbidden ,
		404 => notfound ,
		405 => methode not allowed ,

	*/
 	public function foo_get()
 	{
		 pre('this Notification controller');
	}

	public function check_post()
	{
		$MemberID = $this->post('MemberID');

		$query = $this->db->query("SELECT MemberID
								   FROM ms_onesignal
								   WHERE MemberID = '{$MemberID}'
						");
		$result = $query->num_rows();
		if($result < 1 )
		{
			$data = array(
							'MemberID' => $this->input->post('MemberID'),
							'PlayerID'	=> $this->input->post('PlayerID')
			 				);
			$insert = $this->db->insert('ms_onesignal',$data);
			if($insert)
			{
				$response = array(
								  'message' => 'success created',
								  'error'	=> false, 
								);
				$this->response($response, 201);
			}
			else
			{
				$response = array(
								  'message' => 'data not created',
								  'error'	=> true, 
								);
				$this->response($response, 400);
			}

		}
		else
		{
			$data = array(
							'PlayerID' => $this->post('PlayerID')
						);
			$this->db->where('MemberID',$MemberID);
			$update = $this->db->update('ms_onesignal',$data);

			if($update)
			{
				$response = array(
								  'message' => 'success updated',
								  'error'	=> false, 
								);
				$this->response($response, 201);
			}
			else
			{
				$response = array(
								  'message' => 'data not updated',
								  'error'	=> true, 
								);
				$this->response($response, 400);
			}
		}

	}

	public function update_post()
	{

		$MemberID = $this->post('MemberID');

		if($MemberID == null){
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
		else{
			try{
				

					//$obj = new stdClass();

					$Status				= $this->post('Status');
					
					// process update
				 	$query = $this->db->query(" UPDATE ms_onesignal 
												SET 
													Status ='{$Status}'
												WHERE MemberID = '{$MemberID}'
											");

					$sql         = $this->db->last_query();
	                $page         = base_url('api/v1/Notification/update');
	                tr_log($sql, $page, $this->post("MemberID"));

					
						if($query){
							$data = array(
		
								'massage' => 'data updated',
								'error' => false,
							);
			
							$this->response($data, 201);

						}else{
							$data = array(
		
								'massage' => 'data not updated',
								'error' => true,
							);
			
							$this->response($data, 400);

						}
	
				}
			catch(Exception $e)
			{

			print_r($this->db->_error_number());die;

			}
		}

	}

	public function add_post()
	{
		$MemberID			= $this->post('MemberID');
		$PlayerID			= $this->post('PlayerID');
		$Status				= $this->post('Status');

		if($this->post())
		{
			try{
				
				$query = $this->db->query("	INSERT INTO ms_onesignal( MemberID, PlayerID, Status)
											SELECT 
												'{$MemberID}' as MemberID
												,'{$PlayerID}' as PlayerID
												,'{$Status}' as Status
										");						

				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();
				$sql         = $this->db->last_query();
                $page         = base_url('api/v1/Notification/add');
                tr_log($sql, $page, $this->post("MemberID"));	

				if($result)
				{
					$data = array(
						'massage' => 'success..',
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
			catch(Exception $e) 
			{

				 print_r($this->db->_error_number());die;
			}
		}
		else{
			$data = array(
				'massage' => 'invalid',
				'error' => 'true'
			);
			$this->response($data, 403);
		}
	}

	public function get_get()
	{
		$MemberID = $this->get('MemberID');

		if($MemberID == null)
		{
			$response = array(
				'massage' => 'invalid ID..',
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
		else
		{
			try{
				$query = $this->db->query(" SELECT
												ms_onesignal.MemberID AS  SigMemberID
												,ms_onesignal.PlayerID AS SigPlayerID
												,ms_onesignal.Status
												,ms_employee.DID AS EmpDID
												,ms_employee.EmpID AS EmpID
												,ms_employee.FullName AS EmpName
												,ms_member.DID AS MemDID
												,ms_member.MemberID AS MemID
												,ms_member.FullName AS MemName

											FROM ms_onesignal
											LEFT JOIN ms_employee ON ms_employee.DID = ms_onesignal.PlayerID
											LEFT JOIN ms_member ON ms_member.DID = ms_onesignal.MemberID
											WHERE ms_onesignal.MemberID = '{$MemberID}'
										");
				if($query===FALSE)
					throw new Exception();

					$result = $query->result();

						$data = array(
							'massage' => 'success',
							'error' => 'flase',
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

	}

}