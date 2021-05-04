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

class Articel extends REST_Controller
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
 	public function foo_get(){
		 pre('this article controller');
	 } 


	public function add_post(){

		$slug 				= $this->post('slug');
		$city_id 			= $this->post('city_id');
		$post_date 			= $this->post('post_date');
		$title 				= $this->post('title');
		$content 			= $this->post('content');
		$home_page_visible	= $this->post('home_page_visible');
		$active 			= $this->post('active');

		if($this->post()){
			try{
				
				$query = $this->db->query("	INSERT INTO article( slug, city_id, post_date, title, content, home_page_visible, active, created_by, created_at)
											SELECT 
												'{$slug}' as slug
												,'{$city_id}' as city_id
												,'{$post_date}' as post_date
												,'{$title}' as title
												,'{$content}' as content
												,'{$home_page_visible}' as home_page_visible
												,'{$active}' as active
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

	public function get_get(){
		
		$id = $this->get('article_id');
		
		if($id == null){
			$response = array(
				'massage' => 'invalid params..',
				'error' => 'true'
			);
			$this->response($response, 400); 
		}else{
			try{
				$query = $this->db->query(" SELECT * 
											FROM article	
											WHERE NULLIF (deleted_by , '') IS NULL  
											AND NULLIF (deleted_at , '') IS NULL 
											AND article_id = '{$id}'
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
		}

	}

	public function update_put(){

		$id = $this->put('article_id');

		if($id == null){
			$response = array(
				'massage' => 'invalid params..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}
			try{
				//get data article
				$query = $this->db->query("	SELECT * 
											FROM article 
											WHERE NULLIF (deleted_by , '') IS NULL  
											AND NULLIF (deleted_at , '') IS NULL 
											AND article_id = '{$id}'
											");
				
				if($query===FALSE)
					throw new Exception();

				$article = $query->row();
				
				//print_r($article);die;
				if(!empty($article)){

					//$obj = new stdClass();

					$slug				= $this->put('slug')?$this->put('slug'):$article->slug;
					$city_id			= $this->put('city_id')?$this->put('city_id'):$article->city_id;
					$post_date			= $this->put('post_date')?$this->put('post_date'):$article->post_date;
					$title				= $this->put('title')?$this->put('title'):$article->title;
					$content			= $this->put('content')?$this->put('content'):$article->content;
					$home_page_visible	= $this->put('home_page_visible')?$this->put('home_page_visible'):$article->home_page_visible;
					$active				= $this->put('active')?$this->put('active'):$article->active;
					
					// process update
				 	$query = $this->db->query(" UPDATE article 
												SET 
													slug ='{$slug}'
													,city_id ='{$city_id}'
													,post_date ='{$post_date}'
													,title ='{$title}'
													,content ='{$content}'
													,home_page_visible ='{$home_page_visible}'
													,active ='{$active}'
													,updated_by= 1
													,updated_at= now()
												WHERE article_id = '{$id}'
											");

					if($query === FALSE)
						throw new Exception();
						
						$result = $this->db->affected_rows();
			
						if($result){
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
	
				}else{
					$data = array(
						'massage' => 'data notfound',
						'error' => 'ture',
					);
					$this->response($data, 404);
				}
		
		
			}catch(Exception $e){

				print_r($this->db->_error_number());die;
			}

	}
	
	public function delete_delete(){

		$id = $this->delete('article_id');
		
		if($id == null){
			$response = array(
				'massage' => 'bad request..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}else{
			try{
				$query = $this->db->query("	UPDATE article 
											SET 
												deleted_by = 1
												,deleted_at = now()
											WHERE article_id = '{$id}'
										 ");
				
				if($query===FALSE)
					throw new Exception();

				$result = $this->db->affected_rows();
		
				if($result){
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
