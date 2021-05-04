<?php defined('BASEPATH') or exit('No direct script access allowed');

class Bill extends CI_Controller
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

        // GET MEMBER/CUSTOMER
        $this->db->select("MemberID, Email");
        $this->db->from("ms_member");
        $this->db->where("Active = 1");
        $member = $this->db->get()->result();

        if (privilage($this->user, "bill", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }

        /** DATA SEND TO VIEW */
        $data_content  = array();
        $data_content['bt_add']       = $bt_add;
        $data_content['title']       = 'Point';
        $data_content['breadcrumb']  = 'Dashboard';
        $data_content['breadcrumb1'] = 'Point';
        $data_content['data_tabel']  = 'DataTable Point';
        $data_content['form_title']  = 'Form Point';
        //$data_content['rule_bill']   = $rule_bill;
        $data_content['member']      = $member;
        $data_content['success_msg'] = $this->session->flashdata("success_msg");
        $data_content['error_msg']   = $this->session->flashdata("error_msg");
        $data_content['info_msg']    = $this->session->flashdata("info_msg");
        $data_content['all_data']    = array();

        /* TEMPLATE */
        $config["content_file"] = "tr_point/bill";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }


    public function get_list()
    {
        //pre("ok");
        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("(MemberID LIKE '%" . $this->input->post("search")['value'] . "%' 
                            )");
        /* ORDER */

        if ($this->input->post("memberid")) {
            $this->db->where("MemberID", $this->input->post("memberid"));
        }

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
        if ($this->input->post("length") != -1)
            $this->db->limit($this->input->post("length"), $this->input->post("start"));

        $all_data = $this->db->get()->result();
        //pre($all_data);
        /* ORDER

        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_point");
        $recordsTotal = $this->db->get()->num_rows();

        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("(MemberID LIKE '%" . $this->input->post("search")['value'] . "%'
                              )");
        if ($this->input->post("memberid")) {
            $this->db->where("MemberID", $this->input->post("memberid"));
        }
        $recordsFiltered = $this->db->get()->result();

        $urlDl = "'" . base_url("backend/bill/delete") . "'";
        $path = "upload/BILL/";

        $data   = array();
        $number = $this->input->post("start");
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[]    = $number;
            $row[]    = $val->MemberID;
            $row[]    = number_format($val->TRXAmount, 2, ".", ",");
            $row[]    = $val->Point;
            if ($val->TRXPhoto) {
                $row[]    = '<center><button type="button" class="btn btn-info btn-sm test" onclick="infophoto(this)" value="' . base_url($path) . $val->TRXPhoto . '"><i class="fas fa-images"></i></button></center>';
            } else {
                $row[] = "";
            }
            $row[]    = $val->TRXNote;
            $row[]    = $val->TRXDate;

            if (privilage($this->user, "bill", "MDelete")) {
                $bt_delete = Buttons("delete", "myDelete('{$val->DID}', $urlDl)");
            } else {
                $bt_delete =  "";
            }

            $row[]    = '<center>'. $bt_delete .'</center>';
            //Buttons("edit", "editpoint($val->DID)") . " " .
            $data[] = $row;
        }

        $output = array(
            "draw"            => $this->input->post("draw"),
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => count($recordsFiltered),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    public function get_bill()
    {
        $this->db->select("*");
        $this->db->from("rule_bill");
        $this->db->where("StartDate > now()");
        $this->db->where("(RuleTitle LIKE '%" . $this->input->post("search")['value'] . "%' 
        )");
        if ($this->input->post("length") != -1)
            $this->db->limit($this->input->post("length"), $this->input->post("start"));
        $all_data = $this->db->get()->result();

        $this->db->select("*");
        $this->db->from("rule_bill");
        $this->db->where("StartDate > now()");
        $recordsTotal = $this->db->get()->num_rows();

        $this->db->select("*");
        $this->db->from("rule_bill");
        $this->db->where("StartDate > now()");
        $this->db->where("(RuleTitle LIKE '%" . $this->input->post("search")['value'] . "%'
                              )");
        $recordsFiltered = $this->db->get()->result();

        $data   = array();
        $number = $this->input->post("start");
        foreach ($all_data as $key => $val) {
            $number++;
            $row = array();
            $row[]    = $number;
            $row[]    = $val->RuleTitle;
            $row[]    = number_format($val->PointRatio, 2, ".", ",");
            $row[]    = $val->EventMultiply;
            // $row[]    = $val->StartDate;
            $row[]    = '<center><button type="button" class="btn btn-primary btn-sm" onclick="setbill(this)" value="'.$val->RuleTitle . "-" . $val->DID . "," . $val->PointRatio . "," . $val->EventMultiply.'">select</button></center>';

            $data[] = $row;
        }

        $output = array(
            "draw"            => $this->input->post("draw"),
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
    }

    // tr_log
    public function add_point()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $data_titlebill = explode(",", $this->input->post("titlebill"));

        //GET EXPIRED_TIME
        $this->db->select("*");
        $this->db->from("ms_parameter");
        $this->db->where("ParamVariable = 'POINT_EXPIRED'");
        $expired = $this->db->get()->row();


        $expiredTime = date('Y-m-d H:i:s', strtotime(dateSekarang() . ' + ' . $expired->ParamValue . $expired->ParamID));
        $floor = floor((int) preg_replace("/[^0-9]/", "", substr($_POST["trxamount"], 0, -3)) / (int) $data_titlebill[1]); //ratio
        $point = (int) $floor * (int) $data_titlebill[2]; //eventmutiply

        $this->db->trans_begin();

        $data_insert["MemberID"]    = $this->input->post("member");
        $data_insert["TRXDate"]     = dateSekarang();
        $data_insert["TRXNote"]     = $this->input->post("trxnote");
        $data_insert["TRXAmount"]   = preg_replace("/[^0-9]/", "", substr($_POST["trxamount"], 0, -3));
        $data_insert["Point"]       = $point;
        $data_insert["RuleType"]    = "BILL";
        $data_insert["RuleID"]      = $data_titlebill[0];
        $data_insert["VoucherID"]   = null;
        $data_insert["VoucherCode"] = null;
        $data_insert["VoucherUsed"] = null;
        $data_insert["VoucherBT"]   = null;
        $data_insert["VoucherUT"]   = null;
        $data_insert["ExpiredStatus"] = 0;
        $data_insert["ExpiredTime"]    = $expiredTime;

        if ($_FILES['VoucherIMG']['tmp_name'] != "") {
            $filename         = isset($_FILES['VoucherIMG']['name']) ? $_FILES['VoucherIMG']['name'] : NULL;
            $info             = pathinfo($filename);
            $image_name       = url_title(basename($filename, '.' . $info['extension']));
            $random           = get_random_string("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", 5);
            $image_filename   = $image_name . '_' . $random . '.' . $info['extension'];
            if (!file_exists(IMAGE_BLOCK_APPS_ROOT_BILL . $image_filename)) {
                $image_filename = $image_filename;
            }

            $configImage['upload_path']     = IMAGE_BLOCK_APPS_ROOT_BILL;
            $configImage['allowed_types']   = 'jpg|png|gif|jpeg';
            // $configImage['max_size']        = 500;
            $configImage['file_name']       = $image_filename;
            $configImage['overwrite']       = FALSE;


            $this->load->library('upload', $configImage);
            $this->upload->initialize($configImage);

            if (!$this->upload->do_upload("VoucherIMG")) {
                $output = array(
                    "status"  => false,
                    "message" => $this->upload->display_errors('', ''),
                );
                echo json_encode($output);
                die;
            }

            $file   = IMAGE_BLOCK_APPS_ROOT_BILL . $image_filename;

            $NewImageWidth          = 500;
            $NewImageHeight         = 500;
            $Quality                = 50;

            resizeImage($file, $file, $NewImageWidth, $NewImageHeight, $Quality);

            $data_insert["TRXPhoto"]    = $image_filename;
        } else {
            $data_insert["TRXPhoto"] = "";
        }
        /* START INSERT POINT */
        $this->db->insert("tr_point", $data_insert);
        /* END INSERT POINT */

        /* START SAVE LOG */
        $sql          = $this->db->last_query();
        $page         = base_url('backend/point/add');
        tr_log($sql, $page, $this->user);
        /* END SAVE LOG */

        /* START UPDATE POINT IN MEMBER */
        $this->db->select("SUM(Point) as total_point, MemberID");
        $this->db->from("tr_point");
        $this->db->where("MemberID", $this->input->post("member"));
        $this->db->group_by("MemberID");
        $total_point = $this->db->get()->result();

        $data_update["TotalPoint"] = (int) $total_point[0]->total_point;

        $this->db->where("MemberID", $this->input->post("member"));
        $this->db->update("ms_member", $data_update);
        /* END UPDATE POINT IN MEMBER */

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $output = array(
                "status"  => false,
                "message" => "Failed Create..",
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
    }


    public function delete()
    {
        if ($this->input->post("DID") != "") {
            // GET DATA POINT
            $this->db->select("Point, MemberID, TRXPhoto");
            $this->db->from("tr_point");
            $this->db->where("DID", $this->input->post("DID"));
            $datatr = $this->db->get()->result();

            if ($datatr[0]->TRXPhoto != "") {
                unlink(IMAGE_BLOCK_APPS_ROOT_BILL . $datatr[0]->TRXPhoto);
            }

            //GET TOTAL POIN MMEMBER
            $this->db->select("TotalPoint");
            $this->db->from("ms_member");
            $this->db->where("MemberID", $datatr[0]->MemberID);
            $point = $this->db->get()->result();

            $data_update["TotalPoint"] = (int) $point[0]->TotalPoint - (int) $datatr[0]->Point;

            $this->db->trans_begin();

            /* START UPDATE MEMBER */
            $this->db->where("MemberID", $datatr[0]->MemberID);
            $this->db->update("ms_member", $data_update);
            /* END UPDATE MEMBER */

            /* START DELETE POINT */
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->delete("tr_point");
            /* END DELETE POINT */

            /* START SAVE LOG */
            $sql  = $this->db->last_query();
            $page = base_url('backend/bill/delete');
            tr_log($sql, $page, $this->user);
            /* END SAVE LOG */

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $res = array(
                    "status" => false
                );
                echo json_encode($res);
                die;
            } else {
                $this->db->trans_commit();
                $res = array(
                    "status" => true,
                );
                echo json_encode($res);
                die;
            }
        } else {
            $res = array(
                "status" => "Invalid parameter"
            );
            echo json_encode($res);
            die;
        }
    }
}
