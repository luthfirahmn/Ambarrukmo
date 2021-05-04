<?php defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package   CodeIgniter
 * @subpackage  Rest Server
 * @category  Controller
 * @author    Phil Sturgeon
 * @link    http://philsturgeon.co.uk/code/
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Voucher extends REST_Controller
{
  function __construct()
  {
    parent::__construct();

    /*SET TIMEZONE*/
    date_default_timezone_set('Asia/Jakarta');
  }
  /* code respone 
      200 => ok ,
    201 => created ,
    400 => bad request ,
    401 => unauthorized ,
    403 => forbidden ,
    404 => notfound ,
    405 => methode not allowed ,

  */
  public function foo_get()
  {
     pre('this Voucher controller');
  }

  public function getvoucher_get()
  {
      try
      {
        $query = $this->db->query(" SELECT * ,CONCAT('VOUCHER/',VoucherIMG) as VoucherPath 
                                    FROM ms_voucher
                                 ");
        if($query===FALSE)
          throw new Exception();

          $result = $query->result();

            $data = array(
              'massage' => 'success',
              'error' => 'false',
              'data' => $result
            );

        if($result)
        {
          $this->response($data, 200);
        }
        else
        {
          $this->response($data, 404);
        }

      }
      catch(Exception $e)
      {

        print_r($this->db->_error_number());die;
      }

  }

  public function voucherdetail_post()
  {

    if (empty($this->post("DID"))) 
    {

        $response = array(
          'message' => 'Invalid params',
          'error' => true,
        );
        $this->response($response, 400);
    }

      try
      {

        $DID = $this->post("DID");
           $query = $this->db->query(" SELECT * ,CONCAT('VOUCHER/',VoucherIMG) as VoucherPath
                                    FROM ms_voucher
                                    WHERE DID ='{$DID}'
                                 ");
        if($query===FALSE)
          throw new Exception();

          $result = $query->row();
          $rbt  = date("d M", strtotime($result->RBT));
          $exptime  = date("d M Y", strtotime($result->ExpiredTime));
          $ExpiredVoucher = $rbt.' - '.$exptime;

            $data = array(
              'massage' => 'success',
              'error' => 'false',
              'data' => array('DID' => $result->DID,
                              'VoucherCode' => $result->VoucherCode, 
                              'VoucherName' => $result->VoucherName,
                              'VoucherIMG' => $result->VoucherIMG,  
                              'VoucherPath' => $result->VoucherPath, 
                              'VoucherShortNote' => $result->VoucherShortNote, 
                              'VoucherNote' => $result->VoucherNote, 
                              'RedeemPoint' => $result->RedeemPoint, 
                              'Qty' => $result->Qty, 
                              'QtyUsed' => $result->QtyUsed,
                              'ExpiredTime' => $result->ExpiredTime, 
                              'RedeemCode' => $result->RedeemCode, 
                              'Active' => $result->Active, 
                              'RBT' => $result->RBT,
                              'ExpiredVoucher' => $ExpiredVoucher, 
                              )
            );

        if($result)
        {
          $this->response($data, 200);
        }
        else
        {
          $this->response($data, 404);
        }

      }
      catch(Exception $e)
      {

        print_r($this->db->_error_number());die;
      }

  }


  public function vouchermemberlist_post()
  {

    if (empty($this->post("MemberID"))) 
    {

        $response = array(
          'message' => 'Invalid params',
          'error' => true,
        );
        $this->response($response, 400);
    }

      try
      {

         $MemberID = $this->post("MemberID");
         $query = $this->db->query(" SELECT * 
                                      ,tpoint.DID AS pDID
                                      ,tpoint.MemberID AS pMemberID
                                      ,tpoint.VoucherID AS pVoucherID
                                      ,tpoint.VoucherCode AS pVoucherCode
                                      ,CONCAT('VOUCHER/',VoucherIMG) AS VoucherPath
                                      ,CONCAT(DATE_FORMAT(VoucherBT, '%d %M'), ' - ' ,DATE_FORMAT(tpoint.ExpiredTime, '%d %M %Y')) AS ExpiredVoucher
                                      FROM tr_point AS tpoint
                                      LEFT JOIN ms_voucher AS voucher ON voucher.DID = tpoint.VoucherID

                                      WHERE tpoint.MemberID ='{$MemberID}'
                                      AND tpoint.VoucherID  != '0'

         ");
        if($query===FALSE)
          throw new Exception();

            $result = $query->result();

            $data = array(
              'massage' => 'success',
              'error' => 'false',
              'data' => $result,
            );

        if($result)
        {
          $this->response($data, 200);
        }
        else
        {
          $this->response($data, 404);
        }

      }
      catch(Exception $e)
      {

        print_r($this->db->_error_number());die;
      }

  }


  public function redeemvoucher_post()
  {
    $MemberID     = $this->post('MemberID');
    $RedeemCode   = $this->post('RedeemCode');
    $datenow      = date("Y-m-d H:i:s");

    /*CHECK PARAM*/
    if($RedeemCode == null && $MemberID == null)
    {
      $response = array(
        'massage' => 'invalid params..', 
        'error' => 'true'
      );
      $this->response($response, 400); 
    }

    /*CHECK MEMBER*/
    $sql = "  SELECT 
                    MemberID
                FROM ms_member 
                WHERE MemberID = '{$MemberID}'
                ";
    $CheckMember = $this->db->query($sql)->num_rows();

    if($CheckMember < 1)
    {
      $response = array(
        'massage' => 'Member Not Registered..', 
        'error' => 'true'
      );
      $this->response($response, 400); 
    }

    /*CHECK REDEEM CODE*/
    $sql = "  SELECT *
                FROM ms_voucher 
                WHERE RedeemCode = '{$RedeemCode}'
                ";

    $result = $this->db->query($sql)->row();
    $VoucherCode = $result->VoucherCode;

    if($result == false)
    {
      $response = array(
        'massage' => 'Voucher Not Found..', 
        'error' => 'true'
      );
      $this->response($response, 400); 
    }

    if ($result->Active == '0') 
    {
      $response = array(
        'massage' => 'Voucher Not Active..', 
        'error' => 'true'
      );
      $this->response($response, 400); 
    }

    if ($result->ExpiredTime < $datenow) 
    {
      $response = array( 
        'massage' => 'Voucher Expired..', 
        'error' => 'true'
      );
      $this->response($response, 400); 
    }

    /*UPDATE USED*/
    $data = array('VoucherUsed' => '1',
                  'VoucherUT'   => $datenow );
    $update = $this->db->where('VoucherCode',$VoucherCode)
                       ->update('tr_point',$data);

   if ($update) 
   {
      $response = array( 
        'massage' => 'Success', 
        'error' => 'false'
      );
      $this->response($response, 200); 
   }
   else
   {
      $response = array( 
        'massage' => 'Error', 
        'error' => 'true'
      );
      $this->response($response, 400); 
   }
   
  }


  public function buyvoucher_post()
  {

      try
      {
        /*GET POST*/
        $MemberID = $this->post('MemberID'); 
        $VoucherCode = $this->post('VoucherCode'); 

        /*CHECK PARAM*/
        if($MemberID == null && $VoucherCode == null)
        {
          $response = array(
            'massage' => 'invalid params..', 
            'error' => 'true'
          );
          $this->response($response, 404); 
        }

        /*MEMBER DATA FOUND*/
        $sql = $this->db->query("SELECT MemberID
                                  FROM ms_member
                                  WHERE MemberID = '{$MemberID}'
                                  ");
        $cekmember        = $sql->num_rows();

        /*CHECK MEMBER*/
        if($cekmember < 1)
        {
          $response = array(
            'massage' => 'Member Not Found..', 
            'error' => 'true'
          );
          $this->response($response, 400); 
        }

        /*VOUCHER DATA FOUND*/
        $sql = $this->db->query("SELECT VoucherCode
                                  FROM ms_voucher
                                  WHERE VoucherCode = '{$VoucherCode}'
                                  ");
        $cekvoucher        = $sql->num_rows();

        /*CHECK VOUCHER*/
        if($cekvoucher < 1)
        {
          $response = array(
            'massage' => 'Voucher Not Found..', 
            'error' => 'true'
          );
          $this->response($response, 400); 
        }

        /*AVAIBLE TOTAL POINT ON MEMBER*/
        $sql = $this->db->query("SELECT TotalPoint
                                  FROM ms_member
                                  WHERE MemberID = '{$MemberID}'
                                  ");
        $totalpoint        = $sql->row()->TotalPoint;




        /*SELECT DATA FROM MEMBER*/
        $sql = "SELECT *
                FROM ms_member
                WHERE MemberID = '{$MemberID}'
                ";
        $rs        = $this->db->query($sql);
        $email     = $rs->row()->Email;

        /*SELECT DATA FROM VOUCHER*/
        $sql = "SELECT *
                FROM ms_voucher
                WHERE VoucherCode = '{$VoucherCode}'
                ";
        $rs           = $this->db->query($sql);
        $all_data     = $rs->result();
        $active       = $rs->row()->Active;
        $expired      = $rs->row()->ExpiredTime;
        $datenow      = date("Y-m-d H:i:s");
        $qtyused      = $rs->row()->QtyUsed;
        $qtyusedAdd   = $qtyused + 1;
        $qty          = $rs->row()->Qty;
        $redeempoint  = $rs->row()->RedeemPoint;
        $point        = -($redeempoint);
        $VoucherID    = $rs->row()->DID;

        $sumpoint     = intval($totalpoint) - intval($redeempoint);

        if($sumpoint < 0)
        {
           $response = array(
            'massage' => 'Point not enough..', 
            'error' => 'true'
          );
          $this->response($response, 200); 
        }

        /*CHECK ACTIVE VOUCHER */
        if ($active != '1') 
        {
          $response = array(
            'massage' => 'voucher not active..', 
            'error' => 'true'
          );
          $this->response($response, 200); 
        }
        
        /*CHECK EXPIRED VOUCHER*/
        else if ($expired < $datenow) 
        {
          $response = array(
            'massage' => 'voucher expired..', 
            'error' => 'true'
          );
          $this->response($response, 200); 
        }

        /*CHECK QTY USED*/
        else if ($qtyused >= $qty) 
        {
          $response = array(
            'massage' => 'voucher sold..', 
            'error' => 'true'
          );
          $this->response($response, 200); 
        }

        else
        {

        /*SELECT DATA FROM VOUCHER*/
        $sql = "SELECT *
                FROM tr_point
                WHERE VoucherCode = '{$VoucherCode}'
                ";
        $rs               = $this->db->query($sql)->row();
        $tr_member        = $rs->MemberID;
        $tr_trxdate       = $rs->TRXDate;
        $tr_point         = $rs->Point;
        $tr_vouchercode   = $rs->VoucherCode;
        $tr_bt            = $rs->VoucherBT;
        $tr_expired       = $rs->ExpiredTime;


        }

        /*BEGIN TRANS*/
        $this->db->trans_begin();

        /*INSERT TO tr_point*/
        $insert = $this->db->query(" INSERT INTO tr_point( MemberID, TRXDate, Point, RuleType, VoucherID, VoucherCode, VoucherBT, ExpiredTime )
                                      SELECT 
                                        '{$MemberID}' as MemberID
                                        ,'{$datenow}' as TRXDate
                                        ,'{$point}' as Point
                                        , NULL as RuleType
                                        ,'{$VoucherID}' as VoucherID
                                        ,'{$VoucherCode}' as VoucherCode
                                        ,'{$datenow}' as VoucherBT
                                        ,'{$expired}' as ExpiredTime
                                    "); 
        /*UPDATE QTY USED ON VOUCHER*/
        $update_voucher = $this->db->query(" UPDATE  ms_voucher
                                              SET
                                                QtyUsed = '{$qtyusedAdd}'
                                              WHERE
                                                VoucherCode = '{$VoucherCode}'
                                            ");
        /*UPDATE TOTAL POINT ON MEMBER*/
        $update_member = $this->db->query(" UPDATE  ms_member
                                              SET
                                                TotalPoint = TotalPoint-'{$redeempoint}'
                                              WHERE
                                                MemberID = '{$MemberID}'
                                            ");


        /*TRANS ROLL BACK*/
        if ($this->db->trans_status() === FALSE) 
        {
          $this->db->trans_rollback();
        } 
        else 
        {
          $this->db->trans_commit();
        }

        /*RESPONS insert*/
        if($insert && $update_voucher && $update_member)
        {

          /*SEND EMAIL*/
          // Load PHPMailer library
          $this->load->library('phpmailer_lib');

          // PHPMailer object
          $mail = $this->phpmailer_lib->load();

          // SMTP configuration
          $mail->isSMTP();
          $mail->Host     = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'nysoft.notif@gmail.com';
          $mail->Password = 'X123456x';
          $mail->SMTPSecure = 'ssl';
          $mail->Port     = 465;

          $mail->setFrom('nysoft.notif@gmail.com', 'AMBARRUKMO');

          // Add a recipient
          $mail->addAddress($email);

          // Email subject
          $mail->Subject = 'Voucher Info';

          // Set email format to HTML
          $mail->isHTML(true);

          // Email body content
          $mailContent = "
              <h3>Voucher Information</h4>

              <h4>MEMBER ID : $tr_member</h4>
              <h4>TRX DATE  : $tr_trxdate</h4>
              <h4>POINT : $tr_point</h4>
              <h4>VOUCHER CODE : $tr_vouchercode</h4>
              <h4>Buy Time : $tr_bt</h4>
              <h4>Expired : $tr_expired</h4>
              ";
          $mail->Body = $mailContent;

          // Send email
          if(!$mail->send())
          {
              $response = array(
              'massage' => 'error..'. $mail->ErrorInfo, 
              'error' => 'true'
              );
              $this->response($response, 400); 
          }
          else
          {
              /*RESPONSE*/
            $response = array(
              'massage' => 'Success', 
              'error' => 'false'
            );
            $this->response($response, 200); 
          }



          
        }
        else
        {
            $response = array(
              'massage' => 'Error', 
              'error' => 'false'
            );
            $this->response($response, 400); 
        }
    }
    catch(Exception $e)
    {

      print_r($this->db->_error_number());die;
    }
  }



}
