<?php defined('BASEPATH') or exit('No direct script access allowed');

class VoucherMember extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('backend/login');
        }
        $this->user = $this->session->userdata('user');
    }

    public function index($id = NULL)
    {
        /** DATA SEND TO VIEW */

        if (privilage($this->user, "voucherMember", "MAdd")) {
            $bt_add = 1;
        } else {
            $bt_add = 0;
        }
        $data_content                 = array();
        $data_content['bt_add']       = $bt_add;        
        $data_content['title']        = 'Voucher Member';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Voucher Member';
        $data_content['data_tabel']   = 'Data Voucher Member';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");

        $config["content_file"] = "VoucherMember/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function get_list()
    {
        /* SELECT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("VoucherID !=", '0');
        $this->db->where("(MemberID LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or VoucherCode LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or VoucherID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        /* ORDER */
        $order = array('VoucherBT' => 'DESC');
        $order  = $this->input->post("order");
        $col = 0;
        $dir = "";
         if(empty($order))
        {
            $this->db->order_by("VoucherBT", "DESC");
        }else{
            foreach($order as $o)
          {
             $col = $o['column'];
             $dir= $o['dir'];
          }
        }
        if($dir != "asc" && $dir != "desc")
        {
           $dir = "desc";
        }
        $valid_columns = array(
            0=>'DID',
            1=>'MemberID',
            2=>'TRXDate',
            3=>'Point',
            4=>'VoucherID',
            5=>'VoucherCode',
            6=>'VoucherBT',
            7=>'VoucherUT',
            8=>'ExpiredTime',
        );

        if(!isset($valid_columns[$col]))
        {
          $ordr = null;
        }
        else
        {
          $ordr = $valid_columns[$col];
        }
        if($ordr !=null)
        {
          $this->db->order_by($ordr, $dir);
        }
        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();


        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("VoucherID !=", '0');
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_point");
        $this->db->where("VoucherID !=", '0');
        $this->db->where("(MemberID LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or VoucherCode LIKE '%" . $_REQUEST['search']['value'] . "%'
                            or VoucherID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");

        $recordsFiltered = $this->db->get()->result();


        $urlused = "'" . base_url("backend/VoucherMember/active") . "'";

        $data = array();
        $number = $_POST['start'];
        foreach ($all_data as $key => $val) {

            $number++;
            $row = array();
            $row[] = $number;
            $row[] = $val->MemberID;
            $row[] = date("d-M-Y H:i:s", strtotime($val->TRXDate));
            $row[] = rtrim(rtrim($val->Point, '0'), '.');
            $row[] = $val->VoucherID.'<button type="button" class="btn btn-info btn-sm ml-3" onclick="modal('.$val->VoucherID.')">
                                          <i class="fa fa-eye"></i>
                                        </button>';
            $row[] = $val->VoucherCode;
            $row[] = date("d-M-Y H:i:s", strtotime($val->VoucherBT));
            $row[] = $val->VoucherUT;
            $row[] = date("d-M-Y H:i:s", strtotime($val->ExpiredTime));
            $row[] = $val->VoucherUsed != 1 ? '<center>' . Buttons("notused", "myActive($val->DID, 1, $urlused)") . '</center>' :
                '<center>' . Buttons("used", "myActive($val->DID, 0, $urlused)") . '</center>';

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


        public function active()
    {
        if ($this->input->post("DID") != "") {
            // pre($_POST);
            $use = $this->input->post('active');
            $data["VoucherUsed"] = $this->input->post("active");
            if ($use == '1') {
              $data["VoucherUT"]   = datesekarang();
            }else{
              $data["VoucherUT"]   = NULL;
            }
            // $data["RBU"]    = $this->user;

            $this->db->save_queries = TRUE;
            $this->db->where("DID", $this->input->post("DID"));
            $this->db->update("tr_point", $data);

            if ($this->db->affected_rows() > 0 ? TRUE : FALSE) {
                $sql         = $this->db->last_query();
                $page         = base_url('backend/Voucher/VoucherUsed');
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

    public function detailvoucher()
    {
        $id = $this->input->post('id');

        $this->db->select('*')
                 ->from('ms_voucher')
                 ->where('DID',$id);
        $data = $this->db->get()->result();


        $output = '';
        $output .= ' 
        <div class="table-responsive">
        <table id="table" class="table table-striped table-bordered table-sm">
                <thead>
        <tr>
                  <th>Voucher Code</th>
                  <th>Voucher Name</th>
                  <th>Image</th>
                  <th>Note</th>
                  <th>Redeem Point</th>
                  <th>Qty</th>
                  <th>Qty Used</th>
                  <th>Expired Time</th>
                  <th>Redeem Code</th>
                </tr>
                </thead>
                <tbody>';
        foreach($data as $row){
        $output .= '  
                <tr>
                  <td>'.$row->VoucherCode.'</td>
                  <td>'.$row->VoucherName.'</td>
                  <td><img src="' . base_url('upload/VOUCHER/') . $row->VoucherIMG . '" width="200" height="100"</td>
                  <td>'.$row->VoucherName.'</td>
                  <td>'.$row->RedeemPoint.'</td>
                  <td>'.$row->Qty.'</td>
                  <td>'.$row->QtyUsed.'</td>
                  <td>'.$row->ExpiredTime.'</td>
                  <td>'.$row->RedeemCode.'</td>
                </tr>
                ';
            }
        $output .= '  
                </tbody>
            </table>
        </div>
        '; 
        echo $output;
    }

}

