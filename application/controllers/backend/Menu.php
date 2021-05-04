<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
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
        if (privilage($this->user, "menu", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']        = 'Menu';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Menu';
        $data_content['data_tabel']   = 'DataTable Menu';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        // $data_content['all_data']     = $all_data;
        $data_content['all_data']     = array();

        /* TEMPLATE */
        $config["content_file"] = "ms_menu/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function form()
    {
        /* DATA PARENT */
        $this->db->select("DID, Menu");
        $this->db->from("ms_menu");
        $this->db->where("ParentID = 0");
        $query = $this->db->get();
        $data_parent = $query->result();

        /* DATA STATUS */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "MENU");
        $query = $this->db->get();
        $active = $query->result();

        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 0;
        $data_content['title']        = 'General Form';
        $data_content['breadcrumb']   = 'List Menu';
        $data_content['breadcrumb1']  = 'Menu Form';
        $data_content['data_tabel']   = 'Menu Form';
        $data_content['data_parent']  = $data_parent;
        $data_content['active']       = $active;
        //$data_content['all_data']     = $all_data;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "ms_menu/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function form_edit($id = null)
    {
        $this->db->select("DID, Menu");
        $this->db->from("ms_menu");
        $this->db->where("ParentID = 0");
        $query = $this->db->get();
        $data_parent = $query->result();


        /* DATA STATUS */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "MENU");
        $query = $this->db->get();
        $active = $query->result();


        $this->db->select("*");
        $this->db->from("ms_menu");
        $this->db->where("DID", $id);


        $query = $this->db->get();
        $all_data = $query->row();

        if (!$all_data) {
            $this->session->set_flashdata("error_msg", "Empty ID");
            redirect('backend/menu');
        }


        //pre($result);
        $data_content                 = array();
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        $data_content['status_edit']  = 1;
        $data_content['title']        = 'Menu Form';
        $data_content['breadcrumb']   = 'List Menu';
        $data_content['breadcrumb1']  = 'Menu Form Update';
        $data_content['data_tabel']   = 'Menu Form Update';
        $data_content['all_data']     = $all_data;
        $data_content['data_parent']  = $data_parent;
        $data_content['active']       = $active;
        /* E DATA FOR VIEW */
        //pre($all_data);

        $config["content_file"] = "ms_menu/form";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


    public function add()
    {
        if ($this->input->post()) {


            $insert["ParentID"]   = $this->input->post("parent");
            $insert["Menu"]       = $this->input->post("manu_name");
            $insert["MenuFile"]   = $this->input->post("manu_file");
            $insert["OrderNo"]    = $this->input->post("order_no");
            $insert["Active"]     = $this->input->post("active");

            /*Insert DB */
            $this->db->save_queries = TRUE;
            $this->db->insert("ms_menu", $insert);

            if ($this->db->insert_id() > 1) {
                /* INSERT LOG */
                $sql         = $this->db->last_query();
                $page         = base_url('backend/menu/add');
                tr_log($sql, $page, $this->user);

                $DID = $this->db->query("SELECT DID FROM ms_menu ORDER BY DID DESC LIMIT 1")->row()->DID;

                $this->db->select("DISTINCT(ACLGroup) as aclgroup");
                $this->db->from("ms_acl_group");
                $aclgroup = $this->db->get()->result();

                foreach ($aclgroup as $ky => $val) {

                    $insertAcl["ACLGroup"] = $val->aclgroup;
                    $insertAcl["MenuID"]   = $DID;
                    $insertAcl["MView"]    = 0;
                    $insertAcl["MAdd"]     = 0;
                    $insertAcl["MEdit"]    = 0;
                    $insertAcl["MDelete"]  = 0;
                    $insertAcl["MPrint"]   = 0;
                    $insertAcl["MExport"]  = 0;
                    $this->db->insert("ms_acl_group", $insertAcl);
                }

                if ($this->input->post('save') == "savenew") {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/menu/form');
                } elseif ($this->input->post('save') == "savedit") {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/menu/form_edit/' . $DID);
                } else {
                    $this->session->set_flashdata("success_msg", "Data insert success...");
                    redirect('backend/menu');
                }
            } else {
                $this->session->set_flashdata("error_msg", "error method insert...");
                redirect('backend/menu/form');
            }
        } else {
            $this->session->set_flashdata("error_msg", "error method insert...");
            redirect('backend/menu');
        }
    }

    public function edit($DID = null)
    {
        if ($this->input->post()) {

            $update["ParentID"]   = $this->input->post("parent");
            $update["Menu"]       = $this->input->post("manu_name");
            $update["MenuFile"]   = $this->input->post("manu_file");
            $update["OrderNo"]    = $this->input->post("order_no");
            $update["Active"]     = $this->input->post("active");

            $this->db->save_queries = TRUE;
            $this->db->update("ms_menu", $update, array('DID' => $DID));

            $sql         = $this->db->last_query();
            $page         = base_url('backend/menu/edit');
            tr_log($sql, $page, $this->user);

            if ($this->input->post('save') == "savenew") {
                $this->session->set_flashdata("success_msg", "Data update success...");
                redirect('backend/menu/form');
            } elseif ($this->input->post('save') == "savedit") {
                $this->session->set_flashdata("success_msg", "Data update success...");
                redirect('backend/menu/form_edit/' . $DID);
            } else {
                $this->session->set_flashdata("success_msg", "Data update success...");
                redirect('backend/menu');
            }
        } else {
            $this->session->set_flashdata("error_msg", "error method update...");
            redirect('backend/menu/form');
        }
    }

    //ajax request =======================>

    public function get_list()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        //pre($_POST);
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_menu");
        $this->db->order_by("ParentID", "asc");
        $this->db->where("(MenuFile LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or Menu LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or ParentID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        //if ($_REQUEST['length'] != -1)
        //$this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        // $all_data = $this->db->get()->result();

        /* ORDER */
        $order = array('ms_menu.DID' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if (empty($order)) {
            $this->db->order_by("ms_menu.DID", "DESC");
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
            0 => 'ms_menu.DID',
            1 => 'ms_menu.ParentID',
            2 => 'ms_menu.Menu',
            3 => 'ms_menu.MenuFile',
            4 => 'ms_menu.OrderNo',
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

        /* ORDER

        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("ms_menu");
        $this->db->order_by("ParentID", "asc");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_menu");
        $this->db->order_by("ParentID", "asc");
        $this->db->where("(MenuFile LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or Menu LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or ParentID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlAc = "'" . base_url("backend/menu/active") . "'";
        $urlDl = "'" . base_url("backend/menu/delete") . "'";


        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->ParentID;
            $row[] = $val->Menu;
            $row[] = $val->MenuFile;
            $row[] = '<span id="order-' . $val->DID . '">' . $val->OrderNo . '</span> <span class="float-right">
                        <button type="button" class="btn btn-light btn-sm" onclick="functionUp(this)" value="' . $val->DID . '" ><i class="fas fa-angle-up"></i></button> 
                        <button type="button" class="btn btn-light btn-sm" onclick="functionDown(this)" value="' . $val->DID . '" ><i class="fas fa-angle-down"></i></button>
                    </span>';
            $row[] = $val->Active != 1 ? '<center>' . Buttons("disabled", "myActive($val->DID, 1, $urlAc)") . '</center>' :
                '<center>' . Buttons("actived", "myActive($val->DID, 0, $urlAc)") . '</center>';


            if (privilage($this->user, "menu", "MEdit")) {
                $bt_edit = Buttons("edit", "location.href='" . base_url("/backend/menu/form_edit/" . $val->DID . "") . "'");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "menu", "MDelete")) {
                $bt_delete = Buttons("delete", "myDelete($val->DID, $urlDl)");
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

    public function update_orderno()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->db->where("DID", $this->input->post("DID"));
        $this->db->update("ms_menu", array("OrderNo" => $this->input->post("OrderNo")));

        if ($this->db->affected_rows() > 0) {
            $res = array(
                "status" => true,
            );
            echo json_encode($res);
            exit;
        } else {
            $res = array(
                "status" => false,
            );
            echo json_encode($res);
            exit;
        }
    }

    public function delete()
    {
        if ($this->input->post("DID") != "") {

            $this->db->save_queries = TRUE;

            $this->db->where("MenuID", $this->input->post("DID"));
            $this->db->delete("ms_acl_group");


            $this->db->where('DID', $this->input->post("DID"));
            $this->db->delete('ms_menu');



            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql         = $this->db->last_query();
                $page         = base_url('backend/menu/delete');
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

    public function active()
    {
        if ($this->input->post("DID") != "") {

            $data["Active"] = $this->input->post("active");
            // $data["RBU"]    = $this->user;
            // $data["RBT"]    = dateSekarang();

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("ms_menu", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/menu/active');
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

    public function alert()
    {
        $success_msg  = "";
        $error_msg    = "";
        $info_msg     = "";


        $status = $_GET['status'];
        $msg = $_GET['msg'];

        if ($status == "success_msg") {
            $success_msg = $msg;
        } elseif ($status == "error_msg") {
            $error_msg = $msg;
        } else {
            $info_msg = $msg;
        }

        echo notify_message($success_msg, $error_msg, $info_msg);
    }
}
