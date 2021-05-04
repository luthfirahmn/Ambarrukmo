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

class Token extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');

		date_default_timezone_set('Asia/Jakarta');
	}


	 public function getToken_post()
    {

        header("Access-Control-Allow-Origin: *");

        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);
        
        # Form Validation
        $this->form_validation->set_rules('Email', 'Email', 'trim|required');
        $this->form_validation->set_rules('Password', 'Password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'message' => validation_errors()
                'error' => $this->form_validation->error_array(),
            );

            $this->response($message, 404);
        }
        else
        {

        	$Email = $this->post('Email');
        	$Password = $this->post('Password');
            // Load Login Function
           	$query = $this->db->query(" SELECT *
                                        FROM ms_login 
                                        WHERE Email = '{$Email}'");
                
                if($query === false)
                    throw new Exception();
                
		              $datauser = $query->row();

		              $hash = $datauser->Password;
		              $hashp = $this->bcrypt->check_password($Password, $hash);

			  	   $this->db->where('Email', $Email);
				   $this->db->where('Password', $hashp);
			  	   $output = $this->db->get('ms_login')->row();
            if ($output)
            {
                // Load Authorization Token Library
                $this->load->library('Authorization_Token');

                // Generate Token
                $token_data['DID']      = $output->DID;
                $token_data['Email']    = $output->Email;
                $LastLogin              = date("Y-m-d H:i:s");
                $newtimestamp           = strtotime($LastLogin . '+ 60 minutes');
                $SessionExpiry          = date('Y-m-d H:i:s', $newtimestamp);

                $user_token = $this->authorization_token->generateToken($token_data);
                /*print_r($this->authorization_token->userData($user_token));exit;
*/              
                $return_data = [
                    'DID' 			=> $output->DID,
                    'Email' 		=> $output->Email,
                    'LastLogin' 	=> $LastLogin,
                    'SessionExpiry' => $SessionExpiry,
                    'Token' 		=> $user_token,
                ];

                 $update_token = [
                    'DID' => $output->DID,
                    'Email' => $output->Email,
                    'LastLogin' => $LastLogin,
                    'SessionExpiry' => $SessionExpiry,
                    'AppToken' => $user_token,
                ];

                $this->db->where('Email',$Email)
                		 ->update('ms_login',$update_token);

                // Login Success
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "User login successful"
                ];

                
                $this->response($message, 200);
            } 
            else
            {
                // Login Error
                $message = [
                    'status' => FALSE,
                    'message' => "Invalid Username or Password"
                ];
                $this->response($message, 404);
            }
        }
    }


    
}