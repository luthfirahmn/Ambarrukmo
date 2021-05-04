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

class AppInfo extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}


	public function get_post()
	{
		$Param = $this->post('Param');

		if(empty($this->post('Param')))
		{
            $response = array(
                'message' => 'Invalid params',
                'error' => true,
            );
            $this->response($response, 400);
            
        }
        else
        {
			try
			{
				if ($Param == 'AboutMember') 
				{
					$query = $this->db->query(" SELECT AboutMember
											FROM app_info
										");
				}
				else if ($Param == 'FAQ') 
				{
					$query = $this->db->query(" SELECT FAQ
											FROM app_info
										");
				}
				else if ($Param == 'PrivacyPolicy') 
				{
					$query = $this->db->query(" SELECT PrivacyPolicy
											FROM app_info
										");
				}
				else if ($Param == 'TermOfUse') 
				{
					$query = $this->db->query(" SELECT TermOfUse
											FROM app_info
										");
				}
				else if ($Param == 'OurLocation') 
				{
					$query = $this->db->query(" SELECT OurLocation
											FROM app_info
										");
				}
				else
				{
					$response = array(
		              'message' => 'Invalid params',
		              'error' => true,
			        );
			        $this->response($response, 400);
				}
				
				if($query===FALSE)
					throw new Exception();

					$result = $query->row();

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
	}

	

	/*UPDATE*/
	/*public function update_post()
	{
		$AppInfo = $this->post('AppInfo');
		$Content = $this->post('Content');


		if ($AppInfo == NULL  && $Content == NULL)
		{
			$response = array(
	          'message' => 'Invalid params',
	          'error' => true,
	        );
	        $this->response($response, 400);
		}
		else if ($AppInfo == 'AboutMember') 
		{
			$data = array('AboutMember' => $Content );
		}
		else if ($AppInfo == 'FAQ')
		{
			$data = array('FAQ' => $Content );
		}
		else if ($AppInfo == 'PrivacyPolicy')
		{
			$data = array('PrivacyPolicy' => $Content );
		}
		else if ($AppInfo == 'OurLocation')
		{
			$data = array('OurLocation' => $Content );
		}
		else
		{
			$err = array(
						'massage' => 'error',
						'error' => 'true'
					);
			$this->response($err, 400);

		}
        $update = $this->db->update('app_info',$data);

	}*/

}