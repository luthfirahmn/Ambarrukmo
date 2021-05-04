<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module extends CI_Controller
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
            /* S GET DATA */
            $query = $this->db->query(" SELECT * 
                                        FROM module
                                        WHERE NULLIF (deleted_by , '') IS NULL  
										AND NULLIF (deleted_at , '') IS NULL
                                    ");

            if ($query === false)
                throw new Exception();


            $all_data = $query->result();
            //pre($all_data);
            $data_content                 = array();
            $data_content['title']        = 'Module';
            $data_content['breadcrumb']   = 'Dashboard';
            $data_content['breadcrumb1']  = 'Module';
            $data_content['data_tabel']   = 'DataTable Module';
            $data_content['success_msg']  = $this->session->flashdata("success_msg");
            $data_content['error_msg']    = $this->session->flashdata("error_msg");
            $data_content['info_msg']     = $this->session->flashdata("info_msg");
            $data_content['all_data']     = $all_data;
            /* E DATA FOR VIEW */
            //pre($all_data);

            $config["content_file"] = "module/index";
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
            if (!is_allowed($this->session->userdata('user'), 'module', 'add')) :
                $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
                redirect('backend/module');
            endif;
        }

        if ($id != null && $id != "add") {
            try {

                $query = $this->db->query(" SELECT * 
                                            FROM module 
                                            WHERE id = {$id}
                                            ");

                if ($query === false)
                    throw new Exception();

                $result = $query->row();
                //pre($result);

                $data_content                 = array();
                $data_content['status_edit']  = 1;
                $data_content['title']        = 'General Form';
                $data_content['breadcrumb']   = 'List Module';
                $data_content['breadcrumb1']  = 'Module Form Update';
                $data_content['data_tabel']   = 'Module Form Update';
                $data_content['all_data']     =  $result;
                /* E DATA FOR VIEW */
                //pre($all_data);

                $config["content_file"] = "module/form";
                $config["content_data"] = $data_content;

                $this->template->initialize($config);
                $this->template->render();
            } catch (Exception $e) {
                return $e;
            }
        }



        $data_content                 = array();
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'General Form';
        $data_content['breadcrumb']   = 'List Module';
        $data_content['breadcrumb1']  = 'Module Form';
        $data_content['data_tabel']   = 'Module Form';
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "module/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function add()
    {
        try {
            if ($this->input->post()) {

                $user                   = $this->user;
                $name                   = $this->input->post('name');
                $file                   = $this->input->post('file');
                $controllers            = $this->input->post('controllers');
                $description            = $this->input->post('description');

                $this->db->trans_start();

                $query = $this->db->query(" INSERT INTO module(name, file, controllers, description, status, created_by, created_at)
                                             SELECT   
                                                '{$name}' as name
                                                ,'{$file}' as file
                                                ,'{$controllers}' as controllers
                                                ,'{$description}' as description
                                                ,4 as status
                                                ,'{$user}' as created_by
                                                ,NOW() as created_at
                                            ");

                if ($query === false)
                    throw new Exception();

                $result1 = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                if ($result1) {

                    $query2 = $this->db->query(" SELECT id FROM module WHERE name = '{$name}'");

                    if ($query2 === false)
                        throw new Exception();

                    $result2 =  $query2->row();

                    if ($result2) {

                        $arr = array("add", "edit", "delete", "view");

                        foreach ($arr as $key => $val) {

                            $query3 = $this->db->query(" INSERT INTO access ( module, privileges , status ,created_by, created_at)
                                    SELECT   
                                       '{$result2->id}' as module
                                       ,'{$val}' as privileges
                                       ,10 as status
                                       ,'{$user}' as created_by
                                       ,NOW() as created_at
                                   ");
                        }

                        if ($query3 === false)
                            throw new Exception();

                        $result3 = $this->db->affected_rows($query3) > 0 ? TRUE : FALSE;

                        $this->db->trans_commit();


                        if ($result3) {
                            $this->session->set_flashdata("success_msg", "Data insert success...");
                            redirect('backend/module');
                        } else {
                            $this->session->set_flashdata("error_msg", "faild to insert...");
                            redirect('backend/module/form');
                        }
                    } else {
                        throw new Exception();
                    }
                } else {
                    throw new Exception();
                }
            } else {
                $this->session->set_flashdata("error_msg", "error method insert...");
                redirect('backend/module/form');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return $e;
        }
    }

    public function edit()
    {
        try {
            if ($this->input->post()) {

                $user            = $this->user;
                $id              = $this->input->post('id');
                $name            = $this->input->post('name');
                $file            = $this->input->post('file');
                $controllers     = $this->input->post('controllers');
                $description     = $this->input->post('description');


                $query = $this->db->query(" UPDATE module 
                                        SET 
                                           name ='{$name}'
                                           ,file = '{$file}'
                                           ,controllers = '{$controllers}'
                                           ,description = '{$description}'
                                           ,status = 4
                                           ,updated_by = '{$user}'
                                           ,updated_at = now()
                                           WHERE id = '{$id}'
                                    
                                    ");

                if ($query === false)
                    throw new Exception();

                $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                if ($result) {

                    $this->session->set_flashdata("success_msg", "Data updated...");
                    redirect('backend/module');
                } else {
                    $this->session->set_flashdata("error_msg", "Data not updated...");
                    redirect('backend/module/form/' . $id);
                }
            } else {
                $this->session->set_flashdata("error_msg", "error method edit...");
                redirect('backend/module');
            }
        } catch (Exception $e) {
            return $e;
        }
    }


    public function delete($id = null)
    {
        try {
            if ($id != null) {
                //pre($id);
                $user = $this->user;

                $query = $this->db->query("	UPDATE module 
											SET 
												deleted_by = $user
												,deleted_at = now()
											WHERE id = '{$id}'
                                         ");

                if ($query === false)
                    throw new Exception();

                $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                if ($result) {

                    $query2 = $this->db->query(" SELECT id
                                                FROM access
                                                WHERE module = {$id}
                                                ");

                    if ($query2 === false)
                        throw new Exception();

                    $result2 = $query2->result();


                    if ($result2) {

                        foreach ($result2 as $ky => $values) {

                            $query3 = $this->db->query("DELETE 
                                                        FROM role_access
                                                        WHERE access_id = {$values->id}
                                                        ");
                        }

                        if ($query3 === false)
                            throw new Exception();

                        $result3 = $this->db->affected_rows($query3) > 0 ? TRUE : FALSE;

                        if ($result3) {
                            $this->session->set_flashdata("success_msg", "Data deleted...");
                            redirect('backend/module');
                        } else {
                            $this->session->set_flashdata("error_msg", "Data not deleted...");
                            redirect('backend/module');
                        }
                    }
                }
            }

            $this->session->set_flashdata("error_msg", "ID notfound...");
            redirect('backend/module');
        } catch (Exception $e) {
            return $e;
        }
    }
}
