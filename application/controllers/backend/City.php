<?php defined('BASEPATH') or exit('No direct script access allowed');

class City extends CI_Controller
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
                                    FROM city
                                    WHERE NULLIF (deleted_by , '') IS NULL  
                                    AND NULLIF (deleted_at , '') IS NULL
                                ");

            if ($query === false)
                throw new Exception();


            $all_data = $query->result();
            //pre($all_data);
            $data_content                 = array();
            $data_content['title']        = 'City';
            $data_content['breadcrumb']   = 'Dashboard';
            $data_content['breadcrumb1']  = 'City';
            $data_content['data_tabel']   = 'DataTable City';
            $data_content['success_msg']  = $this->session->flashdata("success_msg");
            $data_content['error_msg']    = $this->session->flashdata("error_msg");
            $data_content['info_msg']     = $this->session->flashdata("info_msg");
            $data_content['all_data']     = $all_data;
            /* E DATA FOR VIEW */
            //pre($all_data);

            //pre($data_content);
            $config["content_file"] = "city/index";
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
            if (!is_allowed($this->session->userdata('user'), 'city', 'add')) :
                $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
                redirect('backend/city');
            endif;
        }

        if ($id != null && $id != "add") {
            try {

                $query = $this->db->query(" SELECT * 
                                            FROM city 
                                            WHERE city_id = {$id}
                                            ");

                if ($query === false)
                    throw new Exception();

                $result = $query->row();
               // pre($result);

                $data_content                 = array();
                $data_content['status_edit']  = 1;
                $data_content['title']        = 'General Form';
                $data_content['breadcrumb']   = 'List City';
                $data_content['breadcrumb1']  = 'City Form Update';
                $data_content['data_tabel']   = 'City Form Update';
                $data_content['all_data']     =  $result;
                /* E DATA FOR VIEW */
                //pre($all_data);

                $config["content_file"] = "city/form";
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
        $data_content['breadcrumb']   = 'List City';
        $data_content['breadcrumb1']  = 'City Form';
        $data_content['data_tabel']   = 'City Form';
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "city/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function add()
    {
        try {
            if ($this->input->post()) {
               
                $user               = $this->user;
                $city_name      	= $this->input->post('city_name');
                $country_id			= $this->input->post('country');
                $about 				= $this->input->post('about');
                $short_description 	= $this->input->post('description');

                $slug 				= "test";
                $operating_hours 	= "test";
                $address1 			= "test";
                $address2 			= "test";
                $phone1 			= "test";
                $phone2 			= "test";
                $wa_phone 			= "test";
                $gps_location 		= "test";

                // $slug 				= $this->input->post('slug');
                // $operating_hours 	= $this->input->post('operating_hours');
                // $address1 			= $this->input->post('address1');
                // $address2 			= $this->input->post('address2');
                // $phone1 			= $this->input->post('phone1');
                // $phone2 			= $this->input->post('phone2');
                // $wa_phone 			= $this->input->post('wa_phone');
                // $gps_location 		= $this->input->post('gps_location');

                $query = $this->db->query(" INSERT INTO city( city_name
                                                                ,slug
                                                                ,country_id
                                                                ,short_description
                                                                ,about
                                                                ,operating_hours
                                                                ,address1
                                                                ,address2
                                                                ,phone1
                                                                ,phone2
                                                                ,wa_phone
                                                                ,gps_location
                                                                ,created_by
                                                                ,created_at)
                                             SELECT   
												'{$city_name}' as city_name
												,'{$slug}' as slug
												,'{$country_id}' as country_id
												,'{$short_description}' as short_description
												,'{$about}' as about
												,'{$operating_hours}' as operating_hours
												,'{$address1}' as address1
												,'{$address2}' as address2
												,'{$phone1}' as phone1
												,'{$phone2}' as phone2
												,'{$wa_phone}' as wa_phone
												,'{$gps_location}' as gps_location
												,{$user} as created_by
												,NOW() as created_at
                                            ");

                if ($query === false)
                    throw new Exception();

                $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                if ($result) {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/city');
                } else {
                    $this->session->set_flashdata("error_msg", "faild to insert...");
                    redirect('backend/city/form');
                }
            } else {
                $this->session->set_flashdata("error_msg", "error method insert...");
                redirect('backend/city/form');
            }
        } catch (Exception $e) {
            return $e;
        }
    }


    public function edit()
    {
        try {
            if ($this->input->post()) {

                $user               = $this->user;
                $id                 = $this->input->post("id");
                $city_name      	= $this->input->post('city_name');
                $country_id			= $this->input->post('country');
                $about 				= $this->input->post('about');
                $short_description 	= $this->input->post('description');

                $slug 				= "test";
                $operating_hours 	= "test";
                $address1 			= "test";
                $address2 			= "test";
                $phone1 			= "test";
                $phone2 			= "test";
                $wa_phone 			= "test";
                $gps_location 		= "test";


                $query = $this->db->query(" UPDATE city 
                                            SET 
                                                city_name='{$city_name}'
                                                ,slug='{$slug}'
                                                ,country_id='{$country_id}'
                                                ,short_description='{$short_description}'
                                                ,about='{$about}'
                                                ,operating_hours='{$operating_hours}'
                                                ,address1='{$address1}'
                                                ,address2='{$address2}'
                                                ,phone1='{$phone1}'
                                                ,phone2='{$phone2}'
                                                ,wa_phone='{$wa_phone}'
                                                ,gps_location='{$gps_location}'
                                                ,updated_by= {$user}
                                                ,updated_at= now()
                                            WHERE city_id = '{$id}'
            ");

                if ($query === false)
                    throw new Exception();

                $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                if ($result) {

                    $this->session->set_flashdata("success_msg", "Data updated...");
                    redirect('backend/city');
                } else {
                    $this->session->set_flashdata("error_msg", "Data not updated...");
                    redirect('backend/city/form/' . $id);
                }
            } else {
                $this->session->set_flashdata("error_msg", "error method edit...");
                redirect('backend/city');
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

                $query = $this->db->query("	UPDATE city 
                                          SET 
                                              deleted_by = $user
                                              ,deleted_at = now()
                                          WHERE city_id = '{$id}'
                                       ");

                if ($query === false)
                    throw new Exception();

                $result = $this->db->affected_rows($query) > 0 ? TRUE : FALSE;

                if ($result) {
                    $this->session->set_flashdata("success_msg", "Data deleted...");
                    redirect('backend/city');
                } else {
                    $this->session->set_flashdata("error_msg", "Data not deleted...");
                    redirect('backend/city');
                }
            }

            $this->session->set_flashdata("error_msg", "ID notfound...");
            redirect('backend/city');
        } catch (Exception $e) {
            return $e;
        }
    }
}
