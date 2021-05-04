<?php defined('BASEPATH') or exit('No direct script access allowed');

class Role extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('logged_in')) {
      redirect('backend/login');
    }
    $this->user = $this->session->userdata('user');
    $this->load->library('bcrypt');
  }

  public function index()
  {
    try {
      /* S GET DATA */
      $query = $this->db->query(" SELECT * 
                                        FROM role
                                        WHERE NULLIF (deleted_by , '') IS NULL  
										                    AND NULLIF (deleted_at , '') IS NULL
                                    ");

      if ($query === false)
        throw new Exception();


      $all_data = $query->result();

      $data_content                 = array();
      $data_content['title']        = 'Role';
      $data_content['breadcrumb']   = 'Dashboard';
      $data_content['breadcrumb1']  = 'Role';
      $data_content['data_tabel']   = 'DataTable Role';
      $data_content['success_msg']  = $this->session->flashdata("success_msg");
      $data_content['error_msg']    = $this->session->flashdata("error_msg");
      $data_content['info_msg']     = $this->session->flashdata("info_msg");
      $data_content['all_data']     = $all_data;
      /* E DATA FOR VIEW */
      //pre($all_data);

      $config["content_file"] = "role/index";
      $config["content_data"] = $data_content;

      $this->template->initialize($config);
      $this->template->render();
    } catch (Exception $e) {
      return $e;
    }
  }


  public function set_access($id = null)
  {
    try {

      $query = $this->db->query("  SELECT GROUP_CONCAT(a.privileges,'-',a.id,'-',IF(ra.access_id IS NULL,0,1)) as privileges 
                                    , (SELECT name FROM module WHERE module.id = a.module AND NULLIF (deleted_by , '') IS NULL  
										                    AND NULLIF (deleted_at , '') IS NULL ) as module
                                    ,ra.role_id 
                                    FROM access as a
                                    LEFT JOIN role_access as ra ON ra.access_id = a.id AND role_id = $id 
                                    AND NULLIF (a.deleted_by , '') IS NULL  
										                AND NULLIF (a.deleted_at , '') IS NULL 
                                    WHERE 1 GROUP BY module");


      if ($query === FALSE)
        throw new Exception();
      // pre($this->db->last_query($query));

      $result = $query->result();
//     pre($result);

      if ($result) {
        $query2 = $this->db->query(" SELECT id , name FROM role WHERE id = {$id}");

        if ($query === FALSE)
          throw new Exception();

        $result2 = $query2->row();
      }


      $data_role = array();


      foreach ($result as $key => $val) {
        if($val->module != ""){
          $data_role[$key]['module'] = $val->module;
          $data1 = explode(",", $result[$key]->privileges);
          foreach ($data1 as $ky => $value) {
            $data2 = explode("-", $data1[$ky]);
            $data_role[$key]['privileges'][$ky]['action']   =  $data2[0];
            $data_role[$key]['privileges'][$ky]['id']       =  $data2[1];
            $data_role[$key]['privileges'][$ky]['status']   =  $data2[2];
          }
        }else{
          continue;
        }
      }

      //pre($data_role);

      $data_content                         = array();
      $data_content['title']                = 'General Form';
      $data_content['breadcrumb']           = 'List Role';
      $data_content['breadcrumb1']          = 'Role Form';
      $data_content['data_tabel']           = 'Role Form';
      $data_content['data_role']            = $result2;
      $data_content['data_role_access']     = $data_role;
      /* E DATA FOR VIEW */
      //pre($data_content);

      $config["content_file"] = "role/set_access";
      $config["content_data"] = $data_content;

      $this->template->initialize($config);
      $this->template->render();
    } catch (Exception $e) {
      return $e;
    }
  }

  public function form($id = null)
  {

    if ($id == "add") {
      if (!is_allowed($this->session->userdata('user'), 'role', 'add')) :
        $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
        redirect('backend/role');
      endif;
    }

    if ($id != null && $id != "add") {

      $query = $this->db->query(" SELECT * FROM role WHERE id = {$id}");

      if ($query === false)
        throw new Exception();

      $result = $query->row();

      $data_content                 = array();
      $data_content['title']        = 'General Form';
      $data_content['breadcrumb']   = 'List Role';
      $data_content['breadcrumb1']  = 'Role Form';
      $data_content['data_tabel']   = 'Role Form';
      $data_content['status_edit']  = 1;
      $data_content['all_data']     = $result;
      /* E DATA FOR VIEW */
      //pre($all_data);

      $config["content_file"] = "role/form";
      $config["content_data"] = $data_content;

      $this->template->initialize($config);
      $this->template->render();
    }

    $data_content                 = array();
    $data_content['title']        = 'General Form';
    $data_content['breadcrumb']   = 'List Role';
    $data_content['breadcrumb1']  = 'Role Form';
    $data_content['data_tabel']   = 'Role Form';
    $data_content['status_edit']  = 0;
    //$data_content['all_data']     = $all_data;
    /* E DATA FOR VIEW */
    //pre($all_data);

    $config["content_file"] = "role/form";
    $config["content_data"] = $data_content;

    $this->template->initialize($config);
    $this->template->render();
  }

  public function add()
  {
    try {
      if ($this->input->post()) {

        $name = $this->input->post('name');
        $description = $this->input->post('description');

        $query = $this->db->query(" INSERT INTO role(name, description, status, created_by, created_at)
                                    SELECT
                                    '{$name}' as name
                                    ,'{$description}' as description
                                    ,7 as status
                                    ,{$this->user} as created_by
                                    ,now() as created_at
                                     ");



        if ($query === false)
          throw new Exception();

        $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

        if ($result) {
          $this->session->set_flashdata("success_msg", "Data insert success...");
          redirect('backend/role');
        } else {
          $this->session->set_flashdata("error_msg", "faild to insert...");
          redirect('backend/role/form');
        }
      } else {
        $this->session->set_flashdata("error_msg", "error method insert...");
        redirect('backend/role/form');
      }
    } catch (Exception $e) {
      return $e;
    }
  }

  public function edit($id = null)
  {
    try {
      if ($this->input->post()) {

        $name = $this->input->post('name');
        $description = $this->input->post('description');

        $query = $this->db->query(" UPDATE role 
                                        SET 
                                          name ='{$name}'
                                          ,description = '{$description}'
                                          ,updated_by = '{$this->user}'
                                          ,updated_at = now()
                                          WHERE id = '{$id}'
                                    
                                    ");

        if ($query === false)
          throw new Exception();

        $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

        if ($result) {

          $this->session->set_flashdata("success_msg", "Data updated...");
          redirect('backend/role');
        } else {
          $this->session->set_flashdata("error_msg", "Data not updated...");
          redirect('backend/role/form/' . $id);
        }
      } else {
        $this->session->set_flashdata("error_msg", "error method edit...");
        redirect('backend/role');
      }
    } catch (Exception $e) {
      return $e;
    }
  }

  public function add_access_role()
  {

    $id = $this->input->post('id');
    $access = $this->input->post('access');
  

    if (!$id) {
      $this->session->set_flashdata("error_msg", "ID not exist...");
      redirect('backend/role');
    }

    try {

      $query = $this->db->query(" DELETE FROM role_access WHERE role_id = {$id}");


      if ($query === FALSE)
        throw new Exception();

      if($access != ''){
      foreach ($access as $row => $key) {

        $data_insert = array();
        $data_insert['role_id']   = $id;
        $data_insert['access_id']  = $key;

        $this->db->insert('role_access', $data_insert);
      }

      $insert = $this->db->affected_rows() > 0 ? true : false;

      if ($insert) {
        $this->session->set_flashdata("success_msg", "Role Access updated...");
        redirect('backend/role');
      } else {
        $this->session->set_flashdata("error_msg", "Role Access not updated...");
        redirect('backend/role');
      }
    }else{
      $this->session->set_flashdata("success_msg", "Role Access updated...");
      redirect('backend/role');
    }
    } catch (Exception $e) {
      return $e;
    }
  }


  public function delete($id = null)
  {
    try {
      if ($id != null) {
        $user = $this->user;

        $query = $this->db->query("	UPDATE role 
                                    SET 
                                      deleted_by = $user
                                      ,deleted_at = now()
                                    WHERE id = '{$id}'
                                    ");

        if ($query === false)
          throw new Exception();

        $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

        if ($result) {
          $this->session->set_flashdata("success_msg", "Data deleted...");
          redirect('backend/role');
        } else {
          $this->session->set_flashdata("error_msg", "Data not deleted...");
          redirect('backend/role');
        }
      }

      $this->session->set_flashdata("error_msg", "ID notfound...");
      redirect('backend/role');
    } catch (Exception $e) {
      return $e;
    }
  }
}
