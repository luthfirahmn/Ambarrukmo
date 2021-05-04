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

class Register extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('bcrypt');
    }


    public function cekOTP_post()
    {
        $data = $this->post();
        
        $this->db->select("OTPExpired");
        $this->db->from("ms_member");
        $this->db->where("MemberID",$data['MemberID']);
        $this->db->where("OTP", $data["OTP"]);
        $this->db->where("OTPExpired > NOW()");

        $query = $this->db->get();

        $result = $query->row();

        if($result){

            $response = array(
                'message' => 'success..',
                'error' => false,
                'data' => array(
                    "MemberID" => $data['MemberID']
                )
            );
            $this->response($response, 200);
           
        }else{

            $response = array(
                'message' => 'OTP already Expired..',
                'error' => true,
            );
            $this->response($response, 200);

        }

    }

    public function createOTP_post()
    {
        date_default_timezone_set('Asia/Jakarta');
   
        $data["OTP"]           	= rand(1000, 9999);
        $Datenow 				= date("Y-m-d H:i:s");
        $newtimestamp 			= strtotime($Datenow . '+ 15 minutes');
        $data["OTPExpired"]		= date('Y-m-d H:i:s', $newtimestamp);

        $this->db->save_queries = TRUE;
        $this->db->where("MemberID",$this->post("MemberID"));
        $this->db->update("ms_member", $data);

        if($this->db->affected_rows() > 0 ? TRUE : FALSE){

            $sql 		= $this->db->last_query();
            $page 		= base_url('api/v1/register/createOTP');
            tr_log($sql, $page, $this->post("MemberID"));

            sendOTP("OTP",$this->post("Email"), $data["OTP"]);

            $response = array(
                'message' => 'data Updated',
                'error' => true,
            );
            $this->response($response, 200);

        }else{
            $response = array(
                'message' => 'data not Updated',
                'error' => true,
            );
            $this->response($response, 200);
        }

    }

}
