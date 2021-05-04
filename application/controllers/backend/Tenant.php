<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tenant extends CI_Controller
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
        /** DATA SEND TO VIEW */
       if (privilage($this->user, "tenant", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Tenant';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Tenant';
        $data_content['data_tabel']   = 'DataTable Tenant';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "Tenant/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


    public function get_list()
    {
        /* SELECT DATA ALL */

       
        $this->db->select("*");
        $this->db->from("ms_tenant");
        $this->db->where("(TenantName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or TenantFloor LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");


        /* ORDER */
        $order = array('RBT' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if(empty($order))
        {
            $this->db->order_by("RBT", "DESC");
        }else{
            foreach($order as $o)
          {
             $col = $o['column'];
             $dir= $o['dir'];
          }
        }
        if($dir != "asc" && $dir != "desc")
        {
           $dir = "desc";
        }
        $valid_columns = array(
            0=>'DID',
            1=>'ImagePath',
            2=>'TenantName',
            3=>'TenantFloor',
            4=>'OrderNo',
            5=>'Active',
            6=>'RBU',
            7=>'RBT',
        );

        if(!isset($valid_columns[$col]))
        {
          $ordr = null;
        }
        else
        {
          $ordr = $valid_columns[$col];
        }
        if($ordr !=null)
        {
          $this->db->order_by($ordr, $dir);
        }

        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();

        

        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_tenant");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_tenant");
        $this->db->where("(TenantName LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or TenantFloor LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlAc = "'" . base_url("backend/Tenant/active") . "'";
        $urlDl = "'" . base_url("backend/Tenant/delete") . "'";
        $path = "upload/TENANT/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = '<center><img src="' . base_url($path) . $val->ImagePath . '" width="200" height="100"></center>';
            $row[] = $val->TenantName;
            $row[] = $val->TenantFloor;
             $row[] = '<span id="order-' . $val->DID . '">' . $val->OrderNo . '</span> <span class="float-right">
                        <button type="button" class="btn btn-light btn-sm" onclick="functionUp(this)" value="' . $val->DID . '" ><i class="fas fa-angle-up"></i></button> 
                        <button type="button" class="btn btn-light btn-sm" onclick="functionDown(this)" value="' . $val->DID . '" ><i class="fas fa-angle-down"></i></button>
                    </span>';
            $row[] = $val->Active != 1 ? '<center>' . Buttons("disabled", "myActive($val->DID, 1, $urlAc)") . '</center>' :
                '<center>' . Buttons("actived", "myActive($val->DID, 0, $urlAc)") . '</center>';
            $row[] = $val->RBU;
            $row[] = date("d-M-Y H:i:s", strtotime($val->RBT));
            if (privilage($this->user, "tenant", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/Tenant/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "tenant", "MDelete")) {
                $bt_delete =  Buttons("delete", "myDelete($val->DID, $urlDl)");
            } else {
                $bt_delete =  "";
            }

            $row[] = '<center>' . $bt_edit . " " .  $bt_delete . '</center>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => count($recordsFiltered),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function update_orderno()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->db->where("DID", $this->input->post("DID"));
        $this->db->update("ms_tenant", array("OrderNo" => $this->input->post("OrderNo")));

        if ($this->db->affected_rows() > 0) {
            $res = array(
                "status" => true,
            );
            echo json_encode($res);
            exit;
        } else {
            $res = array(
                "status" => false,
            );
            echo json_encode($res);
            exit;
        }
    }

    public function active()
    {
        if ($this->input->post("DID") != "") {
            // pre($_POST);
            $data["Active"] = $this->input->post("active");
            // $data["RBU"]    = $this->user;
            $data["RBT"]    = dateSekarang();

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_tenant", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/Tenant/active');
                tr_log($sql, $page, $this->user);

                $res = array(
                    "status" => true,
                );
                echo json_encode($res);
            } else {
                $res = array(
                    "status" => false
                );
                echo json_encode($res);
            }
        } else {
            $res = array(
                "status" => "Invalid Id"
            );
            echo json_encode($res);
        }
    }

    public function delete()
    {
        if ($this->input->post("DID") != "") {

            $this->db->save_queries = TRUE;
            $this->db->where('DID', $this->input->post("DID"));
            $this->db->delete('ms_tenant');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/Tenant/delete');
                tr_log($sql, $page, $this->user);

                $res = array(
                    "status" => true,
                );
                echo json_encode($res);
            } else {

                $res = array(
                    "status" => false
                );
                echo json_encode($res);
            }
        } else {
            $res = array(
                "status" => "Invalid Id"
            );
            echo json_encode($res);
        }
    }

    public function form($id = null)
    {

        // if ($id == "add") {
        //     if (!is_allowed($this->session->userdata('user'), 'manu', 'add')) :
        //         $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
        //         redirect('backend/Tenant');
        //     endif;
        // }

        /* DATA STATUS */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "TENANT");
        $query = $this->db->get();

        $active = $query->result();

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Tenant Form';
        $data_content['breadcrumb']   = 'List Tenant';
        $data_content['breadcrumb1']  = 'Tenant Form';
        $data_content['data_tabel']   = 'Tenant Form';
        $data_content['active']       = $active;
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "Tenant/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


    public function add()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('TenantName','Tenant Name', 'required');
        $this->form_validation->set_rules('TenantFloor','Tenant Floor', 'required');
        $this->form_validation->set_rules('OrderNo','Order No', 'required');
        $this->form_validation->set_rules('Active','Active', 'required');

        if ($_FILES['ImagePath']['tmp_name'] == "") {
            $this->form_validation->set_rules('ImagePath','Tenant Image', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {


            $insert["TenantName"]            = $this->input->post("TenantName");
            $insert["TenantFloor"]           = $this->input->post("TenantFloor");
            $insert["OrderNo"]               = $this->input->post("OrderNo");
            $insert["Active"]                = $this->input->post("Active");
            $insert["RBU"]                   = $this->user;
            $insert["RBT"]                   = dateSekarang();

            $filename         = isset($_FILES['ImagePath']['name']) ? $_FILES['ImagePath']['name'] : NULL;
            $info             = pathinfo($filename);
            $image_name       = url_title(basename($filename, '.' . $info['extension']));
            $random           = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
            $image_filename   = $image_name . '_' . $random . '.' . $info['extension'];
             if (!file_exists(IMAGE_BLOCK_APPS_ROOT_TENANT . $image_filename)) {
                $image_filename = $image_filename;
            }

            $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_TENANT;
            $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
            $configImage['max_size']        = 500;
            $configImage['file_name']       = $image_filename;
            $configImage['overwrite']       = FALSE;


            $this->load->library('upload', $configImage);
            $this->upload->initialize($configImage);

             if (!$this->upload->do_upload("ImagePath")) {
                $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["ImagePath"]['name'] . ", " . $this->upload->display_errors('', ''));
                redirect('backend/Tenant/form');
            }

            $file   = IMAGE_BLOCK_APPS_ROOT_TENANT . $image_filename;

            $NewImageWidth          = 400;
            $NewImageHeight         = 400;
            $Quality                = 50;

            resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);


            $insert["ImagePath"] =  $image_filename;

            $this->db->save_queries = TRUE;
            $this->db->insert("ms_tenant", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/Tenant/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM ms_tenant ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/Tenant');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/Tenant/form');
            }
        
        }
    }

     public function form_edit($id = null)
    {

        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "TENANT");
        $query = $this->db->get();
        $active = $query->result();


        $this->db->select("*");
        $this->db->from("ms_tenant");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/Tenant');
        }
       
        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 1;
        $data_content['title']        = 'General Form';
        $data_content['breadcrumb']   = 'List Tenant';
        $data_content['breadcrumb1']  = 'Tenant Form Update';
        $data_content['data_tabel']   = 'Tenant Form Update';
        $data_content['all_data']     = $result;
        $data_content['active']       = $active;

        $config["content_file"] = "Tenant/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

          public function edit($did = null)
    {   

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('TenantName','Tenant Name', 'required');
        $this->form_validation->set_rules('TenantFloor','Tenant Floor', 'required');
        $this->form_validation->set_rules('OrderNo','Order No', 'required');
        $this->form_validation->set_rules('Active','Active', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
            if ($_FILES['ImagePath']['name'] == "") :

                $this->db->select("*");
                $this->db->from("ms_tenant");
                $this->db->where("DID", $did);

                $result = $this->db->get()->row();

                $update['ImagePath'] = $result->ImagePath;

            else :

                $filename         = isset($_FILES['ImagePath']['name']) ? $_FILES['ImagePath']['name'] : NULL;
                $info             = pathinfo($filename);
                $image_name     = url_title(basename($filename, '.' . $info['extension']));
                $random         = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
                $image_filename = $image_name . '_' . $random . '.' . $info['extension'];

                if (!file_exists(IMAGE_BLOCK_APPS_ROOT_TENANT . $image_filename)) {
                    $image_filename = $image_filename;
                }

                $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_TENANT;
                $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
                $configImage['max_size']        = 500;
                $configImage['file_name']       = $image_filename;
                $configImage['overwrite']       = FALSE;


                $this->load->library('upload', $configImage);
                $this->upload->initialize($configImage);


                if (!$this->upload->do_upload("ImagePath")) {
                    $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["ImagePath"]['name'] . ", " . $this->upload->display_errors('', ''));
                    redirect('backend/Tenant/form');
                }


                $file   = IMAGE_BLOCK_APPS_ROOT_TENANT . $image_filename;

                $NewImageWidth          = 400;
                $NewImageHeight         = 400;
                $Quality                = 50;

                resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);

                // $path = IMAGE_BLOCK_APPS_ROOT_TENANT. $image_filename;;
                // $type = pathinfo($path, PATHINFO_EXTENSION);
                // $dataimg = file_get_contents($path);
                // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
                $update["ImagePath"] =  $image_filename;

            endif;


                $update["TenantName"]            = $this->input->post("TenantName");
                $update["TenantFloor"]           = $this->input->post("TenantFloor");
                $update["OrderNo"]               = $this->input->post("OrderNo");
                $update["Active"]                = $this->input->post("Active");
                $update["RBU"]                   = $this->user;
                $update["RBT"]                   = dateSekarang();


                $this->db->save_queries = TRUE;
                $this->db->where("DID", $did);
                $this->db->update("ms_tenant", $update);
                $sql         = $this->db->last_query();
                $page         = base_url('backend/Tenant/edit');
                tr_log($sql, $page, $this->user);

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") 
                {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                        redirect('backend/Tenant');
                } 
            }

        }
    }

}