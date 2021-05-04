<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }
	public function email()
	{

		/*SELECT MEMBER*/
		$member = $this->db->query ("SELECT Email
									 FROM ms_member
								   ");
		$result = $member->result_array();

		/*SELECT NOTIF*/
		$datenow = date('Y-m-d H:i');
		$notif = $this->db->query ("SELECT *
									FROM tr_notification
									WHERE SendTime = '{$datenow}'
									AND NotifType != 'SMS'
									AND	NotifType != 'NOTIF APP'
									AND SendEmailStatus != '1'
								  ");
		$res = $notif->result();

		if ($res == NULL) 
		{
			echo "No Email Send";
		}


		foreach ($res as $res) {


	         
			/*SEND EMAIL*/
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
	        foreach ($result as $row) {
			$add = $mail->AddAddress($row['Email']);
			}

	        // Email subject
	        $mail->Subject = 'Notification';

	        // Set email format to HTML
	        $mail->isHTML(true);

	        // Email body content
	        $mailContent = "
	            <h3>Notification</h4>

	            <h4>Message : $res->Message</h4>
	            ";
	        $mail->Body = $mailContent;

	        // Send email
	        if(!$mail->send())
	        {
		        
	           echo "error";
	        }
	        else
	        {

         	/*UPDATE STATUS*/
	         $data = array('SendEmailStatus' => '1', );
	         $this->db->where('DID',$res->DID);
	         $this->db->update('tr_notification',$data);

	         /*RESPONSE*/
	          echo "success";

	        }

    	}
			 
	}

	public function notifApp()
	{

		/*SELECT NOTIF*/
		$datenow = date('Y-m-d H:i:s');
		$notif = $this->db->query ("SELECT *
									FROM tr_notification
									WHERE SendTime = '{$datenow}'
									AND NotifType != 'SMS'
									AND	NotifType != 'EMAIL'
									AND SendNotifAppStatus != '1'

								  ");
		$res = $notif->result();

		foreach ($res as $row) 
		{

			$appID = "dd894508-0c00-4ae1-bf92-9bbcbc878372";
			$PlayerID = "";
			$Title = "Informasi";
			$Message = $row->Message;

			OnesignalNotification($appID,$PlayerID,$Title,$Message);

			/*UPDATE STATUS*/

			/*END UPDATE*/
		}		 
	}

	public function sms()
	{
		ob_start();
		// setting

		$apikey        = '123465xxxxxx'; // api key
		$urlendpoint = 'http://domainname.xyz/sms/api_sms_masking_send_json.php'; // url endpoint api
		$callbackurl = 'http://your_url_for_get_auto_update_status_sms'; // url callback get status sms 

		// create header json 
		$senddata = array(
		'apikey' => $apikey, 
		'callbackurl' => $callbackurl,
		'datapacket'=>array()
		);


		/*SELECT MEMBER*/
		$member = $this->db->query ("SELECT Email
									 FROM ms_member
								   ");
		$result = $member->result_array();

		/*SELECT DATA*/
		$datenow = date('Y-m-d H:i:s');
		$sql = $this->db->query ("SELECT *
									FROM tr_notification
									WHERE SendTime = '{$datenow}'
									AND NotifType != 'NOTIF APP'
									AND	NotifType != 'EMAIL'
									AND	NotifType != 'EMAIL & NOTIF APP'
									AND SendSMSStatus != '1'

								  ");
		$res = $sql->result();

		foreach ($res as $res ) {
			
			$number = $res->MobilePhone;
			// create detail data json
			$number='$row["Email"]';
			$message='$res->Message';
			$sendingdatetime ="$res->sendTime";
			array_push($senddata['datapacket'],array(
			'number' => trim($number1),
			'message' => urlencode(stripslashes(utf8_encode($message1))),
			'sendingdatetime' => $sendingdatetime1));

			// send sms
			$data=json_encode($senddata);
			$curlHandle = curl_init($urlendpoint);
			curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data))
			);

			curl_setopt($curlHandle, CURLOPT_TIMEOUT, 5);
			curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 5);
			$responjson = curl_exec($curlHandle);
			curl_close($curlHandle);
			header('Content-Type: application/json');
			echo $responjson;

			/*UPDATE STATUS*/
		}
		
	}


}