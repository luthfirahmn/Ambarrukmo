<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*   Authorization_Token
* -------------------------------------------------------------------
* API Token Check and Generate
*
* @author: Jeevan Lal
* @version: 0.0.5
*/


class Api
{

	private $CI;
    /**
     * Constructor
     */
    function __construct()
    {
        //Load your settting here
        $this->ci = &get_instance();

        $this->db = $this->ci->db;
    }

    function insert_log_api($data)
    {

        $this->db->insert("api_log", $data);

        if ($this->db->insert_id() > 1) {
            return true;
        } else {
            return false;
        }
    }

}