<?php defined('BASEPATH') or exit('No direct script access allowed');

class Useraccess extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model(array("login/login_model")); 
       // $this->load->library('hash_password');   
        $this->load->library('bcrypt'); 
    }

    public function index(){
      $this->load->view('login/form_login');
    }

    public function sign_in(){
      try{
        //pre($_POST);
        $data = $this->input->post();
        $email = $data['data']['email'];
        $password = $data['data']['password'];
     
        if(!$email == ''){
         
              $query = $this->db->query(" SELECT email, password
                                          FROM user 
                                          WHERE email = '{$email}'");
                
                if($query === false)
                    throw new Exception();
                
              $datauser = $query->row();
              
              if($datauser){

                $hash = $datauser->password;

                
                $hashp = $this->bcrypt->check_password($password,$hash); // harus 60 karkate jika selalu false check db jadi varchar(60)
                
                if($hashp){

                  $this->session->set_userdata('logged_in', true);
                  redirect('backend/dashboard');

                }else{
                  $this->session->unset_userdata('logged_in');
                  redirect('backend/useraccess');
                }

              }else{


                redirect('backend/useraccess');
              }          

        }else{
          redirect('backend/useraccess');
        }

      } catch(Exception $e){
        return $e;
      }

    }

    public function register(){
      try{
        if($this->input->post()){
          
          $email           = $this->input->post('email');
          $password        = $this->input->post('password');
          $full_name       = $this->input->post('full_name');
          $retype_password = $this->input->post('retype_password');

          /* CHECK EMAIL EXIST */

          $query = $this->db->query(" SELECT * 
                                      FROM user 
                                      WHERE email = '{$email}'");
          
          /* CHECK EMAIL EXIST */

          if($query === false)
              throw new Exception();

            $cekemail = $query->num_rows();

            if($cekemail == 1){

             //message belum
              redirect('backend/useraccess/register');

            }else{

              if($password <> $retype_password){
                redirect('backend/useraccess/register');
              }
              /* INSERT MEMBER */

                  $query = $this->db->query("	INSERT INTO user( email ,password, name, role, status, created_by, created_at)
                                              SELECT 
                                                '{$email}' as email
                                                ,'{$this->bcrypt->hash_password($password)}' as password
                                                ,'{$full_name}' as nick_name
                                                ,1 as role
                                                ,1 as status
                                                ,1 as created_by
                                                ,NOW() as created_at
                                              ");						

                    if($query === FALSE)
                      throw new Exception();
                      
                    $result = $this->db->affected_rows();	

                    if($result){
                      // message belum
                      redirect('backend/useraccess');
                    }else{
                      redirect('backend/useraccess/register');
                    }

              /* INSERT MEMBER */

            }
        }
      
        $this->load->view('login/register');

      } catch(Exception $e){
        return $e;
      }
    }

    public function logout(){
      $this->session->unset_userdata('logged_in');
      redirect('backend/useraccess');
    }
  

}