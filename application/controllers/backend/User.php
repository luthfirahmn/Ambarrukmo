<?php defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/login');  
        }
        $this->user = $this->session->userdata('user');
        $this->load->library('bcrypt'); 
    }

     public function index()
    {

         /*SELECT EMPLOYEE*/
       /* $emp = $this->db->query("
            SELECT *
            FROM ms_employee as a
            WHERE NOT EXISTS (
            SELECT *
            FROM ms_login as b
            WHERE b.EmpID = a.EmpID
            )
            ")->result();
        */

        /** DATA SEND TO VIEW */
                   /** DATA SEND TO VIEW */
        if (privilage($this->user, "user", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'User Login';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'User Login';
        $data_content['data_tabel']   = 'DataTable';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        /*$data_content['emp']           = $emp;*/

        $config["content_file"] = "user/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }



    public function get_list()
    {
        /* SELECT DATA ALL */
        

        $sql="  SELECT 
                    emp.DID
                    ,emp.EmpID
                    ,emp.FullName
                    ,login.DID AS LogDID
                    ,login.Email AS LogEmail
                    ,login.Password
                    ,login.LastLogin
                    ,login.SessionExpiry
                    ,login.RememberMe
                    ,login.ResetPass
                    ,login.AdminPanel
                    ,login.AppToken
                    ,login.Active 
                FROM ms_login login
                LEFT JOIN ms_employee emp ON emp.DID = login.EmpID
                
             ";
        $rs = $this->db->query($sql)->result();

        /* ORDER */
        /*
        $order = array('login.DID' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
         if(empty($order))
        {
            $this->db->order_by("login.DID", "DESC");
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
            0=>'login.DID',
            1=>'login.Email',
            2=>'Password',
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
        
        $all_data = $this->db->get()->result();
        */
        $all_data = $rs;


        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_login");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_login as login");
        $this->db->where("(login.Email LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or login.EmpID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();


        $urlAc = "'" . base_url("backend/User/active") . "'";
        $urlAp = "'" . base_url("backend/User/AdminPanel") . "'";
        $urlDl = "'" . base_url("backend/User/delete") . "'";
        $path = "upload/VOUCHER/";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->LogEmail;
            $row[] = $val->FullName; /*. ' <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-lg">
                                          Select Employee
                                        </button>';*/
            $row[] = $val->LastLogin;
            $row[] = $val->SessionExpiry;
            $row[] = $val->RememberMe;
            $row[] = $val->ResetPass;
            $row[] = $val->AdminPanel != 1 ? '<center>' . Buttons("check", "AdminPanel($val->LogDID, 1, $urlAp)") . '</center>' :
                '<center>' . Buttons("checked", "AdminPanel($val->LogDID, 0, $urlAp)") . '</center>';
            $row[] = $val->AppToken;
            $row[] = $val->Active != 1 ? '<center>' . Buttons("disabled", "myActive($val->LogDID, 1, $urlAc)") . '</center>' :
                '<center>' . Buttons("actived", "myActive($val->LogDID, 0, $urlAc)") . '</center>';
            if (privilage($this->user, "user", "MEdit")) {
                $bt_edit =  Buttons("edit", "location.href='" . base_url("/backend/User/form_edit/" . $val->LogDID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "user", "MDelete")) {
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

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_login", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/User/active');
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

    public function AdminPanel()
    {
        if ($this->input->post("DID") != "") {
            // pre($_POST);
            $data["AdminPanel"] = $this->input->post("AdminPanel");

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_login", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/User/AdminPanel');
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
            $this->db->delete('ms_login');

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/User/delete');
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

        /*PARAMETER ACTIVE*/
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "USER");
        $query = $this->db->get();
        $active = $query->result();


        /*SELECT EMPLOYEE*/
        $emp = $this->db->query("
            SELECT *
            FROM ms_employee
            ")->result();

         /*$emp = $this->db->query("
            SELECT *
            FROM ms_employee as a
            WHERE NOT EXISTS (
            SELECT *
            FROM ms_login as b
            WHERE b.EmpID = a.EmpID
            )
            ")->result();*/
        /* DATA STATUS */

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'User Login Form';
        $data_content['breadcrumb']   = 'List User Login';
        $data_content['breadcrumb1']  = 'User Login Form';
        $data_content['data_tabel']   = 'User Login Form';
        $data_content['active']       = $active;
        $data_content['emp']           = $emp;
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "user/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }



     public function add()
    {
        $this->form_validation->set_error_delimiters('', '<br>');
        $this->form_validation->set_rules('Email', 'Email', 'trim|is_unique[ms_login.email]|valid_email|required', 
												array(
													'required' => '{field} Harus Diisi',
													'is_unique' => '{field} Telah Terdaftar',
													'valid_email' => 'Email Tidak Valid'
												));
        $this->form_validation->set_rules('Password','Password', 'trim|required',
    											array(
													'required' => '{field} Harus Diisi',
												));
        $this->form_validation->set_rules('ConfirmPass', 'Confirm Password', 'trim|matches[Password]' , 
        										array(
        											'matches' => 'Password does not match.'
        										));
        $this->form_validation->set_rules('Active', 'Active', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        } 
        else {
            $id= $this->input->post("EmpID");
            if($id != ''){

            $this->db->select('DID');
            $this->db->from('ms_employee');
            $this->db->where('EmpID',$id);
            $query =  $this->db->get();
            $EmpID = $query->row()->DID;

            $insert["EmpID"]                = $EmpID;
            }   
        	$password = $this->input->post("Password",TRUE);
        	$insert["Email"]                = $this->input->post("Email");
        	$insert["Password"]             = $this->bcrypt->hash_password($password);
            $insert["Active"]               = $this->input->post("Active");

            $this->db->save_queries = TRUE;
            $this->db->insert("ms_login", $insert);

            if ($this->db->insert_id() > 1) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/User/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM ms_login ORDER BY DID DESC LIMIT 1")->row()->DID;

                echo json_encode(['success'=>'Berhasil']);

                if ($this->input->post('save') == "savereturn") {
                    
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/User');
                } 
            } 
            else {
                $this->session->set_flashdata("error_msg", "Data Not Insert");
                redirect('backend/User/form');
            }
        
        }
    }

     public function form_edit($id = null)
    {


        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "USER");
        $query = $this->db->get();
        $active = $query->result();

         /*SELECT EMPLOYEE*/
        
        /*SELECT EMPLOYEE*/
       /*SELECT EMPLOYEE*/
        $emp = $this->db->query("
            SELECT *
            FROM ms_employee
            ")->result();


      /*  $emp = $this->db->query("
            SELECT *
            FROM ms_employee as a
            WHERE NOT EXISTS (
            SELECT *
            FROM ms_login as b
            WHERE b.EmpID = a.EmpID
            )
            ")->result();
*/

        /*GET DATA*/
        $this->db->select("*, login.Email as LogEmail, login.DID as LogDID");
        $this->db->from("ms_login as login");
        $this->db->where("login.DID",$id);
        $this->db->join("ms_employee as emp", "emp.DID = login.EmpID","LEFT");
        $query = $this->db->get();
        $result = $query->row();

        if(!$result){
            $this->session->set_flashdata("error_msg", "Empty ID");
                redirect('backend/User');
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
        $data_content['breadcrumb']   = 'List User Login';
        $data_content['breadcrumb1']  = 'User Login Form Update';
        $data_content['data_tabel']   = 'User Login Form Update';
        $data_content['all_data']     = $result;
        $data_content['active']     = $active;
        $data_content['emp']     = $emp;
        //pre($result);
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "user/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function edit($did = null)
    {   


        $CheckEmail = $this->input->post('Email');
        $CheckPass = $this->input->post('Password');
        $this->form_validation->set_error_delimiters('', '<br>');
        $ori_Email = $this->db->query("SELECT Email FROM ms_login WHERE DID = $did ")->row()->Email ;
        if ($CheckEmail != $ori_Email) 
        {
            $this->form_validation->set_rules('Email', 'Email', 'trim|is_unique[ms_login.Email]|valid_email|required|', 
                                            array(
                                                'required' => '{field} Harus Diisi',
                                                'is_unique' => '{field} Telah Terdaftar',
                                                'valid_email' => 'Email Tidak Valid'
                                            ));
        }
        else
        {
            $this->form_validation->set_rules('Email', 'Email', 'trim|valid_email|required', 
                                                array(
                                                    'required' => '{field} Harus Diisi',
                                                    'valid_email' => 'Email Tidak Valid'
                                                ));
        }

        if($CheckPass != ''){
        $this->form_validation->set_rules('Password','Password', 'trim|required',
                                                array(
                                                    'required' => '{field} Harus Diisi',
                                                ));
        $this->form_validation->set_rules('ConfirmPass', 'Confirm Password', 'trim|matches[Password]' , 
                                                array(
                                                    'matches' => 'Password does not match.'
                                                ));
        }

        $this->form_validation->set_rules('Active', 'Active', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error'=>$errors]);
        }
        else {

        if ($this->input->post()) {
          

            $id= $this->input->post("EmpID");
            if($id != ''){

            $this->db->select('DID');
            $this->db->from('ms_employee');
            $this->db->where('EmpID',$id);
            $query =  $this->db->get();
            $EmpID = $query->row()->DID;
            
            $update["EmpID"]                = $EmpID;
            }   
            
        	$password = $this->input->post("Password", true);
            if($password != ""){
                $update["Password"]         = $this->bcrypt->hash_password($password);
            }
        	$update["Email"]            = $this->input->post("Email");
            $update["Active"]           = $this->input->post("Active");


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $did);
            $this->db->update("ms_login", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/User/edit');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);

            if ($this->input->post('save') == "savereturn") {
                $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/User');
            } 
            }

        }
    }

   /* public function UpdateEmployee($EmpID = null)
    {   

       
      
     
            $update["EmpID"]         = $EmpID;


            $this->db->save_queries = TRUE;
            $this->db->where("EmpID", $EmpID);
            $this->db->update("ms_login", $update);
            $sql         = $this->db->last_query();
            $page         = base_url('backend/User/edit');
            tr_log($sql, $page, $this->user);

            echo json_encode(['success'=>'Berhasil']);

    }*/

}