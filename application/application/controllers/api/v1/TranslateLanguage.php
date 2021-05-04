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

class TranslateLanguage extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('bcrypt');
    }


    public function getList_post()
    {
        $data = $this->post();

        if(!isset($data['RBT']))
        {
            $response = array(
                'message' => 'RBT must not empty!',
                'error' => true,
            );
            $this->response($response, 200);
        }
        else
        {

            $sql="  SELECT * 
                    FROM ms_trans_lang 
                    WHERE RBT > '".$data['RBT']."'
                    ORDER BY RBT ASC ";
            $rs = $this->db->query($sql)->result();

            if($rs){
                $response = array(
                    'message' => 'success..',
                    'error' => false,
                    'data' => $rs
                );
                $this->response($response, 200);
            
            }


        }

    }

    public function add_post()
    {
        date_default_timezone_set('Asia/Jakarta');
   
        $now 				    = date("Y-m-d H:i:s");
        $newtimestamp 			= strtotime($now . '+ 15 minutes');
        

        $check = 0;
        $sql="  SELECT COUNT(*) 
                FROM ms_trans_lang 
                WHERE EN_LANG = '".$this->post('EN_LANG')."'
             ";
        $check = (int)$this->db->query($sql)->row();

        if($check <= 0){

            $data["EN_LANG"]    = $this->post("EN_LANG");
			$data["ID_LANG"]    = $this->post("ID_LANG");
			$data["RBT"]        = '2010-01-01 00:00:00';
			
			//$this->db->save_queries = TRUE;
			$this->db->insert("ms_trans_lang", $data);

            $page 		= base_url('api/v1/TranslateLanguage/add');
            tr_log($sql, $page, $this->post("MemberID"));

            $response = array(
                'message' => 'Data successfully added',
                'error' => true,
            );
            $this->response($response, 200);

        }else{
            $response = array(
                'message' => 'Data already exists!',
                'error' => true,
            );
            $this->response($response, 200);
        }

    }

}
