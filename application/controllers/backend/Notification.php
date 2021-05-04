<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/login');
        }
        $this->user = $this->session->userdata('user');
        date_default_timezone_set('Asia/Jakarta');
    }

     public function index()
    {
           /** DATA SEND TO VIEW */
        if (privilage($this->user, "notification", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Notification';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Notification';
        $data_content['data_tabel']   = 'Data Notification';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "Notification/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_notification");
        $this->db->where("(NotifType LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or Message LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or SendTime LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        /* ORDER */
        $order = array('DID' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
         if(empty($order))
        {
            $this->db->order_by("Message", "DESC");
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
            1=>'NotifType',
            2=>'Message',
            3=>'SendTime',
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
        $this->db->from("tr_notification");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_notification");
        $this->db->where("(NotifType LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or Message LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or SendTime LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/Notification/delete") . "'";
        $urls = "'" . base_url("backend/Notification/status") . "'";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {

            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->NotifType;
            $row[] = substr($val->Message, 0, 40);
            $row[] = date("d-M-Y H:i:s", strtotime($val->SendTime));
            $row[] = $val->SendEmailStatus != 1 ? '<center>' . Buttons("notsendStatus", "myStatus($val->DID, 1, 1, $urls)") . '</center>' :
                '<center>' . Buttons("sendStatus", "myStatus($val->DID, 1, 0, $urls)") . '</center>';
            $row[] = $val->SendSMSStatus != 1 ? '<center>' . Buttons("notsendStatus", "myStatus($val->DID, 2, 1, $urls)") . '</center>' :
                '<center>' . Buttons("sendStatus", "myStatus($val->DID, 2, 0, $urls)") . '</center>';
            $row[] = $val->SendNotifAppStatus != 1 ? '<center>' . Buttons("notsendStatus", "myStatus($val->DID, 3, 1, $urls)") . '</center>' :
                '<center>' . Buttons("sendStatus", "myStatus($val->DID, 3, 0, $urls)") . '</center>';
            if (privilage($this->user, "notification", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/Notification/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "notification", "MDelete")) {
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
            $this->db->delete('tr_notification');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/tr_notification/delete');
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


        /* PARAMETER */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "NOTIF_TYPE");
        $query = $this->db->get();

        $NotifType = $query->result();


        /* DATA STATUS */
        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'Notification Form';
        $data_content['breadcrumb']   = 'List Notification';
        $data_content['breadcrumb1']  = 'Notification Form';
        $data_content['data_tabel']   = 'Notification Form';
        $data_content['NotifType']    = $NotifType;
/*        $data_content['all_data']     = $all_data;*/
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "Notification/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }



     public function add()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('NotifType','Notif Type', 'required');
        $this->form_validation->set_rules('Message','Message', 'required');
        $this->form_validation->set_rules('SendTime','Send Time', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {
        	$insert["NotifType"]           = $this->input->post("NotifType");
            $insert["Message"]             = $this->input->post("Message");
            $insert["SendTime"]            = $this->input->post("SendTime");
           

            $this->db->save_queries = TRUE;
            $this->db->insert("tr_notification", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/Notification/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM tr_notification ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/Notification');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/Notification/form');
            }
        
        }
    }

     public function form_edit($id = null)
    {

        $this->db->select("*");
        $this->db->from("tr_notification");
        $this->db->where("DID", $id);

        $query = $this->db->get();
        $result = $query->row();


        /* PARAMETER */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "NOTIF_TYPE");
        $query = $this->db->get();

        $NotifType = $query->result();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
            redirect('backend/Notification');
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
        $data_content['breadcrumb']   = 'List Notification';
        $data_content['breadcrumb1']  = 'Notification Form Update';
        $data_content['data_tabel']   = 'Notification Form Update';
        $data_content['all_data']     = $result;
        $data_content['NotifType']    = $NotifType;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "Notification/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

     public function edit($did = null)
    {   

        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('NotifType','Notif Type', 'required');
        $this->form_validation->set_rules('Message','Message', 'required');
        $this->form_validation->set_rules('SendTime','Send Time', 'required');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
          

            $update["NotifType"]           = $this->input->post("NotifType");
            $update["Message"]             = $this->input->post("Message");
            $update["SendTime"]            = $this->input->post("SendTime");


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $did);
            $this->db->update("tr_notification", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/Notification/edit');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);
 
            }

        }
    }


    public function status()
    {
        if ($this->input->post("DID") != "") {
            // pre($_POST);
            if ($this->input->post("status") == "1") 
            {
                $data["SendEmailStatus"] = $this->input->post("active");
            }
            else if ($this->input->post("status") == "2")
            {
                $data["SendSMSStatus"] = $this->input->post("active");
            }
            else if ($this->input->post("status") == "3")
            {
                $data["SendNotifAppStatus"] = $this->input->post("active");
            }

            // $data["RBU"]    = $this->user;

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("tr_notification", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/Notification/status');
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