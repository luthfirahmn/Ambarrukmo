<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('get_auth_lib')) {
	function get_auth_lib()
	{
		$ci = &get_instance();
		$ci->load->library('auth');
		return $ci->auth;
	}
}

if (!function_exists('get_api_lib')) {
	function get_api_lib()
	{
		$ci = &get_instance();
		$ci->load->library('api');
		return $ci->api;
	}
}



if (!function_exists("pre")) {
	function pre($param = array())
	{
		echo "<PRE>";
		print_r($param);
		exit;
	}
}


if (!function_exists('get_random_string')) {
	function get_random_string($valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", $length = 2)
	{
		// start with an empty random string
		$random_string = "";

		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++) {
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick - 1];

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

		// return our finished random string
		return $random_string;
	}
}


if (!function_exists("dateSekarang")) {
	function dateSekarang($act = 1, $param = FALSE, $convert_normal_date = FALSE)
	{
		if (!empty($param) and $convert_normal_date)
			$param = trim(str_replace(array("T", "Z"), " ", $param));

		if ($param == "0001-01-01 00:00:00")
			return "-";

		if ($act == 1) {
			return date("Y-m-d H:i:s");
		} else if ($act == 2) {
			return date("Y-m-d");
		} else if ($act == 3) {
			return date("d F Y H:i", strtotime($param));
		} else if ($act == 4) {
			return date("d F Y", strtotime($param));
		} else if ($act == 5) {
			return date("Y/m/d");
		} else if ($act == 6) {
			return date("d/m/Y H:i");
		} else if ($act == 7) {
			$paramex = explode("/", substr($param, 0, 10));
			$jam = substr($param, 11, 6);
			return "{$paramex[2]}-{$paramex[1]}-{$paramex[0]} {$jam}";
		} else if ($act == 8) {
			return date("d M Y");
		} else if ($act == 9) {
			return date("Ymd");
		} else if ($act == 10) {
			$paramex = explode("/", substr($param, 0, 10));
			return "{$paramex[2]}-{$paramex[1]}-{$paramex[0]}";
		} else if ($act == 11) {
			$paramex = explode("-", substr($param, 0, 10));
			return "{$paramex[2]}-{$paramex[1]}-{$paramex[0]}";
		} else if ($act == 12) {
			return date("d F Y H:i:s", strtotime($param));
		} else if ($act == 13) {
			return date("d F Y", strtotime($param));
		} else if ($act == 14) {
			return date("H:i", strtotime($param));
		} else if ($act == 15) {
			return date("d", strtotime($param));
		} else if ($act == 16) {
			return date("m", strtotime($param));
		} else if ($act == 17) {
			return date("Y", strtotime($param));
		} else if ($act == 18) {
			return date("Y-m-d H:i:s", strtotime($param));
		} else if ($act == 19) {
			return date("Y-m-d", strtotime($param));
		} else if ($act == 20) {
			return date("Y-m-d H:i", strtotime($param));
		} else if ($act == 21) {
			return date("d F Y H:i", strtotime($param));
		} else if ($act == 22) {
			return date("Y-m-d\TH:i:s\Z", strtotime($param));
		}
	}


	if (!function_exists('notify_message')) {
		function notify_message($success_msg = "", $error_message = "", $info_message = "")
		{
			$result = "";
			if ($success_msg)
				$result =  '<div class="alert alert-success">
						<button class="close" data-dismiss="alert">&times;</button>
						<strong>Success!</strong> ' . $success_msg . '
						</div>';
			if ($error_message)
				$result =  '<div class="alert alert-danger">
						<button class="close" data-dismiss="alert">&times;</button>
						<strong>Error!</strong> ' . $error_message . '
						</div>';
			if ($info_message)
				$result =  '<div class="alert alert-info">
						<button class="close" data-dismiss="alert">&times;</button>
						<strong>Info!</strong> ' . $info_message . '
						</div>';
			return $result;
		}
	}

	if (!function_exists("group_by_array")) {
		function group_by_array($array, $key)
		{
			$return = array();
			foreach ($array as $val) {
				$return[$val[$key]][] = $val;
			}
			return $return;
		}
	}

	if (!function_exists('get_menu_module')) {
		function get_menu_module($userid = NULL)
		{
			$modules = array();
			$modules = get_auth_lib()->get_all_module($userid);

			return $modules;
		}
	}

	if (!function_exists('privilage')) {
		function privilage($userid = NULL, $module = NULL, $function = NULL)
		{
			$allowed = get_auth_lib()->privilage($userid, $module, $function);
			return $allowed;
		}
	}


	if (!function_exists('is_allowed')) {
		function is_allowed($userid = NULL, $module = NULL, $function = NULL)
		{
			$allowed = get_auth_lib()->is_allowed($userid, $module, $function);
			return $allowed;
		}
	}



	if (!function_exists('get_user')) {
		function get_user($userid = NULL)
		{
			$allowed = get_auth_lib()->get_user($userid);
			return $allowed;
		}
	}

	if (!function_exists('tr_log')) {
		function tr_log($logquery = null, $logpage = null, $rbu = null)
		{
			$data["LogQuery"]	= $logquery;
			$data["LogPage"]    = $logpage;
			$data["RBU"] 		= $rbu;
			$data["RBT"] 		= date("Y-m-d H:i:s");

			$result = get_auth_lib()->insert_log($data);

			return $result;
		}
	}


	if (!function_exists('sendOTP')) {
		function sendOTP($temp = null, $email = null, $kode = null)
		{
			$result = get_auth_lib()->sendEmail($temp, $email, $kode);
			return $result;
		}
	}


	


	if (!function_exists('base64Image')) {
		function base64Image($pathImage = null)
		{
			$path = IMAGE_BLOCK_APPS_ROOT . $pathImage;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$dataimg = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);

			return $base64;
		}
	}




	if (!function_exists('resizeImage')) {
		function resizeImage($SrcImage, $DestImage, $MaxWidth, $MaxHeight, $Quality)
		{
			list($iWidth, $iHeight, $type)      = getimagesize($SrcImage);
			$ImageScale                         = min($MaxWidth / $iWidth, $MaxHeight / $iHeight);
			$NewWidth                           = ceil($ImageScale * $iWidth);
			$NewHeight                          = ceil($ImageScale * $iHeight);
			$NewCanves                          = imagecreatetruecolor($NewWidth, $NewHeight);

			switch (strtolower(image_type_to_mime_type($type))) {
				case 'image/jpeg':
					$NewImage = imagecreatefromjpeg($SrcImage);
					break;
				case 'image/png':
					$NewImage = imagecreatefrompng($SrcImage);
					break;
				case 'image/gif':
					$NewImage = imagecreatefromgif($SrcImage);
					break;
				case 'image/jpg':
					$NewImage = imagecreatefromgif($SrcImage);
					break;
				default:
					return false;
			}

			// Resize Image
			if (imagecopyresampled($NewCanves, $NewImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight)) {
				// copy file
				if (imagejpeg($NewCanves, $DestImage, $Quality)) {
					imagedestroy($NewCanves);
					return true;
				}
			}
		}
	}

	if (!function_exists('Buttons')) {
		function Buttons($buttons = null, $func = null)
		{
			switch ($buttons) {
				case "delete":
					$button = '<button onclick="' . $func . '" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
					break;
				case "edit":
					$button = '<button onclick="' . $func . '" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>';
					break;
				case "actived":
					$button = '<button onclick="' . $func . '" class="btn btn-warning btn-sm" style="width: 36px; height:30px; "><i class="fas fa-eye"></i></button>';
					break;
				case "disabled":
					$button = '<button onclick="' . $func . '" class="btn btn-warning btn-sm" style="width: 36px; height:30px;"><i class="fas fa-eye-slash"></i></button>';
					break;
				case "check":
					$button = '<button onclick="' . $func . '" class="btn btn-danger btn-sm " style="width: 36px; height:30px;"><i class="fa fa-times"></i></button>';
					break;
				case "checked":
					$button = '<button onclick="' . $func . '" class="btn btn-success btn-sm" style="width: 36px; height:30px;"><i class="fa fa-check"></i></button>';
					break;
				case "used":
					$button = '<button onclick="' . $func . '" class="btn btn-success btn-sm " style="width: 80px; height:30px;">Used</button>';
					break;
				case "notused":
					$button = '<button onclick="' . $func . '" class="btn btn-danger btn-sm" style="width: 80px; height:30px;">Not Used</button>';
					break;
				case "notsendStatus":
					$button = '<button onclick="' . $func . '" class="btn btn-danger btn-sm " style="width: 36px; height:30px;"><i class="fa fa-times"></i></button>';
					break;
				case "sendStatus":
					$button = '<button onclick="' . $func . '" class="btn btn-success btn-sm" style="width: 36px; height:30px;"><i class="fa fa-check"></i></button>';
					break;
				case "header":
					$button = '<button onclick="' . $func . '" class="btn btn-success btn-sm" style="width: 65px; height:30px;">Header</button>';
					break;
				case "footer":
					$button = '<button onclick="' . $func . '" class="btn btn-danger btn-sm" style="width: 65px; height:30px;">Footer</button>';
					break;


				default:
					$button = '<button onclick="' . $func . '" class="btn btn-info btn-sm"><i class="fas fa-list"></i></button>';
			}
			return $button;

			//   $row[] = $val->Active == 1 ? Buttons("disabled", "myActive($val->DID,1)"):Buttons("actived", "myActive($val->DID,0)");
			//   $row[] = Buttons("delete", "myActive($val->DID,1)").Buttons("edit", "myActive($val->DID,1)");

		}
	}

	if (!function_exists('OnesignalNotification')) {
		function OnesignalNotification($appID, $PlayerID, $Title, $Message)
		{
			$content = array(
				"en" => $Message
			);
			$heading = array(
				"en" => $Title
			);
			$fields = array(
				'app_id' => $appID,
				'include_player_ids' => array("$PlayerID"),
				'data' => array("foo" => "bar"),
				'contents' => $content,
				'headings' => $heading
			);

			$fields = json_encode($fields);
			// print("\nJSON sent:\n");
			// print($fields);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$response = curl_exec($ch);
			curl_close($ch);

			return $response;
		}
	}

	if (!function_exists('get_menu')) {
		function get_menu($userid = NULL)
		{
			$allowed = get_auth_lib()->get_menu($userid);
			return $allowed;
		}
	}

	if (!function_exists('get_user')) {
		function get_user($userid = NULL)
		{
			$user = get_auth_lib()->get_user($userid);
			return $user;
		}
	}
	    
    if ( ! function_exists('bulan'))
    {
        function bulan($bln)
        {
            switch ($bln)
            {
                case 1:
                    return "January";
                    break;
                case 2:
                    return "February";
                    break;
                case 3:
                    return "March";
                    break;
                case 4:
                    return "April";
                    break;
                case 5:
                    return "May";
                    break;
                case 6:
                    return "June";
                    break;
                case 7:
                    return "July";
                    break;
                case 8:
                    return "August";
                    break;
                case 9:
                    return "September";
                    break;
                case 10:
                    return "October";
                    break;
                case 11:
                    return "November";
                    break;
                case 12:
                    return "December";
                    break;
            }
        }
    }

	if ( ! function_exists('medium_bulan'))
    {
        function medium_bulan($bln)
        {
            switch ($bln)
            {
                case 1:
                    return "Jan";
                    break;
                case 2:
                    return "Feb";
                    break;
                case 3:
                    return "Mar";
                    break;
                case 4:
                    return "Apr";
                    break;
                case 5:
                    return "Mei";
                    break;
                case 6:
                    return "Jun";
                    break;
                case 7:
                    return "Jul";
                    break;
                case 8:
                    return "Ags";
                    break;
                case 9:
                    return "Sep";
                    break;
                case 10:
                    return "Okt";
                    break;
                case 11:
                    return "Nov";
                    break;
                case 12:
                    return "Des";
                    break;
            }
        }
    }

	if ( ! function_exists('short_bulan'))
    {
        function short_bulan($bln)
        {
            switch ($bln)
            {
                case 1:
                    return "01";
                    break;
                case 2:
                    return "02";
                    break;
                case 3:
                    return "03";
                    break;
                case 4:
                    return "04";
                    break;
                case 5:
                    return "05";
                    break;
                case 6:
                    return "06";
                    break;
                case 7:
                    return "07";
                    break;
                case 8:
                    return "08";
                    break;
                case 9:
                    return "09";
                    break;
                case 10:
                    return "10";
                    break;
                case 11:
                    return "11";
                    break;
                case 12:
                    return "12";
                    break;
            }
        }
    }

	if ( ! function_exists('longdate_indo'))
    {
        function longdate_indo($tanggal)
        {
            $ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-",$ubah);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            $bulan = bulan($pecah[1]);
      
            $nama = date("l", mktime(0,0,0,$bln,$tgl,$thn));
            $nama_hari = "";
            if($nama=="Sunday") {$nama_hari="Sun";}
            else if($nama=="Monday") {$nama_hari="Mon";}
            else if($nama=="Tuesday") {$nama_hari="Tue";}
            else if($nama=="Wednesday") {$nama_hari="Wed";}
            else if($nama=="Thursday") {$nama_hari="Thu";}
            else if($nama=="Friday") {$nama_hari="Fri";}
            else if($nama=="Saturday") {$nama_hari="Sat";}
            return $nama_hari.', '.$bulan.' '.$tgl.',  '.$thn;
        }
    }

	
    if ( ! function_exists('mediumdate_indo'))
    {
        function mediumdate_indo($tgl)
        {
            $ubah = gmdate($tgl, time()+60*60*8);
            $pecah = explode("-",$ubah);
            $tanggal = $pecah[2];
            $bulan = medium_bulan($pecah[1]);
            $tahun = $pecah[0];
            return $tanggal.'-'.$bulan.'-'.$tahun;
        }
    }

	if (!function_exists('get_month_array')) {
		function get_month_array($member = NULL)
		{
			$data["month"] = array();
			$data["total"] = array();

			if ((int) end($member)["month"] >= 6) {
				$start = (int) end($member)["month"] - 6;
				for ($i = $start + 1; $i <= (int) end($member)["month"]; $i++) {
					$total = 0;
					$data["month"][] = bulan($i);
					for ($m = 0; $m < count($member); $m++) {
						if ($i == $member[$m]["month"]) {
							$total = $member[$m]["total"];
						}
					}
					$data["total"][] = $total;
				}
			} else {
				for ($i = 1; $i <= (int) end($member)["month"]; $i++) {
					$total = 0;
					$data["month"][] = bulan($i);
					for ($m = 0; $m < count($member); $m++) {
						if ($i == $member[$m]["month"]) {
							$total = $member[$m]["total"];
						}
					}
					$data["total"][] = $total;
				}
			}

			return $data;
		}
	}


	if (!function_exists('log_api')) {
		function log_api($LogType = null, $LogRequest = null, $LogResponse = null, $LogIP = null)
		{
			$data["LogType"]		= $LogType;
			$data["LogRequest"]    	= $LogRequest;
			$data["LogResponse"]    = $LogResponse;
			$data["LogIP"] 			= $LogIP;
			$data["RBT"] 			= date("Y-m-d H:i:s");

			$result = get_api_lib()->insert_log_api($data);

			return $result;
		}
	}

	if (!function_exists('get_year_array')) {
		function get_year_array($member_year = NULL)
		{
			$start = end($member_year)["year"] - 2;
			for ($i = $start; $i <= end($member_year)["year"]; $i++) {
				$total = 0;
				$data["year"][] = "Tahun - " . $i;
				for ($y = 0; $y < count($member_year); $y++) {
					if ((int) $i == $member_year[$y]["year"]) {
						$total = $member_year[$y]["total"];
					}
				}
				$data["total"][] = $total;
			}

			return $data;
		}
	}
}
