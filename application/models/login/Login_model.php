<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Login_model extends CI_Model {

    public function check_email($email){
        try{
            $query = $this->db->query(" SELECT * FROM member WHERE email = '{$email}'");
            
            if($query === false)
                throw new Exception();
             
           $result = $query->num_rows();
            
           return $result;

        } catch(Exception $e){
            return $e;
        }

    }
}