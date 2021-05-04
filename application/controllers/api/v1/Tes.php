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

class Tes extends REST_Controller
{

	function __construct()
	{
		parent::__construct();
	}


	public function add_post()
	{	
		$IDPhoto = $_POST['IDPhoto'];
		$MemberID = $this->post('MemberID');
		$folderPath = IMAGE_BLOCK_APPS_ROOT_MEMBER;
	    $image_parts = explode(";base64,", $IDPhoto);
	    $image_type_aux = explode("image/", $image_parts[0]);
	    $image_type = $image_type_aux[1];
	    $image_base64 = base64_decode($image_parts[1]);
	    $file = $folderPath . $MemberID . '_' . uniqid() . '.' . $image_type;
		$NewImageWidth 		= 800; //New Width of Image
		$NewImageHeight 	= 800; // New Height of Image
		$Quality 		= 50; //Image Quality
		$checkValidImage = @getimagesize($file);
	    $file_name = explode("/", $file);
	    file_put_contents($file, $image_base64);
	    $UrlFile = $file_name[3];

	    if(file_exists($file)){
		resizeImage($file,$file, $NewImageWidth,$NewImageHeight,$Quality);
    	}

		$insert["IDPhoto"]       = $UrlFile;

		$this->db->insert("tes", $insert);
	}



	
}

