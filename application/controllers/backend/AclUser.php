<?php defined('BASEPATH') or exit('No direct script access allowed');

class AclUser extends CI_Controller
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



         /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'ACL_USER_GROUP'");
        $all_data = $this->db->get()->result();


         /* SELECT LOGIN USER */
        /*$user = $this->db->query("
            SELECT *
            FROM ms_login as table1
            LEFT JOIN ms_employee as table2 ON table2.DID = table1.EmpID
            WHERE NOT EXISTS (
            SELECT *
            FROM ms_acl_user_group as table3
            WHERE table3.Email = table1.Email
            )
            ")->result();*/
        $Email = $this->db->query("SELECT Email FROM ms_acl_user_group" )->result();
        
        $this->db->select('*');
        $this->db->from('ms_login as login');
        /*->join('ms_employee as emp','emp.DID = login.EmpID','LEFT')*/
        foreach ($Email as $row) :
        $EmailResult = $row->Email;

        $this->db->where('login.Email !=',$EmailResult);
        endforeach;
        $this->db->where('login.Active ','1');

      

        $user = $this->db->get()->result();

         /* SELECTED DATA */
        $this->db->select("*");
        $this->db->from("ms_acl_user_group");
        $selected = $this->db->get()->result();



        /** DATA SEND TO VIEW */
        $data_content                 = array();
        $data_content['title']        = 'Acl User';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Acl User';
        $data_content['data_tabel']   = 'Acl User';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['all_data']     = $all_data;
        $data_content['user']         = $user;
        $data_content['selected']     = $selected;

        $config["content_file"] = "AclUser/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    function GetAclUser(){

        $Group=$this->input->post('Group');
        $this->db->select('*');
        $this->db->from('ms_acl_user_group');
        $this->db->where('ACLGroup',$Group);
        $data = $this->db->get()->result();
        echo json_encode($data);
    }


    /*public function SelectedProcess(){
        
            $EmpID = $this->input->post('EmpID');
            $DID = $this->input->post('DID');
            $SelectGroup = $this->input->post('SelectGroup');

           for($count = 0; $count < count($EmpID); $count++) {
                $EmpIDCount = $EmpID[$count];
                $EmpEmail = $this->db->query("SELECT Email FROM ms_login where EmpID = $EmpIDCount" )->row()->Email;
                 $data  = array(
                'Email' => $EmpEmail,
                'ACLGroup' => $SelectGroup
                );
 
                $this->db->where('Email', $EmpEmail);
                $q = $this->db->get('ms_acl_user_group');
                $this->db->reset_query();
                    
                if ( $q->num_rows() > 0 ) 
                {
                    $this->db->where('Email', $EmpEmail)->update('ms_acl_user_group', $data);
                    $this->db->query("DELETE FROM ms_acl_user_group WHERE Email IS NULL");
                } else {
                    $this->db->set('Email', $EmpEmail)->insert('ms_acl_user_group', $data);
                      $this->db->query("DELETE FROM ms_acl_user_group WHERE Email IS NULL");
                }

          }  
           for($count = 0; $count < count($DID); $count++) {
                $DIDCount = $DID[$count];
                $AclDID = $this->db->query("SELECT DID FROM ms_acl_user_group where DID = $DIDCount" )->row()->DID;
 
                $this->db->where('DID', $AclDID)->Delete('ms_acl_user_group');

          } 
      }*/

      public function SelectedProcess(){
        
            $LogDID = $this->input->post('LogDID');
            $DID = $this->input->post('DID');
            $SelectGroup = $this->input->post('SelectGroup');

           for($count = 0; $count < count($LogDID); $count++) {
                $LogDIDCount = $LogDID[$count];
                $LogEmail = $this->db->query("SELECT Email FROM ms_login where DID = $LogDIDCount" )->row()->Email;
                 $data  = array(
                'Email' => $LogEmail,
                'ACLGroup' => $SelectGroup
                );
 
                $this->db->where('Email', $LogEmail);
                $q = $this->db->get('ms_acl_user_group');
                $this->db->reset_query();
                    
                if ( $q->num_rows() > 0 ) 
                {
                    $this->db->where('Email', $LogEmail)->update('ms_acl_user_group', $data);
                    $this->db->query("DELETE FROM ms_acl_user_group WHERE Email IS NULL");
                } else {
                    $this->db->set('Email', $LogEmail)->insert('ms_acl_user_group', $data);
                      $this->db->query("DELETE FROM ms_acl_user_group WHERE Email IS NULL");
                }

          }  
           for($count = 0; $count < count($DID); $count++) {
                $DIDCount = $DID[$count];
                $AclDID = $this->db->query("SELECT DID FROM ms_acl_user_group where DID = $DIDCount" )->row()->DID;
 
                $this->db->where('DID', $AclDID)->Delete('ms_acl_user_group');

          } 
      }

}