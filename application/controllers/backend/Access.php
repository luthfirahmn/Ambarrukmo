<?php defined('BASEPATH') or exit('No direct script access allowed');

class Access extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/login');
        }
        $this->user = $this->session->userdata('user');
    }

    public function index()
    {
        try {

            $query = $this->db->query(" SELECT access.*,
                                            (SELECT name FROM module where access.module = module.id) as name_module
                                        FROM access
                                        WHERE NULLIF (deleted_by , '') IS NULL  
										AND NULLIF (deleted_at , '') IS NULL
                                        ");

            if ($query === false)
                throw new Exception();

            $all_data = $query->result();

            //pre($all_data);
            /* S DATA FOR VIEW */
            $data_content                 = array();
            $data_content['title']        = 'Access';
            $data_content['breadcrumb']   = 'Dashboard';
            $data_content['breadcrumb1']  = 'Access';
            $data_content['data_tabel']   = 'DataTable access';
            $data_content['success_msg']  = $this->session->flashdata("success_msg");
            $data_content['error_msg']    = $this->session->flashdata("error_msg");
            $data_content['info_msg']     = $this->session->flashdata("info_msg");
            $data_content['all_data']     = $all_data;
            /* E DATA FOR VIEW */
            //pre($all_data);

            $config["content_file"] = "access/index";
            $config["content_data"] = $data_content;

            $this->template->initialize($config);
            $this->template->render();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function form($id = null)
    {
        try {

            if ($id == "add") {
                if (!is_allowed($this->session->userdata('user'), 'access', 'add')) :
                    $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
                    redirect('backend/access');
                endif;
            }
    
            if ($id != null && $id != "add") {


                $query = $this->db->query(" SELECT *
                                        FROM access
                                        WHERE id = {$id}");

                if ($query === false)
                    throw new Exception();

                $data_access = $query->row();


                $query = $this->db->query(" SELECT id, name 
                                        FROM module
                                        WHERE deleted_at IS NULL");

                if ($query === false)
                    throw new Exception();

                $data_module = $query->result();

                $data_content                 = array();
                $data_content['title']        = 'General Form';
                $data_content['status_edit']  = 1;
                $data_content['breadcrumb']   = 'List Access';
                $data_content['breadcrumb1']  = 'Access Form Update';
                $data_content['data_tabel']   = 'Access Form Update';
                $data_content['data_module']  = $data_module;
                $data_content['all_data']     = $data_access;
                /* E DATA FOR VIEW */
                //pre($all_data);

                $config["content_file"] = "access/form";
                $config["content_data"] = $data_content;

                $this->template->initialize($config);
                $this->template->render();
            }


            $query = $this->db->query(" SELECT id, name 
                                        FROM module
                                        WHERE deleted_at IS NULL");

            if ($query === false)
                throw new Exception();

            $data_module = $query->result();

            $data_content                 = array();
            $data_content['title']        = 'General Form';
            $data_content['status_edit']  = 0;
            $data_content['breadcrumb']   = 'List Access';
            $data_content['breadcrumb1']  = 'Access Form';
            $data_content['data_tabel']   = 'Access Form';
            $data_content['data_module']  = $data_module;
            //$data_content['all_data']     = $all_data;
            /* E DATA FOR VIEW */
            //pre($all_data);

            $config["content_file"] = "access/form";
            $config["content_data"] = $data_content;

            $this->template->initialize($config);
            $this->template->render();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function add()
    {
        try {
            if ($this->input->post()) {
                //pre($_POST);
                if ($this->input->post('save') == "yes") { //save

                    $user       = $this->session->userdata('user');
                    $privileges = $this->input->post('privileges');
                    $module     = $this->input->post('module');

                    if ($module == '') {
                        redirect('access/form');
                    }

                    $queryC = $this->db->query(" SELECT * FROM access WHERE module = {$module} AND privileges = '{$privileges}'");


                    if ($queryC === false)
                        throw new Exception();

                    $resultC = $queryC->num_rows();

                    if (!$resultC) {
                        $query = $this->db->query(" INSERT INTO access ( module, privileges, status, created_by, created_at)
                                                SELECT 
                                                '{$module}' as module
                                                ,'{$privileges}' as privileges
                                                ,10 as status
                                                ,'{$user}' as created_by
                                                , now() as created_at
                                                ");

                        if ($query === false)
                            throw new Exception();

                        $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                        if ($result) {
                            $this->session->set_flashdata("success_msg", "Data deleted...");
                            redirect('backend/access');
                        } else {
                            $this->session->set_flashdata("error_msg", "Data not deleted...");
                            redirect('backend/access');
                        }
                    } else {
                        $this->session->set_flashdata("error_msg", "The privilege already exists...");
                        redirect('backend/access');
                    }
                } else {

                    pre("ok");
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function delete($id = '')
    {
        try {
            if ($id != null) {
                $user = $this->user;

                $query = $this->db->query("	UPDATE access 
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
                    redirect('backend/access');
                } else {
                    $this->session->set_flashdata("error_msg", "Data not deleted...");
                    redirect('backend/access');
                }
            }

            $this->session->set_flashdata("error_msg", "ID notfound...");
            redirect('backend/access');
        } catch (Exception $e) {
            return $e;
        }
    }
}
