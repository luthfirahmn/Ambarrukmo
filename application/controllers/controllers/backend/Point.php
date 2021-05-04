<?php defined('BASEPATH') or exit('No direct script access allowed');

class Point extends CI_Controller
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
        pre("ok");
        // GET TYPE RULE
        $this->db->select("ParamID, ParamValue");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'RULE_TYPE'");
        $rule_type = $this->db->get()->result();

        // GET MEMBER/CUSTOMER
        $this->db->select("MemberID, Email");
        $this->db->from("ms_member");
        $member = $this->db->get()->result();

        /** DATA SEND TO VIEW */
        $data_content  = array();
        $data_content['title']       = 'Point';
        $data_content['breadcrumb']  = 'Dashboard';
        $data_content['breadcrumb1'] = 'Point';
        $data_content['data_tabel']  = 'DataTable Point';
        $data_content['form_title']  = 'Form Point';
        $data_content['rule_type']   = $rule_type;
        $data_content['member']      = $member;
        $data_content['success_msg'] = $this->session->flashdata("success_msg");
        $data_content['error_msg']   = $this->session->flashdata("error_msg");
        $data_content['info_msg']    = $this->session->flashdata("info_msg");
        $data_content['all_data']    = array();

        /* TEMPLATE */
        $config["content_file"] = "tr_point/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function get_list()
    {

        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("(MemberID LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            )");
        // if ($_REQUEST['length'] != -1)
        //     $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        // $all_data = $this->db->get()->result();


        /* ORDER */
        $order = array('tr_point.DID' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
        if (empty($order)) {
            $this->db->order_by("tr_point.DID", "DESC");
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
            0 => 'tr_point.DID',
            1 => 'tr_point.MemberID',
            2 => 'tr_point.TRXAmount',
            3 => 'tr_point.Point',
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
        $this->db->from("tr_point");
        $recordsTotal = $this->db->get()->num_rows();

        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("(MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                              )");
        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/point/delete") . "'";

        $data   = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[]    = $number;
            $row[]    = $val->MemberID;
            $row[]    = $val->TRXAmount;
            $row[]    = $val->Point;
            $row[]    = '<center>' . Buttons("delete", "myDelete('{$val->DID}', $urlDl)");
            //Buttons("edit", "editpoint($val->DID)") . " " .
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

    public function detail_type()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        // pre($_POST);

        if ($this->input->post("ruletype") == "BILL") :
            //GET TYPE BILL 

            $this->db->select("*");
            $this->db->from("rule_bill");
            $this->db->where("StartDate > now()");
            $all_data = $this->db->get()->result();

        else :
            //GET TYPE CATEGORY

            $this->db->select("*");
            $this->db->from("rule_category");
            $this->db->where("StartDate > now()");
            $all_data = $this->db->get()->result();
        endif;

        // pre($all_data);

        if ($all_data) {
            $output = array(
                "status"  => true,
                "message" => "Success Created..",
                "ruletype" => $this->input->post("ruletype"),
                "data"    => $all_data
            );
            echo json_encode($output);
            die;
        } else {
            $output = array(
                "status"  => false,
                "message" => "Data Not found",
            );
            echo json_encode($output);
            die;
        }
    }

    public function detail_category()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $this->db->select("*");
        $this->db->from("rule_category_detail");
        $this->db->where("PDID", $this->input->post("ruletitle"));
        $all_data = $this->db->get()->result();
        //pre($all_data);

        // if ($all_data) {
        $output = array(
            "status"  => true,
            "message" => "Success Created..",
            "data"    => $all_data
        );
        echo json_encode($output);
        die;
        // } else {
        //     $output = array(
        //         "status"  => false,
        //         "message" => "Data Not found",
        //     );
        //     echo json_encode($output);
        //     die;
        // }
    }

    // tr_log
    public function add_point()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        //pre($_POST);
        // GET DATA RULE_TYPE
        if ($this->input->post("ruletype") == "BILL") {
            $this->db->select("*");
            $this->db->from("rule_bill");
            $this->db->where("DID", $this->input->post("ruletitle"));
            $data = $this->db->get()->row();
        } else {
            $this->db->select("rcd.DID, rcd.PDID,rcd.RuleCategory,rcd.PointRatio,rcd.EventMultiply, rc.RuleTitle, rc.StartDate");
            $this->db->from("rule_category_detail rcd");
            $this->db->join("rule_category rc", "rcd.PDID = rc.DID");
            $this->db->where("rcd.PDID", $this->input->post("ruletitle"));
            $this->db->where("rcd.DID", $this->input->post("detailcategory"));
            $data = $this->db->get()->row();
        }

        //GET EXPIRED_TIME
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'POINT_EXPIRED'");
        $expired = $this->db->get()->row();

        $expiredTime = date('Y-m-d H:i:s', strtotime(dateSekarang() . ' + ' . $expired->ParamValue . $expired->ParamID));
        $floor = floor((int) $this->input->post("totalbill") / (int) $data->PointRatio);
        $point = (int) $floor * (int) $data->EventMultiply;

        $this->db->trans_begin();

        $data_insert["MemberID"]      = $this->input->post("member");
        $data_insert["TRXDate"]       = dateSekarang();
        $data_insert["TRXPhoto"]      = null;
        $data_insert["TRXAmount"]     = $this->input->post("totalbill");
        $data_insert["Point"]         = $point;
        $data_insert["RuleType"]      = $this->input->post("ruletype");
        $data_insert["RuleID"]        = $this->input->post("detailcategory") == 0 ? $this->input->post("ruletitle") : $this->input->post("detailcategory");
        $data_insert["VoucherNo"]     = null;
        $data_insert["ExpiredStatus"] = 0;
        $data_insert["ExpiredTime"]   = $expiredTime;

        $this->db->save_queries = TRUE;
        $this->db->insert("tr_point", $data_insert);

        if ($this->db->insert_id() > 1) {

            $sql          = $this->db->last_query();
            $page         = base_url('backend/point/add');
            tr_log($sql, $page, $this->user);

            $this->db->select("SUM(Point) as total_point, MemberID");
            $this->db->from("tr_point");
            $this->db->where("MemberID", $this->input->post("member"));
            $this->db->group_by("MemberID");
            $total_point = $this->db->get()->result();

            $data_update["TotalPoint"] = (int) $total_point[0]->total_point;

            $this->db->where("MemberID", $this->input->post("member"));
            $this->db->update("ms_member", $data_update);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            $output = array(
                "status"  => true,
                "message" => "Success Created..",
            );
            echo json_encode($output);
            die;
        } else {

            $output = array(
                "status"  => false,
                "message" => "Faild..",
            );
            echo json_encode($output);
            die;
        }
    }


    public function delete()
    {
        if ($this->input->post("DID") != "") {

            // GET DATA POINT
            $this->db->select("Point, MemberID");
            $this->db->from("tr_point");
            $this->db->where("DID", $this->input->post("DID"));
            $datatr = $this->db->get()->result();

            //GET TOTAL POIN MMEMBER

            $this->db->select("TotalPoint");
            $this->db->from("ms_member");
            $this->db->where("MemberID", $datatr[0]->MemberID);
            $point = $this->db->get()->result();

            $data_update["TotalPoint"] = (int) $point[0]->TotalPoint - (int) $datatr[0]->Point;

            $this->db->where("MemberID", $datatr[0]->MemberID);
            $this->db->update("ms_member", $data_update);


            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->delete("tr_point");

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {

                $sql  = $this->db->last_query();
                $page = base_url('backend/point/delete');
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
                "status" => "Invalid parameter"
            );
            echo json_encode($res);
        }
    }

    public function edit()
    {
    }
}
