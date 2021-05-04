<?php defined('BASEPATH') or exit('No direct script access allowed');

class OurLocation extends CI_Controller
{
  	function __construct()
  	{
	    parent::__construct();
	    if (!$this->session->userdata('logged_in')) 
	    {
	      redirect('backend/login');
	    }
        $this->user = $this->session->userdata('user');
  	}

  	public function index()
  	{

		/* DATA MEMBER LEVEL */
	    $this->db->select("OurLocation");
	    $this->db->from("app_info");
	    $query = $this->db->get();

	    $result = $query->row();

			/** DATA SEND TO VIEW */  
	    $data_content                 = array();
	    $data_content['title']        = 'Term Of Use';
	    $data_content['breadcrumb']   = 'Dashboard';
	    $data_content['breadcrumb1']  = 'Term Of Use';
	    $data_content['data_tabel']   = 'DataTable Term Of Use';
	    $data_content['all_data']     = $result;

	    $config["content_file"] = "AppInfo/OurLocation";
	    $config["content_data"] = $data_content;

	    $this->template->initialize($config);
	    $this->template->render();

  	}


	public function update($did = null)
	{   

	    $this->form_validation->set_error_delimiters('', '<br>');
	    $this->form_validation->set_rules('OurLocation','Text Field', 'required');

	    if ($this->form_validation->run() == FALSE) 
	    {
	        $errors = validation_errors();
	        echo json_encode(['error'=>$errors]);
	    }
	    else
	    {

	        $data["OurLocation"]            = $this->input->post("OurLocation");


	        $this->db->update("app_info", $data);
	        $sql         = $this->db->last_query();
	        $page         = base_url('backend/AppInfo/OurLocation');
	        tr_log($sql, $page, $this->user);

	        echo json_encode(['success'=>'Berhasil']);

	            
	    }
	}
	
}