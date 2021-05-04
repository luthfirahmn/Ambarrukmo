

<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class CheckPoint extends CI_Controller
{
  public function index()
  {
	   $sql =  $this->db->query("SELECT tr_point.MemberID AS MemberID, FullName, TotalPoint, SUM(Point) AS CountPoint
								 FROM tr_point
								 LEFT JOIN ms_member ON tr_point.MemberID = ms_member.MemberID
								 GROUP BY MemberID
								");
	   $result = $sql->result_array();
		 	

				//SENDMAIL
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
		        //$mail->addAddress('nl@onedeca.com');
		        $mail->addAddress('luthfirrahman123@gmail.com');
		        // Email subject
		        $mail->Subject = 'Data Versus';

		        // Set email format to HTML
		        $mail->isHTML(true);

		        // Email body content
		        $mailContent = "
		        <html>
		        <head>
		        <style>
				table, td, th {
				  border: 1px solid black;
				}
				td {
					padding-left:4px;
				}

				table {
				  width: 100%;
				  border-collapse: collapse;
				}
				</style>
				</head>
		        <body>
		            <h3>List Data Versus</h3>   
		            <table > 
		                <thead>
		                    <tr>
		                        <th>Member ID</th>
		                        <th>Member Name</th>
		                        <th>Total Point</th>
		                        <th>CountPoint</th>
		                    </tr>    
		                </thead>
		                <tbody>";
		                    foreach($result as $row) {
    			                $MemberID =  $row['MemberID'];
								$CountPoint =  $row['CountPoint']; 
								$FullName =  $row['FullName']; 
								$TotalPoint =  $row['TotalPoint']; 
								/*$sql = $this->db->query("SELECT TotalPoint,FullName FROM ms_member WHERE MemberID = '{$MemberID}' AND TotalPoint != '{$CountPoint}'")->get->result_array();*/
		                         $mailContent .="<tr>
		                            <td>".$MemberID ."</td>
		                            <td>".$FullName ."</td>
		                            <td>".$TotalPoint ."</td>
		                            <td>".$CountPoint ."</td>
		                        </tr>";
		                     	} 
		                $mailContent .= "</tbody>
		            </table>
		            <hr />     
		        </body>
		        </html>"; 
		        //end of $message
		        $mail->Body = $mailContent;

		        // Send email
		        if(!$mail->send())
		        {
		           echo "error";
		        }
		        else
		        {
		            //RESPONSE
	   	            echo "success";
		        }
		

  }

}