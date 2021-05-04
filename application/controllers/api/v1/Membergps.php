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

class Membergps extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');
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

	/* S CREATE MAMBER.. */
	
	public function add_post(){

		$member_id = $this->post('member_id');
		$gps_lat = $this->post('gps_lat');
		$gps_lang = $this->post('gps_lang');

		if( $member_id == '' || $gps_lat == '' || $gps_lang == '' ){
			
			$response = array(
				'message' => 'bad request..', 
				'error' => 'true'
			);
			$this->response($response, 400); 
		}

		$query = $this->db->query("INSERT INTO member_gps (member_id , gps_lat, gps_lang, rbt) 
									SELECT 
									'{$member_id} as member_id'
									,'{$gps_lat} as gps_lat'
									,'{$gps_lang} as gps_lang'
									,now() as rbt
		");

		if($query){
			$response = array(
				'message' => 'success insert...', 
				'error' => false
			);
			$this->response($response, 200); 
		}else{
			$response = array(
				'message' => 'failed to insert...', 
				'error' => true
			);
			$this->response($response, 500);
		}

	}

	public function get_get(){

		$id = '';
		$inputJSON = file_get_contents('php://input');
		$get_url = $this->get('member_id');

		if($inputJSON != ''){
			$id = json_decode($inputJSON);
			if(!array_key_exists("member_id",$id)){
				$response = array(
					'message' => 'invalid params...',
					'error' => true,
				);
				$this->response($response, 400); 
			}
			$member_id = array_map('intval', $id->member_id);
			$member_id 	= implode("','",$member_id);
		}
		
		if($get_url != ''){
			$id = $get_url;
			$member_id = array_map('intval',$id);
			$member_id 	= implode("','",$member_id);
		}

		if($id == ''){
			$query = $this->db->query(" SELECT *
										FROM member_gps 
										WHERE rbt IN (
											SELECT max(rbt) as rbt
											FROM member_gps  
											GROUP BY member_id)
										ORDER BY member_id ASC");
		}else{
			$query = $this->db->query(" SELECT *
									    FROM member_gps 
									    WHERE member_id IN ('{$member_id}') AND rbt IN (
											SELECT max(rbt) as rbt
									    	FROM member_gps 
									   	    WHERE member_id IN ('{$member_id}') 
											GROUP BY member_id)
										ORDER BY member_id ASC") ;

		}
		

		if($query === FALSE)
				throw new Exception();

		$result = $query->result();

		if($query){
			$response = array(
				'message' => 'success...',
				'error' => false,
				'data'  => $result
			);
			$this->response($response, 200); 
		}else{
			$response = array(
				'message' => 'failed ...', 
				'error' => true
			);
			$this->response($response, 500);
		}
	   
	}
	
}