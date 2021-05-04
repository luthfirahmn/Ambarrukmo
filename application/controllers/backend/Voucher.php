<?php defined('BASEPATH') or exit('No direct script access allowed');

class Voucher extends CI_Controller
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
        if (privilage($this->user, "voucher", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Voucher';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Voucher';
        $data_content['data_tabel']   = 'DataTable Voucher';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "ms_voucher/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function get_list()
    {
        /* SELECT DATA ALL */

       
        $this->db->select("*");
        $this->db->from("ms_voucher");
        $this->db->where("(VoucherName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or VoucherCode LIKE '%" . $_REQUEST['search']['value'] . "%'
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
            2=>'VoucherCode',
            3=>'VoucherName',
            5=>'RedeemPoint',
            6=>'RedeemCode',
            7=>'QTY',
            8=>'ExpiredTime',
            9=>'Active',
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
        $this->db->from("ms_voucher");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_voucher");
        $this->db->where("(VoucherName LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or VoucherCode LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlAc = "'" . base_url("backend/voucher/active") . "'";
        $urlDl = "'" . base_url("backend/voucher/delete") . "'";
        $path = "upload/VOUCHER/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->DID;
            $row[] = $val->VoucherCode;
            $row[] = $val->VoucherName;
            $row[] = '<center><img src="' . base_url($path) . $val->VoucherIMG . '" width="200" height="100"></center>';
            $row[] = rtrim(rtrim($val->RedeemPoint, '0'), '.');
            $row[] = $val->RedeemCode;
            $row[] = $val->Qty;
            $row[] = $val->ExpiredTime;
            $row[] = $val->Active != 1 ? '<center>' . Buttons("disabled", "myActive($val->DID, 1, $urlAc)") . '</center>' :
                '<center>' . Buttons("actived", "myActive($val->DID, 0, $urlAc)") . '</center>';
          if (privilage($this->user, "voucher", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/Voucher/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "voucher", "MDelete")) {
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

    public function active()
    {
        if ($this->input->post("DID") != "") {
            // pre($_POST);
            $data["Active"] = $this->input->post("active");
            // $data["RBU"]    = $this->user;
            $data["RBT"]    = dateSekarang();

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_voucher", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/voucher/active');
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
            $this->db->delete('ms_voucher');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/voucher/delete');
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


        /* DATA STATUS */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "voucher");
        $query = $this->db->get();

        $active = $query->result();

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Voucher Form';
        $data_content['breadcrumb']   = 'List Voucher';
        $data_content['breadcrumb1']  = 'Voucher Form';
        $data_content['data_tabel']   = 'Voucher Form';
        $data_content['active']       = $active;
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "ms_voucher/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function add()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('VoucherCode','Voucher Code', 'required');
        $this->form_validation->set_rules('VoucherName','Voucher Name', 'required');
        $this->form_validation->set_rules('RedeemPoint','Redeem Point', 'required');
        $this->form_validation->set_rules('RedeemCode','Redeem Code', 'required');
        $this->form_validation->set_rules('Qty','Quantity', 'required');
        $this->form_validation->set_rules('ExpiredTime','Expired Time', 'required');
        $this->form_validation->set_rules('VoucherShortNote','Voucher Short Note', 'required');
        $this->form_validation->set_rules('VoucherNote','Voucher Note', 'required');
        $this->form_validation->set_rules('Active','Active', 'required');

        if ($_FILES['VoucherIMG']['tmp_name'] == "") {
            $this->form_validation->set_rules('VoucherIMG','Voucher Image', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {
            $insert["VoucherCode"]           = $this->input->post("VoucherCode");
            $insert["VoucherName"]           = $this->input->post("VoucherName");
            $insert["RedeemPoint"]           = $this->input->post("RedeemPoint");
            $insert["RedeemCode"]           = $this->input->post("RedeemCode");
            $insert["Qty"]                   = $this->input->post("Qty");
            $insert["ExpiredTime"]           = dateSekarang(18,$this->input->post("ExpiredTime"));
            $insert["VoucherShortNote"]           = $this->input->post("VoucherShortNote");
            $insert["VoucherNote"]           = $this->input->post("VoucherNote");
            $insert["Active"]                = $this->input->post("Active");
            $insert["RBT"]                   = dateSekarang();

            $filename         = isset($_FILES['VoucherIMG']['name']) ? $_FILES['VoucherIMG']['name'] : NULL;
            $info             = pathinfo($filename);
            $image_name       = url_title(basename($filename, '.' . $info['extension']));
            $random           = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
            $image_filename   = $image_name . '_' . $random . '.' . $info['extension'];
             if (!file_exists(IMAGE_BLOCK_APPS_ROOT_VOUCHER . $image_filename)) {
                $image_filename = $image_filename;
            }

            $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_VOUCHER;
            $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
            $configImage['max_size']        = 500;
            $configImage['file_name']       = $image_filename;
            $configImage['overwrite']       = FALSE;


            $this->load->library('upload', $configImage);
            $this->upload->initialize($configImage);

             if (!$this->upload->do_upload("VoucherIMG")) {
                $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["VoucherIMG"]['name'] . ", " . $this->upload->display_errors('', ''));
                redirect('backend/voucher/form');
            }

            $file   = IMAGE_BLOCK_APPS_ROOT_VOUCHER . $image_filename;

            $NewImageWidth          = 400;
            $NewImageHeight         = 400;
            $Quality                = 50;

            resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);


            $insert["VoucherIMG"] =  $image_filename;

            $this->db->save_queries = TRUE;
            $this->db->insert("ms_voucher", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/voucher/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM ms_voucher ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/voucher');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/voucher/form');
            }
        
        }
    }

    public function form_edit($id = null)
    {

        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "voucher");
        $query = $this->db->get();

        $active = $query->result();


        $this->db->select("*");
        $this->db->from("ms_voucher");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/voucher');
        }
        //$date = date_create($result->ExpiredTime); date_format($date, "Y-m-d");

        //pre(date("Y-m-d", $result->ExpiredTime));

        //pre($result);
       
        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 1;
        $data_content['title']        = 'General Form';
        $data_content['breadcrumb']   = 'List Voucher';
        $data_content['breadcrumb1']  = 'Voucher Form Update';
        $data_content['data_tabel']   = 'Voucher Form Update';
        $data_content['all_data']     = $result;
        $data_content['active']       = $active;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "ms_voucher/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function edit($did = null)
    {   

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('VoucherCode','Voucher Code', 'required');
        $this->form_validation->set_rules('VoucherName','Voucher Name', 'required');
        $this->form_validation->set_rules('RedeemPoint','Redeem Point', 'required');
        $this->form_validation->set_rules('RedeemCode','Redeem Code', 'required');
        $this->form_validation->set_rules('Qty','Quantity', 'required');
        $this->form_validation->set_rules('ExpiredTime','Expired Time', 'required');
        $this->form_validation->set_rules('VoucherShortNote','Voucher Short Note', 'required');
        $this->form_validation->set_rules('VoucherNote','Voucher Note', 'required');
        $this->form_validation->set_rules('Active','Active', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
            if ($_FILES['VoucherIMG']['name'] == "") :

                $this->db->select("*");
                $this->db->from("ms_voucher");
                $this->db->where("DID", $did);

                $result = $this->db->get()->row();

                $update['VoucherIMG'] = $result->VoucherIMG;

            else :

                $filename         = isset($_FILES['VoucherIMG']['name']) ? $_FILES['VoucherIMG']['name'] : NULL;
                $info             = pathinfo($filename);
                $image_name     = url_title(basename($filename, '.' . $info['extension']));
                $random         = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
                $image_filename = $image_name . '_' . $random . '.' . $info['extension'];

                if (!file_exists(IMAGE_BLOCK_APPS_ROOT_VOUCHER . $image_filename)) {
                    $image_filename = $image_filename;
                }

                $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_VOUCHER;
                $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
                $configImage['max_size']        = 500;
                $configImage['file_name']       = $image_filename;
                $configImage['overwrite']       = FALSE;


                $this->load->library('upload', $configImage);
                $this->upload->initialize($configImage);


                if (!$this->upload->do_upload("VoucherIMG")) {
                    $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["VoucherIMG"]['name'] . ", " . $this->upload->display_errors('', ''));
                    redirect('backend/voucher/form');
                }


                $file   = IMAGE_BLOCK_APPS_ROOT_VOUCHER . $image_filename;

                $NewImageWidth          = 400;
                $NewImageHeight         = 400;
                $Quality                = 50;

                resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);

                $update["VoucherIMG"] =  $image_filename;

            endif;

            $update["VoucherCode"]           = $this->input->post("VoucherCode");
            $update["VoucherName"]           = $this->input->post("VoucherName");
            $update["RedeemPoint"]           = $this->input->post("RedeemPoint");
            $update["RedeemCode"]           = $this->input->post("RedeemCode");
            $update["Qty"]                   = $this->input->post("Qty");
            $update["ExpiredTime"]           = dateSekarang(18,$this->input->post("ExpiredTime"));
            $update["VoucherNote"]           = $this->input->post("VoucherNote");
            $update["VoucherShortNote"]           = $this->input->post("VoucherShortNote");
            $update["Active"]                = $this->input->post("Active");
            $update["RBT"]                   = dateSekarang();


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $did);
            $this->db->update("ms_voucher", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/voucher/edit');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                        redirect('backend/voucher');
                } 
            }

        }
    }

    public function detail()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
       
        /*WORKFIELD*/
        $this->db->select("*");
        $this->db->from("ms_voucher");
        $this->db->where("DID", $this->input->post("did"));
        $slider = $this->db->get()->row();

        $row1 = '<div class="card">
                <div class="card-header">
                <h3 class="card-title">Detail</h3>
                <div class="card-tools">
                </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        <table class="detail">
                            <tbody>
                                <tr style="background: none;">
                                    <td valign="top">Short Note</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $slider->VoucherShortNote.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Note</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$slider->VoucherNote.'</td>
                                </tr>     
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>';

        if ($slider) {
              $output['data'] = $row1;
         }else{
            $row2 = '<div class="box box-danger">
                            <div class="box-body">
                               <h5 style="text-align:center">DATA NOT FOUND</h5>
                            </div>
                    </div>';
            $output['data'] = $row2;

        }
        echo json_encode($output);
    }
}

