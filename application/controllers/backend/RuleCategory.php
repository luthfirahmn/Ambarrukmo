<?php defined('BASEPATH') or exit('No direct script access allowed');

class RuleCategory extends CI_Controller
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
        if (privilage($this->user, "ruleCategory", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Rule Category';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Rule Category';
        $data_content['data_tabel']   = 'Tabel Header';
        $data_content['data_tabel2']   = 'Tabel Detail';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "RuleCategory/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("rule_category");
        $this->db->where("(RuleTitle LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or StartDate LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        /* ORDER */
        $order = array('StartDate' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
         if(empty($order))
        {
            $this->db->order_by("StartDate", "DESC");
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
            1=>'RuleTitle',
            2=>'StartDate',
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
        $this->db->from("rule_category");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("rule_category");
        $this->db->where("(RuleTitle LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or StartDate LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/RuleCategory/delete") . "'";
        $path = "upload/VOUCHER/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->RuleTitle;
            $row[] = date("d-M-Y H:i:s", strtotime($val->StartDate));
            /* $row[] = substr($val->VoucherNote, 0, 20) . " .....";*/
            if (privilage($this->user, "ruleCategory", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/RuleCategory/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "ruleCategory", "MDelete")) {
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

    
   
       public function delete()
    {
        if ($this->input->post("DID") != "") {

            $this->db->save_queries = TRUE;
            $this->db->where('DID', $this->input->post("DID"));
            $this->db->delete('rule_category');

            $this->db->where('PDID', $this->input->post("DID"));
            $this->db->delete('rule_category_detail');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/rule_category/delete');
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

        /*GET CATEGORY*/
        $this->db->select("*");
        $this->db->from("rule_category");
        $this->db->order_by('StartDate',"DESC");
        $data = $this->db->get();



        // if ($id == "add") {
        //     if (!is_allowed($this->session->userdata('user'), 'manu', 'add')) :
        //         $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
        //         redirect('backend/voucher');
        //     endif;
        // }

        /* DATA STATUS */

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Header Rule Form';
        $data_content['breadcrumb']   = 'List Header Rule';
        $data_content['breadcrumb1']  = 'Header Rule Form';
        $data_content['data_tabel']   = 'Header Rule Form';
        $data_content['list']         = $data->result();
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "RuleCategory/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }



     public function add()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('RuleTitle','Rule Tittle', 'required');
        $this->form_validation->set_rules('StartDate','Start Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {
        	$insert["RuleTitle"]           = $this->input->post("RuleTitle");
            $insert["StartDate"]            = $this->input->post("StartDate");
           

            $this->db->save_queries = TRUE;
            $this->db->insert("rule_category", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/RuleCategory/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM rule_category ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/RuleCategory');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/RuleCategory/form');
            }
        
        }
    }

     

     public function form_edit($id = null)
    {

        $this->db->select("*");
        $this->db->from("rule_category");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();


        /*DETAIL*/
        $this->db->select("*");
        $this->db->from("rule_category_detail");
        $this->db->where("PDID", $id);

        $detailquery = $this->db->get();
        $resultdetail = $detailquery->result();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/RuleCategory');
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
        $data_content['breadcrumb']   = 'List Header Rule';
        $data_content['breadcrumb1']  = 'Header Rule Form Update';
        $data_content['data_tabel']   = 'Header Rule Form Update';
        $data_content['all_data']     = $result;
        $data_content['detail']         = $resultdetail;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "RuleCategory/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function edit($did = null)
    {   

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('RuleTitle','Rule Tittle', 'required');
        $this->form_validation->set_rules('StartDate','Start Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
          

            $update["RuleTitle"]           = $this->input->post("RuleTitle");
            $update["StartDate"]            = $this->input->post("StartDate");


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $did);
            $this->db->update("rule_category", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/RuleCategory/edit');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);

            if ($this->input->post('save') == "savereturn") {
                $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/RuleCategory');
            } 
            }

        }
    }


    /*====================TABEL DETAIL================================*/

     public function get_list2()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("rule_category as category");
        $this->db->join("rule_category_detail as detail", "detail.PDID = category.DID");
        $this->db->where("(RuleTitle LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or StartDate LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or PointRatio LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or EventMultiply LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        /* ORDER */
        $order = array('StartDate' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
         if(empty($order))
        {
            $this->db->order_by("StartDate", "DESC");
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
            0=>'category.DID',
            1=>'RuleTitle',
            2=>'StartDate',
            3=>'RuleCategory',
            4=>'PointRatio',
            6=>'EventMultiply',
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
        $this->db->from("rule_category");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("rule_category as category");
        $this->db->join("rule_category_detail as detail", "detail.PDID = category.DID");
        $this->db->where("(RuleTitle LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or StartDate LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or PointRatio LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or EventMultiply LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/RuleCategory/delete2") . "'";
        $path = "upload/VOUCHER/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->RuleTitle;
            $row[] = date("d-M-Y H:i:s", strtotime($val->StartDate));
            $row[] = $val->RuleCategory;
            $row[] = number_format($val->PointRatio,2,",",".");
            $row[] = $val->EventMultiply;
            /* $row[] = substr($val->VoucherNote, 0, 20) . " .....";*/
            $row[] = '<center>' . Buttons("edit", "location.href='" . base_url("/backend/RuleCategory/form_edit2/" . $val->DID . "") . "'") . " " . Buttons("delete", "myDelete2($val->DID, $urlDl)") . '</center>';

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

    public function form2($id = null)
    {

        /*GET CATEGORY*/
        $this->db->select("*,detail.DID as DIDdetail");
        $this->db->from("rule_category as category");
        $this->db->join("rule_category_detail as detail", "detail.PDID = category.DID" );
        $query = $this->db->get();
        $result = $query->row();

        $this->db->select("*");
        $this->db->from("rule_category");
        $this->db->order_by('StartDate',"DESC");
        $data = $this->db->get();



        // if ($id == "add") {
        //     if (!is_allowed($this->session->userdata('user'), 'manu', 'add')) :
        //         $this->session->set_flashdata("error_msg", "You're not allowed to access the 'Add Access' page.");
        //         redirect('backend/voucher');
        //     endif;
        // }

        /* DATA STATUS */

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Detail Rule Form';
        $data_content['breadcrumb']   = 'List Detail Rule';
        $data_content['breadcrumb1']  = 'Detail Rule Form';
        $data_content['data_tabel']   = 'Detail Rule Form';
        $data_content['list']         = $data->result();
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "RuleCategory/form_detail";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }



     public function add2()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('RuleTitle','Rule Tittle', 'required');
        $this->form_validation->set_rules('RuleCategory','Rule Category', 'required');
        $this->form_validation->set_rules('PointRatio','Point Ratio', 'required');
        $this->form_validation->set_rules('EventMultiply','Event Multiply', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {
            $insert["PDID"]                 = $this->input->post("RuleTitle");
            $insert["RuleCategory"]         = $this->input->post("RuleCategory");
            $insert["PointRatio"]           = $this->input->post("PointRatio");
            $insert["EventMultiply"]        = $this->input->post("EventMultiply");
           

            $this->db->save_queries = TRUE;
            $this->db->insert("rule_category_detail", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/RuleCategory/add2');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM rule_category_detail ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/RuleCategory');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/RuleCategory/form');
            }
        
        }
    }


         public function form_edit2($id = null)
    {

        $this->db->select("*,detail.DID as DIDdetail");
        $this->db->from("rule_category as category");
        $this->db->join("rule_category_detail as detail", "detail.PDID = category.DID" );
        $this->db->where("detail.DID", $id);

        $query = $this->db->get();
        $result = $query->row();



        /*HEADER*/
        $this->db->select("*,detail.DID as DIDdetail");
        $this->db->from("rule_category as category");
        $this->db->join("rule_category_detail as detail", "detail.PDID = category.DID" );
        $this->db->where("detail.DID", $id);
        $headerquery = $this->db->get();
        $resultheader = $headerquery->result();

        /*GET CATEGORY*/
        $this->db->select("*");
        $this->db->from("rule_category");
        $this->db->order_by('StartDate',"DESC");
        $data = $this->db->get();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/RuleCategory');
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
        $data_content['breadcrumb']   = 'List Detail Rule';
        $data_content['breadcrumb1']  = 'Detail Rule Form Update';
        $data_content['data_tabel']   = 'Detail Rule Form Update';
        $data_content['all_data']     = $result;
        $data_content['list']         = $data->result();
        $data_content['header']       = $resultheader;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "RuleCategory/form_detail";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function edit2($did = null)
    {   

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('RuleTitle','Rule Tittle', 'required');
        $this->form_validation->set_rules('RuleCategory','Role Category', 'required');
        $this->form_validation->set_rules('PointRatio','Point Ratio', 'required');
        $this->form_validation->set_rules('EventMultiply','Event Multiply', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
          

            $update["PDID"]                  = $this->input->post("RuleTitle");
            $update["RuleCategory"]          = $this->input->post("RuleCategory");
            $update["PointRatio"]            = $this->input->post("PointRatio");
            $update["EventMultiply"]          = $this->input->post("EventMultiply");


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $did);
            $this->db->update("rule_category_detail", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/RuleCategory/edit2');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);

            if ($this->input->post('save') == "savereturn") {
                $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/RuleCategory');
            } 
            }

        }
    }

     public function delete2()
    {
        if ($this->input->post("DID") != "") {

            $this->db->save_queries = TRUE;
            $this->db->where('DID', $this->input->post("DID"));
            $this->db->delete('rule_category_detail');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/rule_category/delete2');
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





}