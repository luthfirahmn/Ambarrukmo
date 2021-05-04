<?php defined('BASEPATH') or exit('No direct script access allowed');

class LogApi extends CI_Controller
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

        /** DATA SEND TO VIEW */
        $data_content                 = array();
        $data_content['title']        = 'Log Api';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Log Api';
        $data_content['data_tabel']   = 'DataTable Log Api';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "LogApi/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


     public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("api_log");
        $this->db->where("(LogType LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or LogRequest LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or LogResponse LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or LogIP LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or RBT LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");


        /* ORDER */
        $order = array('api_log.DID' => 'ASC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if (empty($order)) {
            $this->db->order_by("api_log.DID", "ASC");
        } else {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }
        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }
        $valid_columns = array(
            0 => 'api_log.DID',
            1 => 'LogType',
            2 => 'LogIP',
            3 => 'RBT',
            4 => 'LogRequest',
            5 => 'LogResponse',
        );

        if (!isset($valid_columns[$col])) {
            $ordr = null;
        } else {
            $ordr = $valid_columns[$col];
        }
        if ($ordr != null) {
            $this->db->order_by($ordr, $dir);
        }

        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();



        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("api_log");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("api_log");
        $this->db->where("(LogType LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or LogRequest LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or LogResponse LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or LogIP LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or RBT LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->LogType;
            $row[] = $val->LogIP;
            $row[] = $val->RBT;
            $row[] = $val->LogRequest;
            $row[] = $val->LogResponse;
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
}