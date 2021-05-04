<?php defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/login');
        }
        $this->user = $this->session->userdata('user');
        $this->load->library('bcrypt');
        date_default_timezone_set("Asia/Jakarta");
    }

    public function index()
    {
        /** DATA SEND TO VIEW */    
        if (privilage($this->user, "member", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        };

        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Member';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Member';
        $data_content['data_tabel']   = 'DataTable Member';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "Member/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_member");
        $this->db->where("(MemberID LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or Email LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or FullName LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");


        /* ORDER */
        $order = array('DID' => 'ASC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if (empty($order)) {
            $this->db->order_by("DID", "ASC");
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
            0 => 'DID',
            1 => 'MemberID',
            2 => 'IDPhoto',
            3 => 'MobilePhoneNo',
            5 => 'Email',
/*          7 => 'JoinDate',
            8 => 'LastLogin',
            9 => 'OTP',
            10 => 'OTPExpired',
            11 => 'TotalPoint',
            12 => 'FullName',
            13 => 'Gender',
            14 => 'Address1',
            15 => 'Address2',*/
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



        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_member");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_member");
        $this->db->where("(MemberID LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or Email LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or FullName LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlAc = "'" . base_url("backend/Member/active") . "'";
        $urlDl = "'" . base_url("backend/Member/delete") . "'";
        $path = "upload/MEMBER/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->DID;
            $row[] = $val->MemberID;
            // $row[] = '<center><img src="' . base_url($path) . $val->IDPhoto . '" width="200" height="100"></center>';
            /*$row[] = $val->MemberLevel;*/
            $row[] = $val->CountryPrefixNo . ' ' . $val->MobilePhoneNo;
            $row[] = $val->Email;
            // $row[] = $val->JoinDate;
            // $row[] = $val->LastLogin;
            // $row[] = $val->OTP;
            // $row[] = $val->OTPExpired;
            $row[] = $val->TotalPoint;
            // $row[] = $val->FullName;
            // $row[] = $val->Gender;
            // $row[] = $val->Address1;
            // $row[] = $val->Address2;
            $row[] = $val->Active != 1 ? '<center>' . Buttons("disabled", "myActive($val->DID, 1, $urlAc)") . '</center>' :
                '<center>' . Buttons("actived", "myActive($val->DID, 0, $urlAc)") . '</center>';

            if (privilage($this->user, "member", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/Member/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "member", "MDelete")) {
                $bt_delete =  Buttons("delete", "myDelete($val->MemberID, $urlDl)");
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

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_member", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/Member/active');
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
        //pre($_POST);
        if ($this->input->post("memberid") != "") {

             $this->db->trans_begin();

            /* START DELETE MEMBER */
            $this->db->where("MemberID", $this->input->post("memberid"));
            $this->db->delete("ms_member");
            /* END DELETE MEMBER */

            /* START SAVE LOG */
            $sql         = $this->db->last_query();
            $page         = base_url("backend/Member/delete");
            tr_log($sql, $page, $this->user);
            /* END SAVE LOG */

            /* START MEMBER IN TABEL TR_POINT */
            $this->db->select("MemberID");
            $this->db->from("tr_point");
            $this->db->where("MemberID",$this->input->post("memberid"));
            $cekdata = $this->db->get()->row();
            /* END MEMBER IN TABEL TR_POINT */
           
            /* START DELETE MEMBER in TR POINT */
            if($cekdata){
                $this->db->where("MemberID", $this->input->post("memberid"));
                $this->db->delete("tr_point");
            }
            /* END DELETE MEMBER in TR POINT */

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $res = array(
                    "status" => false,
                );
                echo json_encode($res);
                die;
            } else {
                $this->db->trans_commit();
                $res = array(
                    "status" => true
                );
                echo json_encode($res);
                die;
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
        /* DATA ACTIVE */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "MEMBER");
        $query = $this->db->get();

        $active = $query->result();

        /* DATA MEMBER LEVEL */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "MEMBERLEVEL");
        $query = $this->db->get();

        $level = $query->result();

        /* DATA GENDER */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "GENDER");
        $query = $this->db->get();

        $gender = $query->result();

        /* DATA NID TYPE */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "NID");
        $query = $this->db->get();

        $nid = $query->result();

        /* DATA RELIGION */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "RELIGION");
        $query = $this->db->get();

        $religion = $query->result();

        /* DATA WORKFIELD */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "WORK_FIELD");
        $query = $this->db->get();

        $workfield = $query->result();

        /* DATA PROVINCE */

        $this->db->select("*");
        $this->db->from("ms_province");
        $this->db->order_by("DID","ASC");
        $query = $this->db->get();

        $province = $query->result_array();


        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Member Form';
        $data_content['breadcrumb']   = 'List Member';
        $data_content['breadcrumb1']  = 'Member Form';
        $data_content['data_tabel']   = 'Member Form';
        $data_content['active']       = $active;
        $data_content['level']        = $level;
        $data_content['gender']       = $gender;
        $data_content['nid']          = $nid;
        $data_content['religion']     = $religion;
        $data_content['workfield']    = $workfield;
        $data_content['province']     = $province;

        $config["content_file"] = "Member/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function selectState()
    {

        $id=$this->input->post('id');

        $state="<option disable>Select State</pilih>";

        $this->db->order_by('DID','ASC');
        $sta= $this->db->get_where('ms_state',array('ProvinceID'=>$id));

        foreach ($sta->result_array() as $data ){
        $state.= "<option value='$data[DID]'>$data[State]</option>";
        }

        echo  $state;
    }

    public function add()
    {
       // pre($_POST);
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('NIDType', 'NID Type', 'required');
        $this->form_validation->set_rules('NIDNo', 'NID No', 'required');
        $this->form_validation->set_rules('MemberLevel', 'Member Level', 'required');
        $this->form_validation->set_rules('CountryPrefixNo', 'Country Prefix', 'required');
        $this->form_validation->set_rules('MobilePhoneNo', 'Mobile Phone', 'required');
        $this->form_validation->set_rules('Email', 'Email', 'required|is_unique[ms_member.Email]|valid_email');
        $this->form_validation->set_rules('Password', 'Password', 'required');
        $this->form_validation->set_rules('JoinDate', 'Join Date', 'required');
        $this->form_validation->set_rules('FirstName', 'First Name', 'required');
        $this->form_validation->set_rules('Gender', 'Gender', 'required');
        $this->form_validation->set_rules('BirthPlace', 'Birth Place', 'required');
        $this->form_validation->set_rules('BirthDate', 'Birth Date', 'required');
        $this->form_validation->set_rules('Active', 'Active', 'required');

        if ($_FILES['IDPhoto']['tmp_name'] == "") {
            $this->form_validation->set_rules('IDPhoto', 'NID Photo', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error' => $errors]);
        } else {

            /*  $user_login = $this->session->userdata('user');
            $user_email = $this->db->query("SELECT Email FROM ms_login WHERE DID = $user_login")->row()->Email;*/

            /* CEK MEMBER ID */

            date_default_timezone_set('Asia/Jakarta');
            $year = date("Y");

            $query = $this->db->query(" SELECT MemberID , Email
                                        FROM ms_member 
                                        WHERE MID(MemberID , 5, 4 ) = $year 
                                        ORDER BY MemberID DESC LIMIT 1");



            if ($query === false)
                throw new Exception();

            $member = $query->row();
            $memberid = 0;
            if (strtolower($this->input->post("MemberLevel")) == "reguler") {
                if ($query->num_rows() < 1) {
                    $seq_number = "00000001";
                } else {
                    $get_seq_number = (int) (substr($member->MemberID, 8, 8) + 1);
                    $seq_number = str_pad($get_seq_number, 8, "0", STR_PAD_LEFT);
                }
                $memberid = (int) 8888 . $year . $seq_number;
            } else {
                pre("Error...");
            }

            $Password = $this->input->post('Password');
            $insert["MemberID"]              = $memberid;
            $insert["MemberLevel"]           = $this->input->post("MemberLevel");
            $insert["NIDType"]               = $this->input->post("NIDType");
            $insert["NIDNo"]                 = $this->input->post("NIDNo");
            $insert["CountryPrefixNo"]       = $this->input->post("CountryPrefixNo");
            $insert["MobilePhoneNo"]         = $this->input->post("MobilePhoneNo");
            $insert["PhoneNo"]               = $this->input->post("PhoneNo");
            $insert["Email"]                 = $this->input->post("Email");
            $insert["Password"]              = $this->bcrypt->hash_password($Password);
            $insert["JoinDate"]              = $this->input->post("JoinDate");
            $insert["FirstName"]             = $this->input->post("FirstName");
            $insert["LastName"]              = $this->input->post("LastName");
            $insert["FullName"]              = $this->input->post("FirstName") . ' ' . $this->input->post("LastName");
            $insert["Gender"]                = $this->input->post("Gender");
            $insert["BirthPlace"]            = $this->input->post("BirthPlace");
            $insert["BirthDate"]             = $this->input->post("BirthDate");
            $insert["Address1"]              = $this->input->post("Address1");
            $insert["Address2"]              = $this->input->post("Address2");
            $insert["ReligionID"]            = $this->input->post("ReligionID");
            $insert["WorkFieldID"]           = $this->input->post("WorkFieldID");
            $insert["ProvinceID"]            = $this->input->post("Province");
            $insert["StateID"]               = $this->input->post("State");
            $insert["District"]              = $this->input->post("District");
            $insert["SubDistrict"]           = $this->input->post("SubDistrict");
            $insert["ZipCode"]               = $this->input->post("ZipCode");
            $insert["Active"]                = $this->input->post("Active");
            $insert["SBT"]                   = date("Y-m-d H:i:s");

            $filename         = isset($_FILES['IDPhoto']['name']) ? $_FILES['IDPhoto']['name'] : NULL;
            $info             = pathinfo($filename);
            $image_name       = url_title(basename($filename, '.' . $info['extension']));
            $random           = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
            $image_filename   = $image_name . '_' . $random . '.' . $info['extension'];
            if (!file_exists(IMAGE_BLOCK_APPS_ROOT_MEMBER . $image_filename)) {
                $image_filename = $image_filename;
            }

            $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_MEMBER;
            $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
            $configImage['max_size']        = 500;
            $configImage['file_name']       = $image_filename;
            $configImage['overwrite']       = FALSE;


            $this->load->library('upload', $configImage);
            $this->upload->initialize($configImage);

            if (!$this->upload->do_upload("IDPhoto")) {
                $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["IDPhoto"]['name'] . ", " . $this->upload->display_errors('', ''));
                redirect('backend/Member/form');
            }

            $file   = IMAGE_BLOCK_APPS_ROOT_MEMBER . $image_filename;

            $NewImageWidth          = 400;
            $NewImageHeight         = 400;
            $Quality                = 50;

            resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);


            $insert["IDPhoto"] =  $image_filename;

            $this->db->save_queries = TRUE;
            $this->db->insert("ms_member", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/Member/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM ms_member ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success' => 'Berhasil']);

                if ($this->input->post('save') == "savereturn") {

                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/Member');
                }
            } else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/Member/form');
            }
        }
    }


    public function form_edit($id = null)
    {

        /* DATA ACTIVE */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "MEMBER");
        $query = $this->db->get();

        $active = $query->result();

        /* DATA MEMBER LEVEL */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "MEMBERLEVEL");
        $query = $this->db->get();

        $level = $query->result();

        /* DATA GENDER */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "GENDER");
        $query = $this->db->get();

        $gender = $query->result();

                /* DATA NID TYPE */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "NID");
        $query = $this->db->get();

        $nid = $query->result();

        /* DATA RELIGION */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "RELIGION");
        $query = $this->db->get();

        $religion = $query->result();

        /* DATA WORKFIELD */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "WORK_FIELD");
        $query = $this->db->get();

        $workfield = $query->result();

        /* DATA PROVINCE */

        $this->db->select("*");
        $this->db->from("ms_province");
        $this->db->order_by("DID","ASC");
        $query = $this->db->get();

        $province = $query->result_array();


        /*GET DATA */
        $this->db->select("*");
        $this->db->from("ms_member");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();

        if (!$result) {
            $this->session->set_flashdata("error_msg", "Empty ID");
            redirect('backend/Member');
        }

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 1;
        $data_content['title']        = 'General Form';
        $data_content['breadcrumb']   = 'List Member';
        $data_content['breadcrumb1']  = 'Member Form Update';
        $data_content['data_tabel']   = 'Member Form Update';
        $data_content['all_data']     = $result;
        $data_content['active']       = $active;
        $data_content['level']        = $level;
        $data_content['gender']       = $gender;
        $data_content['nid']          = $nid;
        $data_content['religion']     = $religion;
        $data_content['workfield']    = $workfield;
        $data_content['province']     = $province;

        //pre($data_content);
        $config["content_file"] = "Member/form";
        $config["content_data"] = $data_content;


        $this->template->initialize($config);
        $this->template->render();
    }


    public function edit($did = null)
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('NIDType', 'NID Type', 'required');
        $this->form_validation->set_rules('NIDNo', 'NID No', 'required');
        $this->form_validation->set_rules('CountryPrefixNo', 'Country Prefix', 'required');
        $this->form_validation->set_rules('MobilePhoneNo', 'Mobile Phone', 'required');
        $this->form_validation->set_rules('Email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('JoinDate', 'Join Date', 'required');
        $this->form_validation->set_rules('FirstName', 'First Name', 'required');
        $this->form_validation->set_rules('Gender', 'Gender', 'required');
        $this->form_validation->set_rules('BirthPlace', 'Birth Place', 'required');
        $this->form_validation->set_rules('BirthDate', 'Birth Date', 'required');
        $this->form_validation->set_rules('Active', 'Active', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error' => $errors]);
        } else {

            if ($this->input->post()) {
                if ($_FILES['IDPhoto']['name'] == "") :

                    $this->db->select("*");
                    $this->db->from("ms_member");
                    $this->db->where("DID", $did);

                    $result = $this->db->get()->row();

                    $update['IDPhoto'] = $result->IDPhoto;

                else :

                    $filename         = isset($_FILES['IDPhoto']['name']) ? $_FILES['IDPhoto']['name'] : NULL;
                    $info             = pathinfo($filename);
                    $image_name     = url_title(basename($filename, '.' . $info['extension']));
                    $random         = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
                    $image_filename = $image_name . '_' . $random . '.' . $info['extension'];

                    if (!file_exists(IMAGE_BLOCK_APPS_ROOT_MEMBER . $image_filename)) {
                        $image_filename = $image_filename;
                    }

                    $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_MEMBER;
                    $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
                    $configImage['max_size']        = 500;
                    $configImage['file_name']       = $image_filename;
                    $configImage['overwrite']       = FALSE;


                    $this->load->library('upload', $configImage);
                    $this->upload->initialize($configImage);


                    if (!$this->upload->do_upload("IDPhoto")) {
                        $this->session->set_flashdata('error_msg', "Failed to upload, " . $_FILES["IDPhoto"]['name'] . ", " . $this->upload->display_errors('', ''));
                        redirect('backend/Member/form');
                    }


                    $file   = IMAGE_BLOCK_APPS_ROOT_MEMBER . $image_filename;

                    $NewImageWidth          = 400;
                    $NewImageHeight         = 400;
                    $Quality                = 50;

                    resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);

                    // $path = IMAGE_BLOCK_APPS_ROOT_MEMBER. $image_filename;;
                    // $type = pathinfo($path, PATHINFO_EXTENSION);
                    // $dataimg = file_get_contents($path);
                    // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
                    $update["IDPhoto"] =  $image_filename;

                endif;

                $Password = $this->input->post('Password');
                if ($Password != '') {
                    $update["Password"]              = $this->bcrypt->hash_password($Password);
                }

                
                $update["NIDType"]               = $this->input->post("NIDType");
                $update["NIDNo"]                 = $this->input->post("NIDNo");
                $update["CountryPrefixNo"]       = $this->input->post("CountryPrefixNo");
                $update["MobilePhoneNo"]         = $this->input->post("MobilePhoneNo");
                $update["PhoneNo"]               = $this->input->post("PhoneNo");
                $update["Email"]                 = $this->input->post("Email");
                $update["JoinDate"]              = $this->input->post("JoinDate");
                $update["FirstName"]             = $this->input->post("FirstName");
                $update["LastName"]              = $this->input->post("LastName");
                $update["FullName"]              = $this->input->post("FirstName") . ' ' . $this->input->post("LastName");
                $update["Gender"]                = $this->input->post("Gender");
                $update["BirthPlace"]            = $this->input->post("BirthPlace");
                $update["BirthDate"]             = $this->input->post("BirthDate");
                $update["Address1"]              = $this->input->post("Address1");
                $update["Address2"]              = $this->input->post("Address2");
                $update["ReligionID"]            = $this->input->post("ReligionID");
                $update["WorkFieldID"]           = $this->input->post("WorkFieldID");
                $update["ProvinceID"]            = $this->input->post("Province");
                $update["StateID"]               = $this->input->post("State");
                $update["District"]              = $this->input->post("District");
                $update["SubDistrict"]           = $this->input->post("SubDistrict");
                $update["ZipCode"]               = $this->input->post("ZipCode");
                $update["Active"]                = $this->input->post("Active");
                $update["SBT"]                   = date("Y-m-d H:i:s");


                $this->db->save_queries = TRUE;
                $this->db->where("DID", $did);
                $this->db->update("ms_member", $update);
                $sql         = $this->db->last_query();
                $page         = base_url('backend/Member/edit');
                tr_log($sql, $page, $this->user);

                echo json_encode(['success' => 'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/Member');
                }
            }
        }
    }

    public function detail()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
       // pre("ok");
        $path = "upload/MEMBER/";
        $this->db->select("*");
        $this->db->from("ms_member");
        $this->db->where("ms_member.DID", $this->input->post("did"));
        $this->db->join("ms_province", "ms_province.DID = ms_member.ProvinceID ","LEFT");
        $this->db->join("ms_state", "ms_state.DID = ms_member.StateID ","LEFT");
        $list = $this->db->get()->row();
        
        /*NID*/
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "NID");
        $this->db->where("ParamID", $list->NIDType);
        $nid = isset($this->db->get()->row()->ParamValue);
       

        /*RELIGION*/
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "RELIGION");
        $this->db->where("ParamID", $list->ReligionID);
        $religion = isset($this->db->get()->row()->ParamValue);
        
        /*WORKFIELD*/
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "WORK_FIELD");
        $this->db->where("ParamID", $list->WorkFieldID);  
        $workfield = isset($this->db->get()->row()->ParamValue);

        $row1 = '<div class="card">
                <div class="card-header">
                <h3 class="card-title">Detail Member</h3>
                <div class="card-tools">
                </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="' . base_url($path) . $list->IDPhoto . '" width="200" height="100">
                        </div>
                        <div class="col-md-10">
                        <table class="detail">
                            <tbody>
                                <tr style="background: none;">
                                    <td valign="top">Member ID</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $list->MemberID.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Member Level</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->MemberLevel.'</td>
                                </tr>
                                <tr style="background: none;">
                                    <td valign="top">NID Type</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $nid.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">NID No</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->NIDNo.'</td>
                                </tr>  
                                <tr style="background: none;">
                                    <td valign="top">Mobile Phone</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $list->CountryPrefixNo.' '.$list->MobilePhoneNo.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Phone No</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->PhoneNo.'</td>
                                </tr>   
                        
                                <tr style="background: none;">
                                    <td valign="top">Email</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $list->Email.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Join Date</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->JoinDate.'</td>
                                </tr>  
                                <tr style="background: none;">
                                    <td valign="top">First Name</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'. $list->FirstName.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Last Name</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->LastName.'</td>
                                </tr>  
                                <tr style="background: none;">
                                    <td valign="top">Full Name</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->FullName.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Gender</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->Gender.'</td>
                                 </tr>
                                 <tr style="background: none;">
                                    <td valign="top">Birth Place</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->BirthPlace.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">Birth Place</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->BirthDate.'</td>
                                 </tr>  
                                <tr style="background: none;">
                                    <td valign="top">Religion</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$religion.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">WorkField</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$workfield.'</td>
                                 </tr>
                                 <tr style="background: none;">
                                    <td valign="top">Province</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->Province.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">StateID</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->State.'</td>
                                 </tr>
                                 <tr style="background: none;">
                                    <td valign="top">District</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->District.'</td>

                                    <!--   PEMBATAS  -->

                                    <td valign="top">SubDistrict</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->SubDistrict.'</td>
                                 </tr>
                                 <tr style="background: none;">
                                    <td valign="top">ZipCode</td>
                                    <td valign="top">:</td>
                                    <td valign="top">'.$list->ZipCode.'</td>
                                 </tr>      
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>';

        if ($list) {
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
