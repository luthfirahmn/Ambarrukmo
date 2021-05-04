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

class City extends REST_Controller
{
	function __construct()
	{
		//parent::__construct();
		//$this->load->library('bcrypt');
		//$this->load->model('member_model', 'city', TRUE);
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

	public function foo_get(){
		die("this city controller......");
	}

	public function add_post(){

		$city_id        	= $this->post('city_id');
		$city_name      	= $this->post('city_name');
		$slug 				= $this->post('slug');
		$country_id			= $this->post('country_id');
		$short_description 	= $this->post('short_description');
		$about 				= $this->post('about');
		$operating_hours 	= $this->post('operating_hours');
		$address1 			= $this->post('address1');
		$address2 			= $this->post('address2');
		$phone1 			= $this->post('phone1');
		$phone2 			= $this->post('phone2');
		$wa_phone 			= $this->post('wa_phone');
		$gps_location 		= $this->post('gps_location');

		if($this->post()){
			try{
				$query = $this->db->query("	INSERT INTO city( city_id, city_name, slug, country_id, short_description, about, operating_hours, address1, address2, phone1, phone2, wa_phone, gps_location, created_by, created_at)
											SELECT 
												'{$city_id}' as city_id
												,'{$city_name}' as city_name
												,'{$slug}' as slug
												,'{$country_id}' as country_id
												,'{$short_description}' as short_description
												,'{$about}' as about
												,'{$operating_hours}' as operating_hours
												,'{$address1}' as address1
												,'{$address2}' as address2
												,'{$phone1}' as phone1
												,'{$phone2}' as phone2
												,'{$wa_phone}' as wa_phone
												,'{$gps_location}' as gps_location
												,1 as created_by
												,NOW() as created_at
										");						

				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	

				if($result){
					$data = array(
						'massage' => 'success created..',
						'error' => 'false'
					);
					$this->response($data, 201);	

				}else{
					
					$data = array(
						'massage' => 'invalid',
						'error' => 'true'
					);
					$this->response($data, 404);
				}
	
			} catch(Exception $e) {

				 print_r($this->db->_error_number());die;
			}
		}else{
			$data = array(
				'massage' => 'invalid',
				'error' => 'true'
			);
			$this->response($data, 403);
		}
	}

	public function get_get(){ // 
		
		$id = $this->get('city_id');
		
		if($id == null){
			try{
				$query = $this->db->query(" SELECT * 
											FROM city	
											WHERE NULLIF (deleted_by , '') IS NULL  
											AND NULLIF (deleted_at , '') IS NULL 
										");
				
				if($query===FALSE)
					throw new Exception();

					$result = $query->result();

						$data = array(
							'massage' => 'success',
							'error' => 'flase',
							'data' => $result
						);

					if($result){
						$this->response($data, 200);
					}else{
						$this->response($data, 404);
					}

			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}
		}else{
			try{
				$query = $this->db->query(" SELECT * 
											FROM city	
											WHERE NULLIF (deleted_by , '') IS NULL  
											AND NULLIF (deleted_at , '') IS NULL 
											AND city_id = '{$id}'
										");


									/* 		$query = $this->db->query(" SELECT 
												c.*
												,d.*
											FROM city as c
											LEFT JOIN destiantion as d ON c.city_id = d.city_id	
											WHERE NULLIF (deleted_by , '') IS NULL  
											AND NULLIF (deleted_at , '') IS NULL 
											AND member_id = '{$id}'
										");

										*/
				
				if($query===FALSE)
					throw new Exception();

					$result = $query->result();

						$data = array(
							'massage' => 'success',
							'error' => 'flase',
							'data' => $result
						);

					if($result){
						$this->response($data, 200);
					}else{
						$this->response($data, 404);
					}

			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}
		}

	}

	public function update_put(){

		$id = $this->put('city_id');

		if($id == null){
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
			try{
				//get data city
				$query = $this->db->query("	SELECT * 
											FROM city 
											WHERE NULLIF (deleted_by , '') IS NULL  
											AND NULLIF (deleted_at , '') IS NULL 
											AND city_id = '{$id}'
											");
				
				if($query===FALSE)
					throw new Exception();

				$city = $query->row();
				
				if(!empty($city)){

					 $city_id   		= $this->put('city_id')?$this->put('city_id'):$city->city_id;
					 $city_name   		= $this->put('city_name')?$this->put('city_name'):$city->city_name;				
					 $slug   			= $this->put('slug')?$this->put('slug'):$city->slug;
					 $country_id   		= $this->put('country_id')?$this->put('country_id'):$city->country_id;
					 $short_description = $this->put('short_description')?$this->put('short_description'):$city->short_description;
					 $about   			= $this->put('about')?$this->put('about'):$city->about;
					 $operating_hours   = $this->put('operating_hours')?$this->put('operating_hours'):$city->operating_hours;
					 $address1   		= $this->put('address1')?$this->put('address1'):$city->address1;
					 $address2   		= $this->put('address2')?$this->put('address2'):$city->address2;
					 $phone1   			= $this->put('phone1')?$this->put('phone1'):$city->phone1;
					 $phone2   			= $this->put('phone2')?$this->put('phone2'):$city->phone2;
					 $wa_phone   		= $this->put('wa_phone')?$this->put('wa_phone'):$city->wa_phone;
					 $gps_location   	= $this->put('gps_location')?$this->put('gps_location'):$city->gps_location;

					// process update
				 	$query = $this->db->query(" UPDATE city 
												SET 
													city_id='{$city_id}'
													,city_name='{$city_name}'
													,slug='{$slug}'
													,country_id='{$country_id}'
													,short_description='{$short_description}'
													,about='{$about}'
													,operating_hours='{$operating_hours}'
													,address1='{$address1}'
													,address2='{$address2}'
													,phone1='{$phone1}'
													,phone2='{$phone2}'
													,wa_phone='{$wa_phone}'
													,gps_location='{$gps_location}'
													,updated_by= 1
													,updated_at= now()
												WHERE city_id = '{$id}'
											");

					if($query === FALSE)
						throw new Exception();
						
						$result = $this->db->affected_rows();
			
						if($result){
							$data = array(
		
								'massage' => 'data Updated ...',
								'error' => false,
							);
			
							$this->response($data, 201);

						}else{
							$data = array(
		
								'massage' => 'data not Updated',
								'error' => true,
							);
			
							$this->response($data, 400);

						}
	
				}else{
					$data = array(
						'massage' => 'data notfound',
						'error' => 'ture',
					);
					$this->response($data, 404);
				}
		
		
			}catch(Exception $e){

				print_r($this->db->_error_number('body'));die;
			}

	}
	
	public function delete_delete(){

		$id = $this->delete('city_id');

		if($id == null){
			$response = array(
				'massage' => 'invalid id...', 
				'error' => true
			);
			$this->response($response, 400); 
		}else{
			try{
				$query = $this->db->query("	UPDATE city 
											SET 
												deleted_by = 1
												,deleted_at = now()
											WHERE city_id = '{$id}'
										 ");
				
				if($query===FALSE)
					throw new Exception();

				$result = $this->db->affected_rows();
		
				if($result){
					$data = array(

						'massage' => 'data deleted...',
						'error' => false,
					);
	
					$this->response($data, 201);

				}else{
					$data = array(

						'massage' => 'data not deleted...',
						'error' => true,
					);
	
					$this->response($data, 400);

				}
		
		
			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}
		}
	}
}
