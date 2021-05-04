<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function  __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('backend/login');
		}
	}

	public function index()
	{


		redirect('backend/dashboard');
		// pre("oksasdadasdadadasdssss");
		// /* s: set data variable */
		// $data = array();
		// $data['success_msg'] = $this -> session -> flashdata("success_msg");
		// $data['error_msg'] = $this -> session -> flashdata("error_msg");
		// $data['info_msg'] = $this -> session -> flashdata("info_msg");
		// /* e: set data variable */

		// /* s: load data to view */
		// $view_param['title_set']	= '1Deca CMS - Cook and Eat - Dashboard';
		// $view_param['head']			= '';
		// $view_param['content']		= $this->load->view('welcome/v1/dashboard',$data,TRUE);
		// $view_param['foot']			= $this->load->view('welcome/v1/foot/dashboard','',TRUE);

		// view_v1($view_param);
		/* e: load data to view */
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */