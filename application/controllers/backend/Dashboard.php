<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('logged_in')) {
      redirect('backend/login');
    }
  }

  public function index()
  {
    $config["content_file"] = "ms_dashboard/index";
    $config["content_data"] = array();

    $this->template->initialize($config);
    $this->template->render();
  }

  public function get_data_dashboard()
  {
    if (!$this->input->is_ajax_request()) {
      exit('No direct script access allowed');
    }
    //pre("ok");

    $year       = date("Y");

    /* START GET TOTAL MEMBER JIONING THIS YEAR */
    $this->db->select("count(MemberID) as total, MID(JoinDate, 1, 4 ) as year");
    $this->db->from("ms_member");
    $this->db->where("MID(JoinDate, 1, 4 ) = {$year}");
    $member_jioning_this_year = $this->db->get()->row();
    // pre($member_jioning_this_year->total);
    /* END GET TOTAL MEMBER JIONING THIS YEAR */

    /* START GET TOTAL EARNED POINT THIS YEAR */
    $this->db->select("sum(Point) as total, MID(TRXDate, 1, 4 ) as year");
    $this->db->from("tr_point");
    $this->db->where("MID(TRXDate, 1, 4 ) = {$year}");
    $earned_point_this_year = $this->db->get()->row();
    /* START GET TOTAL EARNED POINT THIS YEAR */


    /* START GET DATA CART  MEMBER JIONING THIS YEAR */
    $this->db->select("IFNULL(count(MemberID),0) as total, IFNULL(MID(JoinDate, 6, 2 ),0) as month");
    $this->db->from("ms_member");
    $this->db->group_by("month");
    $this->db->order_by('month', 'ASC');
    $member  = $this->db->get()->result_array();
    /* END GET DATA CART  MEMBER JIONING THIS YEAR */


    /* START GET DATA CART  MEMBER JIONING PER YEAR */
    $this->db->select("IFNULL(count(MemberID),0) as total, IFNULL(MID(JoinDate, 1, 4 ),0) as year");
    $this->db->from("ms_member");
    $this->db->group_by("year");
    $this->db->order_by('year', 'ASC');
    $member_year  = $this->db->get()->result_array();

    $data_member_carts = get_month_array($member);
    $data_member_cart_blue = get_year_array($member_year);  

    $output = array(
      "status"                      => true,
      "message"                     => "Success ",
      "member_jioning_this_year"    => $member_jioning_this_year,
      "earned_point_this_year"      => $earned_point_this_year,
      "data_member_carts"           => $data_member_carts["month"],
      "data_member_carts_total"     => $data_member_carts["total"],
      "data_member_cart_blue"       => $data_member_cart_blue["year"],
      "data_member_cart_blue_total" => $data_member_cart_blue["total"],
      "data_member_donut"           => array((int) $member_jioning_this_year->total, (float) $earned_point_this_year->total, 20),

    );
    echo json_encode($output);
    die;
  }
}
