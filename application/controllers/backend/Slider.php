<?php defined('BASEPATH') or exit('No direct script access allowed');

class Slider extends CI_Controller
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
        if (privilage($this->user, "slider", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Slider';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Slider';
        $data_content['data_tabel']   = 'DataTable Slider';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "ms_slider/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

        //ajax request =======================>

    public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_slider");
        $this->db->where("(OrderNo LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            )");
        // if ($_REQUEST['length'] != -1)
        //     $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        // $all_data = $this->db->get()->result();

        /* ORDER */
        $order = array('ms_slider.DID' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if (empty($order)) {
            $this->db->order_by("ms_slider.DID", "DESC");
        } else {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }
        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }
        $valid_columns = array(
            0 => 'ms_slider.DID',
            2 => 'ms_slider.OrderNo',
            5 => 'ms_slider.SliderGroup',
            6 => 'ms_slider.Active',
        );

        if (!isset($valid_columns[$col])) {
            $ordr = null;
        } else {
            $ordr = $valid_columns[$col];
        }
        if ($ordr != null) {
            $this->db->order_by($ordr, $dir);
        }
        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();
        /* ORDER



        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_slider");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_slider");
        $this->db->where("(OrderNo LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlAc = "'" . base_url("backend/slider/active") . "'";
        $urlGr = "'" . base_url("backend/slider/group") . "'";
        $urlDl = "'" . base_url("backend/slider/delete") . "'";
        $path = "upload/HOME_SLIDER/";
        $header = "header";

        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->DID;
            $row[] = '<span id="order-' . $val->DID . '">' . $val->OrderNo . '</span> <span class="float-right">
                        <button type="button" class="btn btn-light btn-sm" onclick="functionUp(this)" value="' . $val->DID . '" ><i class="fas fa-angle-up"></i></button> 
                        <button type="button" class="btn btn-light btn-sm" onclick="functionDown(this)" value="' . $val->DID . '" ><i class="fas fa-angle-down"></i></button>
                    </span>';
            $row[] =  '<center><img src="' . base_url($path) . $val->ImagePath . '" width="200" height="100"></center>';
            $row[] = $val->VideoPath;
            $row[] = $val->SliderGroup == "Header" ? '<center>'.Buttons("header", "myGroup($val->DID, 'Footer', $urlGr)").'</center>' :
                                         '<center>'.Buttons("footer", "myGroup($val->DID, 'Header', $urlGr)").'</center>';
            $row[] = $val->Active != 1 ? '<center>'.Buttons("disabled", "myActive($val->DID, 1, $urlAc)").'</center>' :
                                         '<center>'.Buttons("actived", "myActive($val->DID, 0, $urlAc)").'</center>';

            if (privilage($this->user, "slider", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/Slider/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "slider", "MDelete")) {
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
        $this->db->update("ms_slider", array("OrderNo" => $this->input->post("OrderNo")));

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




    public function form($id = null)
    {

        /* SLIDER GROUP */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "SLIDERGROUP");
        $query = $this->db->get();

        $group = $query->result();

        /* DATA STATUS */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "slider");
        $query = $this->db->get();

        $active = $query->result();

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Slider Form';
        $data_content['breadcrumb']   = 'List Slider';
        $data_content['breadcrumb1']  = 'Slider Form';
        $data_content['data_tabel']   = 'Slider Form';
        $data_content['group']        = $group;
        $data_content['active']       = $active;


        $config["content_file"] = "ms_slider/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function form_edit($id = null)
    {
        /* SLIDER GROUP */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "SLIDERGROUP");
        $query = $this->db->get();

        $group = $query->result();
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "slider");
        $query = $this->db->get();

        $active = $query->result();


        $this->db->select("*");
        $this->db->from("ms_slider");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/slider');
        }

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 1;
        $data_content['title']        = 'General Form';
        $data_content['breadcrumb']   = 'List Slider';
        $data_content['breadcrumb1']  = 'Slider Form Update';
        $data_content['data_tabel']   = 'Slider Form Update';
        $data_content['all_data']     = $result;
        $data_content['group']        = $group;
        $data_content['active']       = $active;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "ms_slider/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


    public function add()
    {

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('SliderGroup', 'Slider Group', 'required');
        $this->form_validation->set_rules('ShortDescription', 'Short Description', 'required');
        $this->form_validation->set_rules('Description', 'Description', 'required');
        $this->form_validation->set_rules('OrderNo', 'Order No', 'required');
        $this->form_validation->set_rules('Active', 'Active', 'required');
        if ($this->input->post()) 
        {
            if ($_FILES['ImagePath']['tmp_name'] == "") {
                $this->form_validation->set_rules('ImagePath', 'Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                $errors = validation_errors();
                echo json_encode(['error' => $errors]);
            }
            else
            {
                
                $insert["ShortDescription"]= $this->input->post('ShortDescription');
                $insert["Description"]     = $this->input->post('Description');
                $insert["SliderGroup"]     = $this->input->post('SliderGroup');
                $insert["VideoPath"]       = $this->input->post('VideoPath');
                $insert["OrderNo"]         = $this->input->post('OrderNo');
                $insert["Active"]          = $this->input->post('Active');
                $insert['RBU']             = $this->user;
                $insert['RBT']             = date("Y-m-d H:i:s");

                $filename         = isset($_FILES['ImagePath']['name']) ? $_FILES['ImagePath']['name'] : NULL;
                $info             = pathinfo($filename);
                $image_name     = url_title(basename($filename, '.' . $info['extension']));
                $random         = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
                $image_filename = $image_name . '_' . $random . '.' . $info['extension'];


                if (!file_exists(IMAGE_BLOCK_APPS_ROOT . $image_filename)) {
                    $image_filename = $image_filename;
                }

                $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT;
                $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
                $configImage['max_size']        = 500;
                $configImage['file_name']       = $image_filename;
                $configImage['overwrite']       = FALSE;


                $this->load->library('upload', $configImage);
                $this->upload->initialize($configImage);


                if (!$this->upload->do_upload("ImagePath")) {
                    $ee =$this->upload->display_errors('', '');
                    echo json_encode(['error' => $ee]);
                }

                $file   = IMAGE_BLOCK_APPS_ROOT . $image_filename;

                $NewImageWidth          = 400;
                $NewImageHeight         = 400;
                $Quality                = 50;

                resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);


                $insert["ImagePath"] =  $image_filename;

                $this->db->save_queries = TRUE;
                $this->db->insert("ms_slider", $insert);

                if ($this->db->insert_id() > 1) 
                {

                    $sql         = $this->db->last_query();
                    $page         = base_url('backend/slider/add');
                    tr_log($sql, $page, $this->user);

                    $DID = $this->db->query("SELECT DID FROM ms_slider ORDER BY DID DESC LIMIT 1")->row()->DID;
                    echo json_encode(['success' => 'Berhasil']);
                } 
                else 
                {
                    $this->session->set_flashdata("error_msg", "Data Not Insert");
                    redirect('backend/slider/form');
                }
            }
        }
    }


    public function edit($did = null)
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('SliderGroup', 'Slider Group', 'required');
        $this->form_validation->set_rules('ShortDescription', 'Short Description', 'required');
        $this->form_validation->set_rules('Description', 'Description', 'required');
        $this->form_validation->set_rules('OrderNo', 'Order No', 'required');
        $this->form_validation->set_rules('Active', 'Active', 'required');
        if ($this->form_validation->run() == FALSE) 
        {
            $errors = validation_errors();
            echo json_encode(['error' => $errors]);
        }
        else 
        {
            if ($this->input->post()) 
            {
                if ($_FILES['ImagePath']['name'] == "") :

                    $this->db->select("*");
                    $this->db->from("ms_slider");
                    $this->db->where("DID", $did);

                    $result = $this->db->get()->row();

                    $update['ImagePath'] = $result->ImagePath;

                else :

                    $filename         = isset($_FILES['ImagePath']['name']) ? $_FILES['ImagePath']['name'] : NULL;
                    $info             = pathinfo($filename);
                    $image_name     = url_title(basename($filename, '.' . $info['extension']));
                    $random         = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
                    $image_filename = $image_name . '_' . $random . '.' . $info['extension'];

                    if (!file_exists(IMAGE_BLOCK_APPS_ROOT . $image_filename)) {
                        $image_filename = $image_filename;
                    }

                    $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT;
                    $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
                    $configImage['max_size']        = 500;
                    $configImage['file_name']       = $image_filename;
                    $configImage['overwrite']       = FALSE;


                    $this->load->library('upload', $configImage);
                    $this->upload->initialize($configImage);


                    if (!$this->upload->do_upload("ImagePath")) 
                    {
                        $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["ImagePath"]['name'] . ", " . $this->upload->display_errors('', ''));
                        redirect('backend/slider/form');
                    }


                    $file   = IMAGE_BLOCK_APPS_ROOT . $image_filename;

                    $NewImageWidth          = 400;
                    $NewImageHeight         = 400;
                    $Quality                = 50;

                    resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);

                    // $path = IMAGE_BLOCK_APPS_ROOT. $image_filename;;
                    // $type = pathinfo($path, PATHINFO_EXTENSION);
                    // $dataimg = file_get_contents($path);
                    // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
                    $update["ImagePath"] =  $image_filename;

                endif;  

                    $update["ShortDescription"]     = $this->input->post('ShortDescription');
                    $update["Description"]     = $this->input->post('Description');
                    $update["SliderGroup"]     = $this->input->post('SliderGroup');
                    $update["VideoPath"]       = $this->input->post('VideoPath');
                    $update["OrderNo"]         = $this->input->post('OrderNo');
                    $update["Active"]          = $this->input->post('Active');
                    $update['RBU']             = $this->user;
                    $update['RBT']             = date("Y-m-d H:i:s");


                $this->db->save_queries = TRUE;
                $this->db->where("DID", $did);
                $this->db->update("ms_slider", $update);

                $sql         = $this->db->last_query();
                $page         = base_url('backend/slider/edit');
                tr_log($sql, $page, $this->user);


                echo json_encode(['success' => 'Berhasil']);
            }
        }
    }


    public function active()
    {
        if ($this->input->post("DID") != "") {

            $data["Active"] = $this->input->post("active");
            $data["RBU"]    = $this->user;
            $data["RBT"]    = dateSekarang();

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_slider", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/slider/active');
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

    public function group()
    {
        if ($this->input->post("DID") != "") {

            $data["SliderGroup"] = $this->input->post("value");
            $data["RBU"]    = $this->user;
            $data["RBT"]    = dateSekarang();

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_slider", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/slider/slidergroup');
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
            $this->db->delete('ms_slider');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/slider/delete');
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

     public function detail()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
       
        /*WORKFIELD*/
        $this->db->select("*");
        $this->db->from("ms_slider");
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
                                    <td valign="top"><b>Short Description</b></td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $slider->ShortDescription.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top"><b>Description</b></td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$slider->Description.'</td>
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
