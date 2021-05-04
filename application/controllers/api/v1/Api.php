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

class Api extends REST_Controller
{
	function __construct()
	{
		parent::__construct();

		//$this->load->model('member_model', 'member', TRUE);
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

	public function add_post(){
		$nick_name 		  = $this->input->post('username');
		$email 			  = $this->input->post('email');
		$password 		  = $this->input->post('password');
		$confirm_password = $this->input->post('confirm_password');

		if(empty($nick_name) || empty($email) || empty($password) || empty($confirm_password) || $password <> $confirm_password){
			$response = array(
				'massage' => 'bad request..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}else{
			try{
				
				$query = $this->db->query("	INSERT INTO member( member_password , nick_name, email)
											SELECT 
												'{$password}' as member_password
												,'{$nick_name}' as nick_name
												,'{$email}' as email
										");						

				if($query === FALSE)
					throw new Exception();
					
				$result = $this->db->affected_rows();	

				if($result){
					$data = array(
						'massage' => 'success',
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
		}
	}

function city_get()
	{
		// echo "##= " . $this->get('id');
		if (!$this->get('id')) {
			$users = array(
				array('id' => 1, 'country_id' => '1', 'name' => 'Jakarta', 'slug' => 'jakarta', 'about' => 'My Home', 'url'=> 'https://lp-cms-production.imgix.net/image_browser/Jakarta_city_S.jpg', 'status' => '1', 'is_homepage' => '1', 'deleted_at' => 'null', 'created_at' => '2020-12-10T21:00:14.000000Z', 'updated_at' => '2020-12-10T21:00:14.000000Z'),
				array('id' => 2, 'country_id' => '2', 'name' => 'Bali', 'slug' => 'bali', 'about' => 'My Trip', 'url'=> 'https://media.timeout.com/images/105240189/image.jpg', 'status' => '1', 'is_homepage' => '1', 'deleted_at' => 'null', 'created_at' => '2020-12-10T21:00:14.000000Z', 'updated_at' => '2020-12-10T21:00:14.000000Z'),
				array('id' => 3, 'country_id' => '3', 'name' => 'Padang', 'slug' => 'padang', 'about' => 'My Home 2', 'url'=> 'https://asset.kompas.com/crops/ZG4UWTLI1EhaBaRqq1zcWxNGhlA=/0x0:1000x667/750x500/data/photo/2020/08/28/5f48688bddfb2.jpg', 'status' => '1', 'is_homepage' => '1', 'deleted_at' => 'null', 'created_at' => '2020-12-10T21:00:14.000000Z', 'updated_at' => '2020-12-10T21:00:14.000000Z'),
				array('id' => 4, 'country_id' => '4', 'name' => 'Semarang', 'slug' => 'padang', 'about' => 'My Home 2', 'url'=> 'https://asset.kompas.com/crops/ZG4UWTLI1EhaBaRqq1zcWxNGhlA=/0x0:1000x667/750x500/data/photo/2020/08/28/5f48688bddfb2.jpg', 'status' => '1', 'is_homepage' => '1', 'deleted_at' => 'null', 'created_at' => '2020-12-10T21:00:14.000000Z', 'updated_at' => '2020-12-10T21:00:14.000000Z')
			);
			$response = array('code' => 200, 'status' => true, 'message' => 'success');
			$output = array(
				"response" => $response,
				"data" => $users,
			);
			echo json_encode($output, true);
            exit();
		}

		// $user = $this->some_model->getSomething( $this->get('id') );
		// $users = array(
		// 	0 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
		// 	1 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
		// 	2 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
		// );

		$users = array(
			array('id' => 1, 'name' => 'Some Guyw', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			array('id' => 2, 'name' => 'Some Guye', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			array('id' => 3, 'name' => 'Some Guyr', 'email' => 'example1@example.com', 'fact' => 'Loves swimming')
		);
		$output = array(
			"status" => 200,
			"error" => false,
			"data" => $users,
		);
		// var_dump(@$output['data'][$this->get('id')]);
		$user = @$output['data']['id'][$this->get('id')];

		if ($user) {
			$this->response($user, 200); // 200 being the HTTP response code
		} else {
			$this->response(array('error' => 'User could not be found'), 404);
		}
	}
	public function get_get(){
		
		$id = $this->get('id');
		
		if($id == null){
			try{
				
				$query = $this->db->query("	SELECT * 
											FROM member
										");
				die("ok");

				if($query === FALSE)
					throw new Exception();

				$result = $query->result();

				if($result){

					$data = array(
						'massage' => 'success',
						'error' => 'flase',
						'data' => $result
					);
	
					$this->response($data, 200);

				}else{

					$data = array(

						'massage' => 'success',
						'error' => 'flase',
						'data' => 'notfound'
					);
	
					$this->response($data, 200);
				}

				
				
			}catch(Exception $e){
				print_r($this->db->_error_number());die;
			}
		}else{
			try{
				$query = $this->db->query(" SELECT * 
											FROM member	
											WHERE member_id = '{$id}'
										");
				
				if($query===FALSE)
					throw new Exception();

					$result = $query->result();

					if($result){

						$data = array(
							'massage' => 'success',
							'error' => 'flase',
							'data' => $result
						);
		
						$this->response($data, 200);
	
					}else{

						$data = array(
	
							'massage' => 'success',
							'error' => 'flase',
							'data' => 'notfound'
						);
		
						$this->response($data, 200);
					}

			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}
		}

	}

	public function update_put(){

		$id = $this->get('id');

		if($id == null){
			$response = array(
				'massage' => 'bad request..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
			try{
				//get data member
				$query = $this->db->query("	SELECT * 
											FROM member 
											WHERE member_id = '{$id}'"
										 );
				
				if($query===FALSE)
					throw new Exception();

				$member = $query->row();
				
				if($member){

					$member_password	= $this->input->post('member_password') ? $this->input->post('member_password'):$member->member_password ;
					$photo_profile	    = $this->input->post('photo_profile') ? $this->input->post('photo_profile'):$member->photo_profile ;
					$photo_bg			= $this->input->post('photo_bg') ? $this->input->post('photo_bg'):$member->photo_bg ;
					$nick_name			= $this->input->post('nick_name') ? $this->input->post('nick_name'):$member->nick_name ;
					$first_name			= $this->input->post('first_name') ? $this->input->post('first_name'):$member->first_name ;
					$last_name			= $this->input->post('last_name') ? $this->input->post('last_name'):$member->last_name ;
					$age	        	= $this->input->post('age') ? $this->input->post('age'):$member->age ;
					$gender				= $this->input->post('gender') ? $this->input->post('gender'):$member->gender ;
					$motto	    		= $this->input->post('motto') ? $this->input->post('motto'):$member->motto ;
					$country_phone_id	= $this->input->post('country_phone_id') ? $this->input->post('country_phone_id'):$member->country_phone_id ;
					$phone_number		= $this->input->post('phone_number') ? $this->input->post('phone_number'):$member->phone_number ;
					$member_follower	= $this->input->post('member_follower') ? $this->input->post('member_follower'):$member->member_follower ;
					$active			    = $this->input->post('active') ? $this->input->post('active'):$member->active ;


					// process update
				 	$query = $this->db->query(" UPDATE member 
												SET 
													member_password ='{$member_password}'
													,photo_profile ='{$photo_profile}'
													,photo_bg ='{$photo_bg}'
													,nick_name ='{$nick_name}'
													,first_name ='{$first_name}'
													,last_name ='{$last_name}'
													,age ='{$age}'
													,gender ='{$gender}'
													,motto ='{$motto}'
													,country_phone_id ='{$country_phone_id}'
													,phone_number ='{$phone_number}'
													,member_follower ='{$member_follower}'
													,active ='{$active}'
												WHERE member_id = '{$id}'
											");

					if($query===FALSE)
						throw new Exception();

						$response = $this->db->affected_rows();
			
						if($respone){
							$data = array(
		
								'massage' => 'data updated',
								'error' => 'flase',
							);
			
							$this->response($data, 201);

						}else{
							$data = array(
		
								'massage' => 'data not updated',
								'error' => 'true',
							);
			
							$this->response($data, 400);

						}
	
				}else{
					$data = array(
	
						'massage' => 'data member notfound',
						'error' => 'flase',
					);
	
					$this->response($data, 404);
				}
		
		
			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}

	}
	
	public function delete_delete(){

		$id = $this->get('id');

		if($id == null){
			$response = array(
				'massage' => 'bad request..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}else{
			try{
				$query = $this->db->query("	DELETE FROM member 
											WHERE member_id = '{$id}'
										 ");
				
				if($query===FALSE)
					throw new Exception();

				$response = $this->db->affected_rows();
		
				if($respone){
					$data = array(

						'massage' => 'data deleted',
						'error' => 'flase',
					);
	
					$this->response($data, 201);

				}else{
					$data = array(

						'massage' => 'data not deleted',
						'error' => 'true',
					);
	
					$this->response($data, 400);

				}
		
		
			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}
		}
	}
}
