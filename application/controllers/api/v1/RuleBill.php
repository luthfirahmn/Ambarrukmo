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

class RuleBill extends REST_Controller
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
		 pre('this tenant controller');
	}

	public function getlist_get()
	{
		

		try{
			$query = $this->db->query(" SELECT
											RuleTitle
											,StartDate
											,PointRatio
											,EventMultiply

										FROM rule_bill
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
		$DID = $this->get('DID');
		if($DID == null)
		{
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}

		try{
			$query = $this->db->query(" SELECT
											RuleTitle
											,StartDate
											,PointRatio
											,EventMultiply

										FROM rule_bill
										WHERE DID = '{$DID}'
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



	public function add_post()
	{
		$RuleTitle				= $this->post('RuleTitle');
		$StartDate				= $this->post('StartDate');
		$PointRatio				= $this->post('PointRatio');
		$EventMultiply			= $this->post('EventMultiply');

		if($this->post())
		{
			try{
				
				$query = $this->db->query("	INSERT INTO rule_bill( RuleTitle, StartDate, PointRatio, EventMultiply)
											SELECT 
												'{$RuleTitle}' as RuleTitle
												,'{$StartDate}' as StartDate
												,'{$PointRatio}' as PointRatio
												,'{$EventMultiply}' as EventMultiply
										");			


				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	
				$sql         = $this->db->last_query();
	            $page         = base_url('api/v1/RuleBill/add');
	            tr_log($sql, $page, $this->post("RBU"));


				if($result)
				{
					$data = array(
						'massage' => 'success created..',
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

	public function delete_delete()
	{

		$DID = $this->delete('DID');

		if($DID == null){
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
		else{
			try{
				

					//$obj = new stdClass();
					// process update
				 	$query = $this->db->query(" DELETE 
				 								FROM rule_bill
												WHERE DID = '{$DID}'
											");

					$sql         = $this->db->last_query();
	                $page         = base_url('api/v1/RuleBill/delete');
	                tr_log($sql, $page, $this->delete("DID"));

					
						if($query){
							$data = array(
		
								'massage' => 'data deleted',
								'error' => false,
							);
			
							$this->response($data, 201);

						}else{
							$data = array(
		
								'massage' => 'data not deleted',
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



	public function update_put()
	{

		$DID = $this->put('DID');

		if($DID == null)
		{
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
		/*$RuleTitle			= $this->put('RuleTitle');
		$StartDate			= $this->put('StartDate');
		$PointRatio		= $this->put('PointRatio');
		$EventMultiply			= $this->put('EventMultiply');
		$Active				= $this->put('Active');
		$RBU				= $this->put('RBU');
		$RBT				= DateSekarang();*/

		if($this->put())
		{
			try
			{
				$query = $this->db->query("	SELECT 
												RuleTitle
												,StartDate
												,PointRatio
												,EventMultiply
											FROM rule_bill
											WHERE
												DID = '{$DID}'
										");
				if($query===FALSE)
					throw new Exception();

				$all_data = $query->row();
				//print_r($article);die;
				if(!empty($all_data))
				{

					//$obj = new stdClass();

					$RuleTitle				= $this->put('RuleTitle')?$this->put('RuleTitle'):$all_data->RuleTitle;
					$StartDate			= $this->put('StartDate')?$this->put('StartDate'):$all_data->StartDate;
					$PointRatio			= $this->put('PointRatio')?$this->put('PointRatio'):$all_data->PointRatio;
					$EventMultiply				= $this->put('EventMultiply')?$this->put('EventMultiply'):$all_data->EventMultiply;
					$RBT					= DateSekarang();	
				
				$query = $this->db->query("	UPDATE  rule_bill
											SET
												RuleTitle = '{$RuleTitle}'
												, StartDate = '{$StartDate}'
												, PointRatio = '{$PointRatio}'
												, EventMultiply = '{$EventMultiply}' 
											WHERE
												DID = '{$DID}'
										");			


				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	
				$sql         = $this->db->last_query();
	            $page         = base_url('api/v1/RuleBill/update');
	            tr_log($sql, $page, $this->put("RBU"));

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
			catch(Exception $e) 
			{

				 print_r($this->db->_error_number());die;
			}

		}
		else
		{
			$data = array(
				'massage' => 'invalid',
				'error' => 'true'
			);
			$this->response($data, 403);
		}
	}


}