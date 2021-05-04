<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*   Authorization_Token
* -------------------------------------------------------------------
* API Token Check and Generate
*
* @author: Jeevan Lal
* @version: 0.0.5
*/


class Token 
{

	private $CI;
    /**
     * Constructor
     */
    function __construct()
    {
        //Load your settting here
        $this->ci = &get_instance();

        $this->db = $this->ci->db;
    }

	public function ValidateToken($Token)
	{
		if($Token == null)
		{
			$response = array(
				'massage' => 'invalid Token..', 
				'error' => 'true'
			);
			print_r($response);exit;
		}

		$query = $this->db->query(" SELECT * 
						  			FROM ms_login 
						  			WHERE AppToken = '{$Token}'
		");
		$res   = $query->num_rows();

		if($res < 1)
		{
			$response = array(
				'massage' => 'invalid Token..', 
				'error' => 'true'
			);
			print_r($response);exit;
		}
		$datenow = date("Y-m-d H:i:s");
		$query = $this->db->query(" SELECT * 
						  			FROM ms_login 
						  			WHERE AppToken = '{$Token}'
						  			AND SessionExpiry > '{$datenow}'
		");
		$res   = $query->num_rows();
		if($res < 1)
		{
			$response = array(
				'massage' => 'invalid Token..', 
				'error' => 'true'
			);
			print_r($response);exit;
		}
	}
}