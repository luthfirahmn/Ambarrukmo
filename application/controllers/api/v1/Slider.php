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

class Slider extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');
    }

    public function getslider_get(){

        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
        /* GET SLIDER */
        
        if(empty($this->get("RBT"))){

            $response = array(
                'message' => 'Invalid params',
                'error' => true,
            );
            $this->response($response, 200);
            
        }

        $rbt = $this->get("RBT");

        $this->db->select("*");
        $this->db->from("ms_slider");
        $this->db->where("Active = 1");
        $this->db->where("RBT > ", $rbt);
        $this->db->order_by("OrderNo","ASC");
        $result = $this->db->get()->result_array();
     
    
        if(count($result) != 0 ){
            foreach($result as $key => $data){
                $result[$key]["base64Iamge"] = base46Image($data['ImagePath']);
            }
        }
      
        $response = array( 
            'message'   => 'success',
            'error'     => false,
            'data'      => $result
        );
        $this->response($response, 200);

    }
    
}
