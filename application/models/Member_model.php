<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Member_model extends CI_Model {

    public function user($id = null){
        try{
            if($id <> null){
                $query = $this->db->query("SELECT * FROM member WHERE = {'$id'}");
            }else{
                $query = $this->db->query("SELECT * FROM member");
            }
            if($query === FALSE)
              throw new Exception();
              
             return $query->result();
        
        } catch(Exception $e) {
            return 0;
        }
    }
}