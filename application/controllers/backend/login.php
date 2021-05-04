<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library('bcrypt');
  }

  public function index()
  {
    $data_content['success_msg']  = $this->session->flashdata("success_msg");
    $data_content['error_msg']    = $this->session->flashdata("error_msg");
    $data_content['info_msg']     = $this->session->flashdata("info_msg");
    
    $this->load->view('ms_login/form_login',$data_content);
  }

  public function sign_in()
  {
    try {
      $data = $this->input->post();
      $email = $data['data']['email'];
      $password = $data['data']['password'];

      if (!$email == '') 
      {
        $query = $this->db->query(" SELECT ml.DID, ml.Email, ml.Password, maug.ACLGroup
                                          FROM ms_login ml
                                          LEFT JOIN ms_acl_user_group maug on maug.Email = ml.Email
                                          WHERE ml.Email = '{$email}'");

        if ($query === false)
          throw new Exception();

        $datauser = $query->row();

        if ($datauser) {

          $hash = $datauser->Password;
          $hashp = $this->bcrypt->check_password($password, $hash); // harus 60 karkate jika selalu false check db jadi varchar(60)

          if ($hashp) {

            if ($this->input->post("remember_me") == "on") 
            {
              $this->session->set_tempdata('remember_email', $email, 86400); // 24 jam
              $this->session->set_tempdata('remember_password', $password, 86400);
              $this->session->set_tempdata('remember_chekbox', true, 86400);
            } else {
              $this->session->unset_userdata('remember_email');
              $this->session->unset_userdata('remember_password');
              $this->session->unset_userdata('remember_chekbox');
            }

            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('user', $datauser->Email);
            $this->session->set_userdata('ACLGroup', $datauser->ACLGroup);

            redirect('backend/dashboard');
          } else {

            $this->session->set_flashdata("error_msg", "Wrong password..!!");
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user');
            $this->session->unset_userdata('ACLGroup');
            redirect('backend/login');
          }
        } else {

          $this->session->set_flashdata("error_msg", "Invalid Email..!!");
          redirect('backend/login');
        }
      } else {

        $this->session->set_flashdata("error_msg", "Email not found!");
        redirect('backend/login');
      }
    } catch (Exception $e) {
      return $e;
    }
  }

  public function remember_me()
  {
    if (!$this->input->is_ajax_request()) {
      exit('No direct script access allowed');
    }

    $email    = $this->session->userdata('remember_email');
    $password = $this->session->userdata('remember_password');
    $checked  = $this->session->userdata('remember_chekbox');

    $res = array(
      "status"   => true,
      "email"    => $email,
      "password" => $password,
      "checked"  => $checked
    );
    echo json_encode($res);
  }

  public function logout()
  {
    $this->session->unset_userdata('logged_in');
    redirect('backend/login');
  }


  // public function register(){
  //   try{
  //     if($this->input->post()){

  //       $email           = $this->input->post('email');
  //       $password        = $this->input->post('password');
  //       $full_name       = $this->input->post('full_name');
  //       $retype_password = $this->input->post('retype_password');

  //       /* CHECK EMAIL EXIST */

  //       $query = $this->db->query(" SELECT * 
  //                                   FROM ms_login 
  //                                   WHERE email = '{$email}'");

  //       /* CHECK EMAIL EXIST */

  //       if($query === false)
  //           throw new Exception();

  //         $cekemail = $query->num_rows();

  //         if($cekemail == 1){

  //          //message belum
  //           redirect('backend/login/register');

  //         }else{

  //           if($password <> $retype_password){
  //             redirect('backend/login/register');
  //           }
  //           /* INSERT MEMBER */

  //               $query = $this->db->query("	INSERT INTO ms_login( Email ,Password, Active)
  //                                           SELECT 
  //                                             '{$email}' as email
  //                                             ,'{$this->bcrypt->hash_password($password)}' as password
  //                                             ,1 as Active
  //                                           ");						

  //                 if($query === FALSE)
  //                   throw new Exception();

  //                 $result = $this->db->affected_rows();	

  //                 if($result){
  //                   // message belum
  //                   redirect('backend/login');
  //                 }else{
  //                   redirect('backend/login/register');
  //                 }

  //           /* INSERT MEMBER */

  //         }
  //     }

  //     $this->load->view('login/register');

  //   } catch(Exception $e){
  //     return $e;
  //   }
  // }

}
