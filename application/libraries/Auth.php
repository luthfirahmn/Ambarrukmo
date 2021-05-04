<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
    private $CI;
    /**
     * Constructor
     */
    function __construct()
    {
        //Load your settting here
        $this->ci = &get_instance();
        $this->ci->load->library('session');
        $this->ci->load->library('email');

        $this->db = $this->ci->db;
    }

    // --------------------------------------------------------------------

    /**
     * Check user signin status
     *
     * @access public
     * @return bool
     */

    function get_user($email = null)
    {
        $this->db->select("me.FullName");
        $this->db->from("ms_employee me");
        $this->db->join("ms_login ml", "ml.Email = me.Email");
        $this->db->where("ml.email", $email);
        $query = $this->db->get();
        $result = $query->row();

        if ($result) {
            return $result->FullName;
        } else {
            return "";
        }
    }

    function insert_log($data)
    {
        $this->db->insert("tr_log", $data);

        if ($this->db->insert_id() > 1) {
            return true;
        } else {
            return false;
        }
    }


    // di sini--------------------------------------------------------------------

    function data_template($temp, $email, $kode)
    {

        switch ($temp) {
            case "ForgetPass":
                $data = array();
                $data['subject'] = "Forget Password";
                $data['body'] = "Your Password : " . $kode;

                break;

            default:
                $data = array();
                $data['subject'] = "verifikasi OTP";
                $data['body'] = "your OTP code : " . $kode;
        }

        return $data;
    }

    function privilage($userid, $module, $function)
    {
        $this->db->select("maug.ACLGroup, $function ,(SELECT DID FROM ms_menu WHERE menu='{$module}') as idmenu");
        $this->db->from("ms_acl_user_group maug");
        $this->db->join("ms_acl_group mag", "mag.ACLGroup = maug.ACLGroup");
        $this->db->join("ms_menu mm","mm.DID = mag.MenuID");
        $this->db->where("Email", $userid);
        $this->db->where("mm.MenuFile", $module);
        $result = $this->db->get()->row();

        return $result->$function == 1 ? true : false ;
    }

    function sendEmail($temp, $email, $kode)
    {

        $this->ci->load->library('email_template/emailotp');
        $data_template = $this->data_template($temp, $email, $kode);
        $template = $this->ci->emailotp->html($data_template);


        $config['useragent']        = 'PHPMailer';
        $config['protocol']            = 'smtp';
        $config['smtp_host']        = 'smtp.gmail.com';
        $config['smtp_port']        = 587;
        $config['smtp_crypto']      = 'tls';
        $config['smtp_timeout']     = '7';
        $config['smtp_user']        = 'nysoft.notif@gmail.com';
        $config['smtp_pass']        = 'X123456x';
        $config['charset']            = 'utf-8';
        $config['newline']            = "\r\n";
        $config['mailtype']         = 'html';
        $config['validation']         = TRUE;
        $config['smtp_debug']       = 1;

        $this->ci->email->initialize($config);

        $this->ci->email->from("nysoft.notif@gmail.com", "AMBARRUKMO");
        $this->ci->email->to($email);
        $this->ci->email->subject($data_template['subject']);
        $this->ci->email->message($template);

        if ($this->ci->email->send()) {
            //    echo $this->ci->email->print_debugger();
            //    die;
            return true;
        } else {
            return false;
        }
    }


    function get_menu($aclgroup = null)
    {
        $this->db->select("mm.DID
        ,mm.Menu
        ,mm.MenuFile
        ,mm.OrderNo,
        ,(SELECT ms.Menu FROM ms_menu ms WHERE mm.ParentID = ms.DID) as submenu
        ,(SELECT ms2.OrderNo FROM ms_menu ms2 WHERE mm.ParentID = ms2.DID) as orders
        ");
        $this->db->from("ms_menu mm");
        $this->db->join("ms_acl_group mag", "mag.MenuID = mm.DID");
        $this->db->where("mm.ParentID <> 0");
        $this->db->where("mag.ACLGroup", $aclgroup);
        $this->db->where("mag.Mview <> 0");
        $this->db->order_by("orders", "asc");
        $this->db->order_by("mm.OrderNo", "asc");
        $parent_menu = $this->db->get()->result_array();

        $grup_menu = array();
        foreach ($parent_menu as $val) {
            $grup_menu[$val["submenu"]][] = $val;
        }

        return $this->builMenu($grup_menu);
    }

    function builMenu($grup_menu)
    {
        $html = '';
        foreach ($grup_menu as $key => $values) {
            $sub = '';
            $arrayclass = array();
            foreach ($values as $val) {
                $link = current_url();
                $explode  = explode("/", $link);
                $gurl = $explode[4];

                if (strtolower($gurl) == strtolower($val["MenuFile"])) {
                    //if (strtolower($gurl) == strtolower($val["MenuFile"]) && strpos(strtolower($link), strtolower(base_url() . "backend/" . $val["MenuFile"])) !== false){
                    $active = 'class="nav-link active"';
                    array_push($arrayclass, $active);
                } else {
                    $active = 'class="nav-link"';
                }
                $sub .= '
                    <li class="nav-item">
                    <a href="' . base_url("backend/") . $val["MenuFile"] . '" ' . $active . '>
                        <i class="far fa-circle nav-icon" style="margin-left: 15px;"></i>
                        <p>' . $val["Menu"] . '</p>
                    </a>
                    </li>
                ';
            }

            if (in_array('class="nav-link active"', $arrayclass)) {
                $class = 'class="nav-item menu-is-opening menu-open"';
                $display = 'style="display: block;"';
                $activc = 'class="nav-link active"';
            } else {
                $class = 'class="nav-item"';
                $display = 'style="display: none;"';
                $activc = 'class="nav-link"';
            }

            $html .= '<li ' . $class . ' >
                        <a ' . $activc . '>
                            <i class="nav-icon far fa-plus-square"></i>
                            <p>' . $key . '</p>
                            <i class="right fas fa-angle-right"></i>
                        </a>
                        <ul class="nav nav-treeview " ' . $display . '>
                        ' . $sub . '
                        </ul>
                     </li>';
        }

        return $html;
    }
}

/* End of file auth.php */
/* Location: ./application/libraries/auth.php */