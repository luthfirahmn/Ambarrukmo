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

class SupportChat extends REST_Controller
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
		 pre('this support chat controller');
	} 


	public function add_post()
	{
		$MemberID			= $this->post('MemberID');
		$ChatTime			= $this->post('ChatTime');
		$Message			= $this->post('Message');
		$RBT				= DateSekarang();

		if($this->post())
		{
			try{
				
				$query = $this->db->query("	INSERT INTO tr_chat( MemberID, ChatTime, Message, RBT)
											SELECT 
												'{$MemberID}' as MemberID
												,'{$ChatTime}' as ChatTime
												,'{$Message}' as Message
												,'{$RBT}' as RBT
										");						

				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	

				if($result)
				{
					$data = array(
						'massage' => 'success send chat..',
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
												chat.MemberID AS  ChatMemberID
												,chat.EmpID AS ChatEmpID
												,chat.ChatTime
												,chat.Message
												,chat.RBT
												,emp.DID AS EmpDID
												,emp.EmpID AS EmpID
												,emp.FullName AS EmpName
												,member.DID AS MemDID
												,member.MemberID AS MemID
												,member.FullName AS MemName

											FROM tr_chat chat
											LEFT JOIN ms_employee emp ON emp.DID = chat.EmpID
											LEFT JOIN ms_member member ON member.DID = chat.MemberID
											WHERE chat.MemberID = '{$MemberID}'
											AND chat.DELETED = '0'
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
