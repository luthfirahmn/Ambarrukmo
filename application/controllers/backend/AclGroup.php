<?php defined('BASEPATH') or exit('No direct script access allowed');

class AclGroup extends CI_Controller
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
        if (privilage($this->user, "aclgroup", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content  = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']       = 'ACL Group';
        $data_content['breadcrumb']  = 'Dashboard';
        $data_content['breadcrumb1'] = 'ACL Group';
        $data_content['data_tabel']  = 'DataTable ACL Group';
        $data_content['success_msg'] = $this->session->flashdata("success_msg");
        $data_content['error_msg']   = $this->session->flashdata("error_msg");
        $data_content['info_msg']    = $this->session->flashdata("info_msg");
        // $data_content['all_data']     = $all_data;
        $data_content['all_data'] = array();

        /* TEMPLATE */
        $config["content_file"] = "ms_acl_group/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


    public function get_list()
    {
        /* SELECT DATA ALL */
        
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'ACL_USER_GROUP'");
        $this->db->where("(paramValue LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            )");

        $order = array('ms_parameter.DID' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if (empty($order)) {
            $this->db->order_by("ms_parameter.DID", "DESC");
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
            1 => 'ms_parameter.ParamVariable',
            2 => 'ms_parameter.ParamID',
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
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'ACL_USER_GROUP'");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'ACL_USER_GROUP'");
        $this->db->where("(paramValue LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/aclGroup/delete") . "'";

        $data   = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[]    = $number;
            $row[]    = $val->ParamID;
            $row[]    = $val->ParamValue;

            if (privilage($this->user, "aclgroup", "MEdit")) {
                $bt_edit = Buttons("edit", "formedit($val->DID)");
            } else {
                $bt_edit =  "";
            }

            if (privilage($this->user, "aclgroup", "MDelete")) {
                $bt_delete = Buttons("delete", "myDeletes('{$val->DID}', $urlDl)");
            } else {
                $bt_delete =  "";
            }
            $row[] = '<center>' . $bt_edit . " " .  $bt_delete . '</center>';

            $data[] = $row;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => count($recordsFiltered),
            "data"            => $data,
        );


        echo json_encode($output);
    }

    public function add()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        if ($this->input->post()) {

            $this->db->select("DID");
            $this->db->from("ms_parameter");
            $this->db->where("ParamValue",$this->input->post("ParamValue"));
            $grup = $this->db->get()->row();
           
            if($grup){
                $output = array(
                    "status"  => false,
                    "message" => "the group already exists..",
                );
                echo json_encode($output);
                die;
            }

            $this->db->trans_begin();

            $insert['ParamVariable'] = "ACL_USER_GROUP";
            $insert['ParamID']       = $this->input->post('ParamID');
            $insert['ParamValue']    = $this->input->post('ParamValue');
            $this->db->insert("ms_parameter", $insert);

            $sql  = $this->db->last_query();
            $page = base_url('backend/aclGroup/add');
            tr_log($sql, $page, $this->user);


            $this->db->select("DID");
            $this->db->from("ms_menu");
            $this->db->where("ParentId <> 0");
            $did = $this->db->get()->result_array();
            //pre($did);

            foreach ($did as $val) {
                $insertAcl["ACLGroup"] = $this->input->post('ParamValue');
                $insertAcl["MenuID"]   = $val["DID"];
                $insertAcl["MView"]    = 0;
                $insertAcl["MAdd"]     = 0;
                $insertAcl["MEdit"]    = 0;
                $insertAcl["MDelete"]  = 0;
                $insertAcl["MPrint"]   = 0;
                $insertAcl["MExport"]  = 0;

                $this->db->insert("ms_acl_group", $insertAcl);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $output = array(
                    "status"  => false,
                    "message" => "Faild Created.!!!",
                );
                echo json_encode($output);
                die;
            } else {
                $this->db->trans_commit();
                $output = array(
                    "status"  => true,
                    "message" => "Success Created..",
                );
                echo json_encode($output);
                die;
            }
        } else {
            $output = array(
                "status"  => false,
                "message" => "Error method Post...",
            );
            echo json_encode($output);
            die;
        }
    }

    public function form_edit()
    {
        //pre($_POST);
        $id = $this->input->post("ParamID");
        /* DATA DATA */
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("DID", $id);
        $this->db->where("ParamVariable", "ACL_USER_GROUP");

        $query    = $this->db->get();
        $all_data = $query->row();

        if ($all_data) {
            $output = array(
                "status"  => true,
                "message" => "success..",
                "data"    => $all_data
            );

            echo json_encode($output);
            die;
        } else {
            $output = array(
                "status"  => true,
                "message" => "Not Found Data..",
            );

            echo json_encode($output);
            die;
        }
    }

    public function edit()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        //pre($_POST);
        // GET DATA ms_parameter
        $this->db->select("ParamID, ParamValue");
        $this->db->from("ms_parameter");
        $this->db->where("DID", $this->input->post('DID'));
        $acl_gorup = $this->db->get()->row();
        // GET DAT ms_parameter
        //pre($acl_gorup);
        $this->db->trans_begin();
        /* UPDATE MS PARAMETER */
        $DID                     = $this->input->post('DID');
        $update['ParamVariable'] = "ACL_USER_GROUP";
        $update['ParamID']       = $this->input->post('ParamID');
        $update['ParamValue']    = $this->input->post('ParamValue');

        $this->db->where("DID", $DID);
        $this->db->update("ms_parameter", $update);
        /* UPDATE MS PARAMETER */

        $sql  = $this->db->last_query();
        $page = base_url('backend/aclGroup/edit');
        tr_log($sql, $page, $this->user);

        /* UPDATE MS ACL GROUP */
        $updates['ACLGroup']       = $this->input->post('ParamValue');
        $this->db->where("ACLGroup", $acl_gorup->ParamValue);
        $this->db->update("ms_acl_group", $updates);
        /* UPDATE MS ACL GROUP */

        /* UPDATE MS ACL USER GROUP */
        $updates['ACLGroup']       = $this->input->post('ParamValue');
        $this->db->where("ACLGroup", $acl_gorup->ParamID);
        $this->db->update("ms_acl_user_group", $updates);
        /* UPDATE MS ACL USER GROUP */

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $output = array(
                "status"  => false,
                "message" => "Failed Updated..",
            );
            echo json_encode($output);
            die;
        } else {
            $this->db->trans_commit();
            $output = array(
                "status"  => true,
                "message" => "Success Updated..",
            );
            echo json_encode($output);
            die;
        }

    }

    public function delete()
    {
        if ($this->input->post("DID") != "") {

          
            $this->db->select("ParamID, Paramvalue");
            $this->db->from("ms_parameter");
            $this->db->where("DID", $this->input->post("DID"));
            $param = $this->db->get()->row();

            $this->db->trans_begin();

            $this->db->where('DID', $this->input->post("DID"));
            $this->db->delete('ms_parameter');

            $sql  = $this->db->last_query();
            $page = base_url('backend/aclGroup/delete');
            tr_log($sql, $page, $this->user);

            $this->db->where("ACLGroup", $param->Paramvalue);
            $this->db->delete("ms_acl_group");

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $output = array(
                    "status"  => false,
                    "message" => "Failed Updated..",
                );
                echo json_encode($output);
                die;
            } else {
                $this->db->trans_commit();
                $output = array(
                    "status"  => true,
                    "message" => "Success Updated..",
                );
                echo json_encode($output);
                die;
            }
        } else {
            $res = array(
                "status" => "Invalid parameter"
            );
            echo json_encode($res);
        }
    }


    public function access_menu()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        /* GET DATA  ROLE*/
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable", "ACL_USER_GROUP");
        $ACL_USER_GROUP = $this->db->get()->result();
        if ($ACL_USER_GROUP) {
            $output = array(
                "status"  => true,
                "message" => "success.!!!",
                "data"    => $ACL_USER_GROUP
            );
            echo json_encode($output);
            die;
        } else {
            $output = array(
                "status"  => false,
                "message" => "Get data Faild.!!!",
            );
            echo json_encode($output);
            die;
        }
    }

    public function access_user()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        if ($this->input->post()) {

            $selected = $this->input->post("selected");

            /* GET DATA  MENU*/
            $menu = $this->db->query(" SELECT mm.DID
                                            ,mm.Menu
                                            ,mag.ACLGroup 
                                            ,mag.MView
                                            ,mag.MAdd
                                            ,mag.MEdit
                                            ,mag.MDelete
                                            ,mag.MPrint
                                            ,mag.MExport
                                        FROM ms_menu as mm
                                        LEFT JOIN ms_acl_group as mag ON mag.MenuID = mm.DID
                                        WHERE mag.ACLGroup = '{$selected}'
                                        AND mm.ParentID <> 0
                                        GROUP BY mag.MenuID
                                   ")->result();

            if ($menu) {
                $output = array(
                    "status"  => true,
                    "message" => "success.!!!",
                    "data"    => $menu
                );
                echo json_encode($output);
                die;
            } else {
                $output = array(
                    "status"  => false,
                    "message" => "Get data Faild.!!!",
                );
                echo json_encode($output);
                die;
            }
        } else {
            $this->session->set_flashdata("error_msg", "error Params...");
            redirect('backend/aclGroup/access_menu');
        }
    }


    public function set_access()
    {
        $checked = $this->input->post('checked');

        $checkbox_data = array();
        foreach($checked as $keys => $val){
            $checkbox_data[$val["did"]][$val["access"]] =$val["value"];
        }
       
        foreach($checkbox_data as $key => $access){
           $this->db->where("ACLGroup", $this->input->post("aclgroup"));
           $this->db->where("MenuId", $key);
           $this->db->update("ms_acl_group",$access);
        }

        $output = array(
            "status"  => true,
            "message" => "success.!!!",
        );
        echo json_encode($output);

    }
}
