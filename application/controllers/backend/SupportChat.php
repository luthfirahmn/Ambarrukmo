<?php defined('BASEPATH') or exit('No direct script access allowed');

class SupportChat extends CI_Controller
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
        $data_content['title']        = 'Support Chat';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Support Chat';
        $data_content['data_tabel']   = 'Data Chat';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        // $data_content['all_data']     = $all_data;
        $data_content['all_data']     = array();

        /* TEMPLATE */
        $config["content_file"] = "SupportChat/index";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function get_list()
    {

        /* SELECT DATA ALL */
        $this->db->select("*,chat.DID as ChatDID,chat.MemberID as ChatMemberID");
        $this->db->from("tr_chat as chat");
        $this->db->join("ms_member as member", "member.DID = chat.MemberID","LEFT");
        $this->db->where("(FullName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or chat.MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $this->db->where("DELETED",'0');
        $this->db->group_by("chat.MemberID");
        $this->db->order_by("chat.RBT","DESC");

        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();

        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_chat");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_chat as chat");
        $this->db->join("ms_member as member", "member.DID = chat.MemberID","LEFT");
        $this->db->where("(FullName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or chat.MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $this->db->order_by("chat.RBT","DESC");

        $recordsFiltered = $this->db->get()->result();


        $data = array();
        foreach ($all_data as $key => $val) {
            $row = array();
            $row[] = '<input type="checkbox" class="checkbox_delete_chat" id="checkbox_delete_chat" value="'.$val->ChatMemberID.'" >
                      ';
            $row[] = $val->ChatMemberID;
            $row[] = '<strong>'.$val->FullName;
            $row[] = '<div style="white-space: nowrap;width: 300px;overflow: hidden; text-overflow: ellipsis;">'.$val->Message.'</div>';
            $row[] = date(" H:i / d-M-Y", strtotime($val->ChatTime));

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


        public function get_list_read()
    {

        $status = $this->input->post('status_selection');

        /* SELECT DATA ALL */
        $this->db->select("*,chat.DID as ChatDID,chat.MemberID as ChatMemberID");
        $this->db->from("tr_chat as chat");
        $this->db->join("ms_member as member", "member.DID = chat.MemberID","LEFT");
        $this->db->where("(FullName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or chat.MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $this->db->where("StatusRead",$status);
        $this->db->where("DELETED",'0');
        $this->db->group_by("chat.MemberID");
        $this->db->order_by("chat.RBT","DESC");

        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();

        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_chat");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_chat as chat");
        $this->db->join("ms_member as member", "member.DID = chat.MemberID","LEFT");
        $this->db->where("(FullName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or chat.MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $this->db->order_by("chat.RBT","DESC");

        $recordsFiltered = $this->db->get()->result();


        $data = array();
        foreach ($all_data as $key => $val) {
            $row = array();
            $row[] = '<input type="checkbox" class="checkbox_delete_chat" id="checkbox_delete_chat" value="'.$val->ChatMemberID.'" >
                      ';
            $row[] = $val->ChatMemberID;
            $row[] = '<strong>'.$val->FullName;
            $row[] = '<div style="white-space: nowrap;width: 300px;overflow: hidden; text-overflow: ellipsis;">'.$val->Message.'</div>';
            $row[] = date(" H:i / d-M-Y", strtotime($val->ChatTime));

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

    public function get_list_deleted()
    {

        $status = $this->input->post('status_deleted');

        /* SELECT DATA ALL */
        $this->db->select("*,chat.DID as ChatDID,chat.MemberID as ChatMemberID");
        $this->db->from("tr_chat as chat");
        $this->db->join("ms_member as member", "member.DID = chat.MemberID","LEFT");
        $this->db->where("(FullName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or chat.MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $this->db->where("DELETED",$status);
        $this->db->order_by("chat.RBT","DESC");

        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $all_data = $this->db->get()->result();

        /* SELECT COUNT DATA ALL */
        $this->db->select("*");
        $this->db->from("tr_chat");
        $recordsTotal = $this->db->get()->num_rows();


        /* SELECT COUNT FILTR DATA */
        $this->db->select("*");
        $this->db->from("tr_chat as chat");
        $this->db->join("ms_member as member", "member.DID = chat.MemberID","LEFT");
        $this->db->where("(FullName LIKE '%" . $_REQUEST['search']['value'] . "%' 
                            or chat.MemberID LIKE '%" . $_REQUEST['search']['value'] . "%'
                            )");
        $this->db->order_by("chat.RBT","DESC");

        $recordsFiltered = $this->db->get()->result();


        $data = array();
        foreach ($all_data as $key => $val) {
            $row = array();
            $row[] = '<input type="checkbox" class="checkbox_delete_chat" id="checkbox_delete_chat" value="'.$val->ChatMemberID.'" >
                      ';
            $row[] = $val->ChatMemberID;
            $row[] = '<strong>'.$val->FullName;
            $row[] = '<div style="white-space: nowrap;width: 300px;overflow: hidden; text-overflow: ellipsis;">'.$val->Message.'</div>';
            $row[] = date(" H:i / d-M-Y", strtotime($val->RBT));

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

    public function chat($id = null)
    {

        /*SELECT DATA*/
        $this->db->select("*,chat.DID as ChatDID")
                    ->from("tr_chat as chat")
                    ->where('chat.MemberID',$id);
        $query = $this->db->get();
        $all_data = $query->result();

        /*SELECT MEMBER*/
        $this->db->select("*")
                    ->from("ms_member as member")
                    ->where('member.DID',$id);
        $query = $this->db->get();
        $member = $query->result();

        /** DATA SEND TO VIEW */
        $data_content                 = array();
        $data_content['title']        = 'Chat';
        $data_content['breadcrumb']   = 'Dashboard';
        $data_content['breadcrumb1']  = 'Chat';
        $data_content['data_tabel']   = 'Direct Chat';
        $data_content['success_msg']  = $this->session->flashdata("success_msg");
        $data_content['error_msg']    = $this->session->flashdata("error_msg");
        $data_content['info_msg']     = $this->session->flashdata("info_msg");
        // $data_content['all_data']     = $all_data;
        $data_content['all_data']     = $all_data;
        $data_content['member']     = $member;

        /* TEMPLATE */
        $config["content_file"] = "SupportChat/chat";
        $config["content_data"] = $data_content;

        $this->template->initialize($config);
        $this->template->render();
    }

    public function send_text_Message()
    {
        $post = $this->input->post();
        $MessageTXT='NULL';
    
            $MessageTXT = $post['MessageTXT'];
            $user_login = $this->session->userdata('user');
            $LoginID = $this->db->query("SELECT EmpID FROM ms_login WHERE DID = $user_login")->row()->EmpID;
            $EmpID = $this->db->query("SELECT DID FROM ms_employee WHERE DID = $LoginID")->row()->DID;
         
                $data=[
                    'MemberID'  => $post['MemberID'],
                    'EmpID'     => $EmpID,
                    'ChatTime'  => dateSekarang(),
                    'EmpID'     => $EmpID,
                    'Message'   => $MessageTXT,
                    'RBT'       => dateSekarang(), 
                ];

                $res = $this->db->insert('tr_chat', $data ); 
                if($res == 1) {
                   $query =  true;
                }
                else {
                   $query = false;
                }

                $response='';
                if($query == true)
                {
                    $response = ['status' => 1 ,'Message' => '' ];
                }
                else
                {
                    $response = ['status' => 0 ,'Message' => 'sorry we re having some technical problems. please try again !'                       ];
                }
             
           echo json_encode($response);
    }


    public function get_chat($id){

        /*SELECT CHAT DATA*/
        $this->db->select('*,chat.DID as ChatDID,emp.FullName as EmpName, member.FullName as MemberName,chat.EmpID as ChatEmpID');
        $this->db->from('tr_chat as chat');
        $this->db->where('chat.MemberID',$id);
        $this->db->where('chat.DELETED','0');
        $this->db->join('ms_employee as emp','emp.DID = chat.EmpID','LEFT');
        $this->db->join('ms_member as member','member.DID = chat.MemberID','LEFT');
        $this->db->order_by('ChatTime','ASC');
        $query = $this->db->get();
        $row =  $query->result_array();
        ?>



        <?php
        foreach($row as $chat):
            
            $ChatDID = $chat['ChatDID'];
            $MemberID = $chat['MemberID'];
            $Message = $chat['Message'];
            $ChatEmpID = $chat['ChatEmpID'];
            $ChatTime = date('d M H:i A',strtotime($chat['ChatTime']));
            $user_login = $this->session->userdata('user');
            $LoginID = $this->db->query("SELECT EmpID FROM ms_login WHERE DID = $user_login")->row()->EmpID;
            $Sender = $this->db->query("SELECT DID FROM ms_employee WHERE DID = $LoginID")->row()->DID;
            
            if($Sender == $MemberID)
            {
                $name = $chat['MemberName'];
            }else{
                $name = $chat['EmpName'];
            }

            
            
        ?>
            
             <?php if($ChatEmpID==''){?>     


                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left"><?=$name;?></span>
                      <span class="direct-chat-timestamp float-right"><?=$ChatTime;?></span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="<?= base_url('assets') ?>/dist/img/avatar.png" alt="Message user image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      <?=$Message;?>
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->
                <!--chat Row -->
                <!-- <li class="chat-item">
                    <div class="chat-img"><img src="<?php echo $photo ?>" alt="user"></div>
                    <div class="chat-content">
                        <h6 class="font-medium"><?=$name;?></h6>
                        <div class="box bg-light-info"><?=$Message;?></div>
                    </div>
                    <div class="chat-time"><?=$Messagedatetime;?></div>
                </li> -->

            <?php }else{?>

                <!-- Message to the right -->
                      <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-name float-right"><?=$name;?></span>
                          <span class="direct-chat-timestamp float-left"><?=$ChatTime;?></span>
                        </div>
                        <!-- /.direct-chat-infos -->
                        <img class="direct-chat-img" src="<?= base_url('assets') ?>/dist/img/avatar5.png" alt="Message user image">
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                          <?=$Message;?>
                        </div>
                        <!-- /.direct-chat-text -->
                      </div>
                      <!-- /.direct-chat-msg -->

                    <?php } ?>
        
        <?php
        endforeach;
        ?>
        <?php
        
    }


    function update_read_message($id){
        $data   = array('StatusRead' => '1' );
        $result  = $this->db->where('MemberID',$id)
                            ->update('tr_chat',$data);
        echo json_encode($result); 
    }


    function delete_chat()
    {
      if($this->input->post('checkbox_value')){

            $MemberID = $this->input->post('checkbox_value');
            for($count = 0; $count < count($MemberID); $count++) {
                $DelID = $MemberID[$count];
                $data = array('DELETED' => '1' );
                $this->db->where('MemberID', $DelID);
                $this->db->update('tr_chat',$data);
            }
      }
    }

}