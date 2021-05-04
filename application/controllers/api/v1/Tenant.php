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

class Tenant extends REST_Controller
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


	public function gettenant_get()
	{

      $this->db->select("*");
      $this->db->from("ms_tenant");
      $result = $this->db->get()->result();

      foreach($result as $key => $val){
        $val->Path = base_url("upload/TENANT/").$val->ImagePath;
      }

      $response = array( 
        'message'   => 'success',
        'error'     => false,
        'data'      => $result
    );
    $this->response($response, 200);

    }




	public function getlist_get()
	{
		

		try{
			$query = $this->db->query(" SELECT
											ImagePath
											,TenantName
											,TenantFloor
											,OrderNo
											,Active
											,RBU
											,RBT

										FROM ms_tenant
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

		if($DID == null){
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}

		try{
			$query = $this->db->query(" SELECT
											ImagePath
											,TenantName
											,TenantFloor
											,OrderNo
											,Active
											,RBU
											,RBT

										FROM ms_tenant
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

	public function active_put()
	{

		$DID = $this->put('DID');

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

					$Active				= $this->put('Active');
					$RBT				= datesekarang();
					
					// process update
				 	$query = $this->db->query(" UPDATE ms_tenant
												SET 
													Active ='{$Active}'
													,RBT   ='{$RBT}'
												WHERE DID = '{$DID}'
											");

					$sql         = $this->db->last_query();
	                $page         = base_url('api/v1/Tenant/active');
	                tr_log($sql, $page, $this->post("DID"));

					
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
		$ImagePath			= $this->post('ImagePath');
		$TenantName			= $this->post('TenantName');
		$TenantFloor		= $this->post('TenantFloor');
		$OrderNo			= $this->post('OrderNo');
		$Active				= $this->post('Active');
		$RBU				= $this->post('RBU');
		$RBT				= DateSekarang();

		if($this->post())
		{
			try{
				
				$query = $this->db->query("	INSERT INTO ms_tenant( ImagePath, TenantName, TenantFloor, OrderNo, Active, RBU, RBT)
											SELECT 
												'{$ImagePath}' as ImagePath
												,'{$TenantName}' as TenantName
												,'{$TenantFloor}' as TenantFloor
												,'{$OrderNo}' as OrderNo
												,'{$Active}' as Active
												,'{$RBU}' as RBU
												,'{$RBT}' as RBT
										");			


				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	
				$sql         = $this->db->last_query();
	            $page         = base_url('api/v1/Tenant/add');
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
				 								FROM ms_tenant
												WHERE DID = '{$DID}'
											");

					$sql         = $this->db->last_query();
	                $page         = base_url('api/v1/Tenant/delete');
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
		/*$ImagePath			= $this->put('ImagePath');
		$TenantName			= $this->put('TenantName');
		$TenantFloor		= $this->put('TenantFloor');
		$OrderNo			= $this->put('OrderNo');
		$Active				= $this->put('Active');
		$RBU				= $this->put('RBU');
		$RBT				= DateSekarang();*/

		if($this->put())
		{
			try
			{
				$query = $this->db->query("	SELECT 
												ImagePath
												,TenantName
												,TenantFloor
												,OrderNo
												,Active
												, RBU
												,RBT
											FROM ms_tenant
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

					$ImagePath				= $this->put('ImagePath')?$this->put('ImagePath'):$all_data->ImagePath;
					$TenantName				= $this->put('TenantName')?$this->put('TenantName'):$all_data->TenantName;
					$TenantFloor			= $this->put('TenantFloor')?$this->put('TenantFloor'):$all_data->TenantFloor;
					$OrderNo				= $this->put('OrderNo')?$this->put('OrderNo'):$all_data->OrderNo;
					$Active					= $this->put('Active')?$this->put('Active'):$all_data->Active;
					$RBU					= $this->put('RBU')?$this->put('RBU'):$all_data->RBU;
					$RBT					= DateSekarang();	
				
				$query = $this->db->query("	UPDATE  ms_tenant
											SET
												ImagePath = '{$ImagePath}'
												, TenantName = '{$TenantName}'
												, TenantFloor = '{$TenantFloor}'
												, OrderNo = '{$OrderNo}' 
												, Active = '{$Active}'
												, RBU = '{$RBU}'
												,RBT = '{$RBT}'
											WHERE
												DID = '{$DID}'
										");			


				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	
				$sql         = $this->db->last_query();
	            $page         = base_url('api/v1/Tenant/update');
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