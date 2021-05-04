<?php defined('BASEPATH') or exit('No direct script access allowed');

class Article extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('logged_in')) {
      redirect('backend/login');
    }
  }

  public function index()
  {
    try {
      /* S GET DATA */
      $query = $this->db->query(" SELECT * 
                                      FROM article
                                      WHERE deleted_at IS NULL
                                    ");

      if ($query === false)
        throw new Exception();

      // if($paging == true){


      // }

      $all_data = $query->result();
      /* E GET DATA */


      /* S DATA FOR VIEW */
      $data_content                 = array();
      $data_content['title']        = 'Articel';
      $data_content['breadcrumb']   = 'Dashboard';
      $data_content['breadcrumb1']  = 'Articel';
      $data_content['data_tabel']   = 'DataTable article';
      $data_content['all_data']     = $all_data;
      /* E DATA FOR VIEW */
      //pre($all_data);

      $config["content_file"] = "article/index";
      $config["content_data"] = $data_content;

      $this->template->initialize($config);
      $this->template->render();
    } catch (Exception $e) {
      return $e;
    }
  }
}
