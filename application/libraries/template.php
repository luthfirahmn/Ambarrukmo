<?php

if ( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

class Template {
	private $CI;
	private $_content_file;
	private $_content_data;

	public function __construct() {
		$this->CI =& get_instance();
	}

	public function initialize($config = array()) {  
		$this->_content_file = (isset($config["content_file"])) ? $config["content_file"] : "";
		$this->_content_data = (isset($config["content_data"])) ? $config["content_data"] : "";
	}

	public function render($return_data = FALSE) {
		$data["content_file"] = $this->_content_file;
		$data["content_data"] = $this->_content_data;
		// $data["menu_data"] = array("all_menu" => $this->CI->menu->index());

		// JIKA VIEW DITAMPILKAN DALAM BENTUK DATA
		if ($return_data)
			return $this->CI->load->view("template", $data, $return_data);
		else
			$this->CI->load->view("template", $data);
	}

}