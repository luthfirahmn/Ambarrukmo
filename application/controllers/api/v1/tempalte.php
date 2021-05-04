try{
        $query = $this->db->query("	");
        
        if($query===FALSE)
            throw new Exception();


    }catch(Exception $e){
        print_r($this->db->_error_number());die;
    }


