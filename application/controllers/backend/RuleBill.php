<?php defined('BASEPATH') or exit('No direct script access allowed');

class RuleBill extends CI_Controller
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
        if (privilage($this->user, "ruleBill", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Rule Bill';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Rule Bill';
        $data_content['data_tabel']   = 'Data Rule Bill';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "RuleBill/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("rule_bill");
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
            0=>'DID',
            1=>'RuleTitle',
            2=>'StartDate',
            3=>'PointRatio',
            4=>'EventMultiply',
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
        $this->db->from("rule_bill");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("rule_bill");
        $this->db->where("(RuleTitle LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or StartDate LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or PointRatio LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or EventMultiply LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/RuleBill/delete") . "'";
        $path = "upload/VOUCHER/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {

            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->RuleTitle;
            $row[] = date("d-M-Y H:i:s", strtotime($val->StartDate));
            $row[] = number_format($val->PointRatio,2,",",".");
            $row[] = $val->EventMultiply;
            /* $row[] = substr($val->VoucherNote, 0, 20) . " .....";*/
            if (privilage($this->user, "ruleBill", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/RuleBill/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "ruleBill", "MDelete")) {
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
            $this->db->delete('rule_bill');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/rule_bill/delete');
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
        //         redirect('backend/voucher');
        //     endif;
        // }

        /* DATA STATUS */

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Rule Bill Form';
        $data_content['breadcrumb']   = 'List Rule Bill';
        $data_content['breadcrumb1']  = 'Rule Bill Form';
        $data_content['data_tabel']   = 'Rule Bill Form';
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "RuleBill/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }



     public function add()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('RuleTitle','Rule Tittle', 'required');
        $this->form_validation->set_rules('StartDate','Start Date', 'required');
        $this->form_validation->set_rules('PointRatio','Point Ratio', 'required');
        $this->form_validation->set_rules('EventMultiply','Event Multiply', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {
        	$insert["RuleTitle"]           = $this->input->post("RuleTitle");
            $insert["StartDate"]            = $this->input->post("StartDate");
            $insert["PointRatio"]           = $this->input->post("PointRatio");
            $insert["EventMultiply"]        = $this->input->post("EventMultiply");
           

            $this->db->save_queries = TRUE;
            $this->db->insert("rule_bill", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/RuleBill/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM rule_bill ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/RuleBill');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/RuleBill/form');
            }
        
        }
    }

     public function form_edit($id = null)
    {

        $this->db->select("*");
        $this->db->from("rule_bill");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/RuleBill');
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
        $data_content['breadcrumb']   = 'List Rule Bill';
        $data_content['breadcrumb1']  = 'Rule Bill Form Update';
        $data_content['data_tabel']   = 'Rule Bill Form Update';
        $data_content['all_data']     = $result;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "RuleBill/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function edit($did = null)
    {   

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('RuleTitle','Rule Tittle', 'required');
        $this->form_validation->set_rules('StartDate','Start Date', 'required');
        $this->form_validation->set_rules('PointRatio','Point Ratio', 'required');
        $this->form_validation->set_rules('EventMultiply','Event Multiply', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
          

            $update["RuleTitle"]           = $this->input->post("RuleTitle");
            $update["StartDate"]            = $this->input->post("StartDate");
            $update["PointRatio"]           = $this->input->post("PointRatio");
            $update["EventMultiply"]        = $this->input->post("EventMultiply");


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $did);
            $this->db->update("rule_bill", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/RuleBill/edit');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);

            if ($this->input->post('save') == "savereturn") {
                $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/RuleBill');
            } 
            }

        }
    }



}