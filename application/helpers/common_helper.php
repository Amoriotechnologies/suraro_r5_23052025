<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	if(!function_exists('getInstance')) { /* init getinstance */
		function getInstance() {
			$CI = & get_instance();
			return $CI;
		}
	}
	if (!function_exists('ImageUpload')) {
	    function ImageUpload($imagename, $path, $width = "", $height = "") {
	        $new_name = 'blog_' . time();
	        $config['file_name'] = $new_name;
	        $config['upload_path'] = $path;
	        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
	        $config['max_size'] = 2048;
	        $config['max_width'] = $width;
	        $config['max_height'] = $height;
	        getInstance()->load->library('upload', $config);
	        if (!$CI->upload->do_upload($imagename)) {
	            $error = array('status' => 400, 'msg' => $CI->upload->display_errors('', '')); 
	            return $error;
	        } else {
	            $data = array('status' => 200, 'msg' => $CI->upload->data()); 
	            return $data;
	        }
	    }
	}
	if(!function_exists('showDate')) { /* format date for frontend view */
		function showDate($date) {
			$length = strlen($date);
			$showdate = "";
			if(!empty($date) && $date != "0000-00-00" && $date != "0000-00-00 00:00:00" && $date != "1970-01-01") {
				$format = 'm/d/Y';
				if($length > 11) { $format = 'm/d/Y h:i A'; }
				$showdate = date($format, strtotime($date));
			}
			return $showdate;
		}
	}
	if(!function_exists('showTime')) { /* format date for frontend view */
		function showTime($time) {
			$showtime = "";
			if(!empty($time) && $time != "00:00" && $time != "00:00:00") {
				$format = 'h:i A';
				$showtime = date($format, strtotime($time));
			}
			return $showtime;
		}
	}
	if(!function_exists('TimeDuration')) { /* format date for db store */
		function TimeDuration($times, $duration) {
			$timedura = "";
			if(!empty($times) && !empty($duration)) {
				$splitTime = explode(', ', $times);
				foreach($splitTime as $time) {
					$timedura .= date('h:i A', strtotime($time.' +'.$duration.' minutes'));
				}
			}
			return $timedura;	
		}
	}
	if(!function_exists('struDate')) { /* format date for db store */
		function struDate($date) {
			$length = strlen($date);
			$date = str_replace([' ', '/'], '-', $date);
			$strudate = "";
			if(!empty($date) && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
				$format = 'Y-m-d';
				if($length > 11) { $format = 'Y-m-d H:i'; }
				$strudate = date($format, strtotime($date));
			}
			return $strudate;	
		}
	}
	if(!function_exists('alertMsg')) { /* return alert msg color & sentence */
		function alertMsg($name) {
			$color = [
				'add_suc' => 'success',
				'add_fail' => 'danger' ,
				'login_secc' => 'success', 
				'up_suc' => 'success',
				'log_fail' => 'danger',
			];
			$word = [
				'add_suc' => 'Added Successfully!!!',
				'add_fail' => 'Added Failed!!!' ,
				'login_secc' => 'Login Successfully!!!', 
				'up_suc' => 'Updated Successfully!!!',
				'log_fail' => 'Email / Password Incorrect!',
			];
			$alert_msg = [];
			if(in_array($name, array_keys($word))) {
				$alert_msg = ['color' => $color[$name], 'word' => $word[$name]];
			}
			return $alert_msg;
		}
	}
	if(!function_exists('mintoHour')) { /* convert minutes to hour(s) */
		function mintoHour($mins) {
			$hours = (int)((float)$mins / 60);
			$hour = ($hours < 9) ? '0'.$hours : $hours;
			$min = ($mins - ($hours * 60));
			$mins = ($min < 9) ? '0'.$min : $min;
			return $hour.':'.$mins;
		}
	}
	if(!function_exists('isSame')) { /* checking value for select dropdown */
		function isSame($var1, $var2) {
			return ($var1 == $var2) ? 'selected' : '';
		}
	}
	if(!function_exists('DateDiff')) { /* return days count of btw dates */
		function DateDiff($date1, $date2) {
			$start = struDate($date1);
			$end = struDate($date2);
			$diff = date_diff(date_create($start), date_create($end));
			return $diff->format("%a");			
		}
	}
	if(!function_exists('ExistorNot')) { /* return existing value */
		function ExistorNot($table, $where) {
			getInstance()->load->model('Common_model');
			$result = getInstance()->Common_model->GetDatas($table, '*', $where);
			return $result;
		}
	}
	if(!function_exists('AgeCal')) { /* return year diff count */
		function AgeCal($dob) {
			return ((int)date('Y') - (int)date('Y', strtotime($dob)));
		}
	}
	if(!function_exists('GetWishes')) { /* get today bday and anniversary detail */
		function GetWishes() {
			getInstance()->load->model('Common_model');
			$wishdate = date('m-d');
			$wishes_record = getInstance()->Common_model->GetDatas('customers', "`fld_name`, `fld_phone`, `fld_email`, DATE_FORMAT(`fld_dob`, '%m-%d') as `fld_dob`, `fld_dob` as `bday`, DATE_FORMAT(`fld_anniversary`, '%m-%d') as `fld_anniversary`, `fld_anniversary` as `aday`", "(DATE_FORMAT(`fld_dob`, '%m-%d') = '".$wishdate."') OR ( DATE_FORMAT(`fld_anniversary`, '%m-%d') = '".$wishdate."')");
			$wish_data = [];
			if(!empty($wishes_record)) {
				foreach($wishes_record as $wish) {
					if($wish['fld_dob'] == $wishdate) { 
						$wish_data['birth_days'][] = ['name' => $wish['fld_name'], 'phone' => $wish['fld_phone'], 'email' => $wish['fld_email'], 'day' => $wish['bday']]; 
					}
					if($wish['fld_anniversary'] == $wishdate) { 
						$wish_data['anni_days'][] = ['name' => $wish['fld_name'], 'phone' => $wish['fld_phone'], 'email' => $wish['fld_email'], 'day' => $wish['aday']]; 
					}
				}
			}
			return $wish_data;
		}
	}
	if(!function_exists('EmailConfig')) { /* get today bday and anniversary detail */
		function EmailConfig() {
			$settings = getSettingData();
			$emailconfig = array(
	            'protocol'  => 'smtp', // Can also be 'mail' or 'sendmail'
	            'smtp_host' => $settings['fld_host'],
	            'smtp_port' => 465,
	            'smtp_user' => $settings['fld_fromemail'],
	            'smtp_pass' => $settings['fld_emailpass'],
	            'smtp_crypto' => 'ssl',
	            'mailtype'  => 'html',
	            'charset'   => 'utf-8',
	            'wordwrap'  => TRUE
	        );
			return $emailconfig;
		}
	}
	if(!function_exists('SendEmail')) { /* get today bday and anniversary detail */
		function SendEmail($to, $cc, $bcc, $subj, $msg) {
			getInstance()->load->library('email');
			getInstance()->email->initialize(EmailConfig());
			getInstance()->email->from('yokesh@amoriotech.com', 'Amorio');
			getInstance()->email->to($to);
	        getInstance()->email->cc($cc); // Optional, use for carbon copy
	        getInstance()->email->bcc($bcc); // Optional, use for blind carbon copy
	        getInstance()->email->subject($subj);
	        getInstance()->email->message($msg);
	        if(getInstance()->email->send()) {
	        	$result = 'Email sent successfully.';
	        } else {
	        	$result = 'Failed to send email. ' . getInstance()->email->print_debugger();
	        }
	        return $result;
		}
	}
	if(!function_exists('EmailTemplate')) { /* Email Template for wishes */
		function EmailTemplate($data) {
			$template = "";
			if(!empty($data)) {
				$template = '<!DOCTYPE html>
								<html>
								  <head>
								    <meta charset="utf-8">
								    <meta name="viewport" content="width=device-width">
								    <meta http-equiv="X-UA-Compatible" content="IE=edge">
								    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
								    <meta name="color-scheme" content="light">
								    <meta name="supported-color-schemes" content="light">
								    <title>Wishes</title>								  
								    <style>
								      @font-face {
								        font-family: Poppins;
								        font-style: normal;
								        font-weight: 400;
								        font-display: swap;
								        src: url(https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrJJfecg.woff2) format(woff2);
								        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
								      }
								      html,
								      body {
								        margin: 0 auto !important;
								        padding: 0 !important;
								        height: 100% !important;
								        width: 100% !important;
								      }
								      table {
								        border-spacing: 0 !important;
								        border-collapse: collapse !important;
								        table-layout: fixed !important;
								        margin: 0 auto !important;
								      }
								      h2, h3 {
								        padding: 0;
								        margin: 0;
								        border: 0;
								        background: none;
								      }
								      .bg-white {
								        background-color:#ffffff;
								        padding:10px 20px;
								      }
								    </style>
								  </head>
								  <body style="background-color: #F2F2F2;">
								    <center>
								      <div style="max-width: 680px; margin: 0 auto;" class="email-container">
								        <table>
								            <tr>
								              <td class="separator" aria-hidden="true" height="20" style="height:20px"> &nbsp; </td>
								            </tr>								          
								            <tr>
								              <td class="p-0-10-50 bg-white" style="border-radius:20px;">
								                <br>
								                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
								                    <tr>
								                      <td align="center" style="border:1px solid #A20C25; border-radius: 15px; padding:35px 20px">
								                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">';
									                    if(isset($data['content'])) {
									                    	$template .= '<tr>
											                            <td style="font-family:Poppins,Arial,sans-serif; font-size:14px; mso-line-height-rule: exactly;line-height: 1.5;padding-bottom:30px;color:#000000;text-align:center">
											                              <p>'.$data['content'].'</p>
											                            </td>
											                          </tr>';
									                    }
									                    if(isset($data['wish_msg'])) {
									                    	$template .= '<tr>
											                            <td style="font-family:Poppins,Arial,sans-serif; font-size:20px; text-align:center">
											                              <p style="margin:0; color:#a20c25;font-weight:600">'.$data['wish_msg'].'</p>
											                            </td>
											                          </tr>';
									                    }
								            $template .= '</table>
								                      </td>
								                    </tr>
								                  </table>
								            <!-- <tr>
								              <td class="p-15-10 bg-white">
								                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: auto;">
								                  <tr>
								                    <td style="border-radius: 30px; background: #A20C25;text-align:center">
								                      <a class="btn" href="" style="border: 1px solid #A20C25; font-family:Poppins,Arial,sans-serif; font-size:22px; mso-line-height-rule: exactly;line-height:22px; text-decoration: none; padding: 11px 25px; color: #FFFFFF; font-weight:600;display: block; border-radius: 30px;"> Booking Spa</a>
								                    </td>
								                  </tr>
								                </table>
								              </td>
								            </tr> -->
								                <table>
								                  <tr>
								                    <td align="center">
								                      <br><br>
								                      <img src="assets/images/company_imgs/amo-black.png" alt="" width="250" height="100" >
								                    </td>
								                  </tr>
								                </table>
								                <table class="">
								                  <tr>
								                    <td style="font-family:Poppins,Arial,sans-serif; font-size:10px; padding:20px;text-align:center">
								                      <p>Spa bookings are an essential part of the spa experience, allowing clients to enjoy the relaxation and rejuvenation that comes with a variety of wellness treatments. Whether you’re visiting a luxury resort spa or a local wellness center, making an appointment in advance ensures that you get the treatment you desire at a time that suits you. A well-organized spa booking process not only guarantees your spot but also sets the stage for an enjoyable experience where you can unwind, de-stress, and take care of your well-being.
								                      </p>
								                      <p style="margin:0">
								                        <a target="_blank" href="" style="color:#000000;text-decoration:underline">Teams & Conditions</a>
								                      </p>
								                    </td>
								                  </tr>
								                </table>
								              </td>
								            </tr>
								          </table>
								        </div>
								    </center>
								  </body>
								</html>';
			}
			return $template;
		}
	}
	if(!function_exists('BookingTemplate')) {
		function BookingTemplate($data) {
			$template = "";
			if(!empty($data)) {
				$timing = $data['time'];
				$template = '<!DOCTYPE html>
								<html lang="en">
								<head>
								    <meta charset="UTF-8">
								    <meta name="viewport" content="width=device-width, initial-scale=1.0">
								    <title>Table Example</title>
								    <style>
								        table {
								            width: 50%;
								            margin: 20px auto;
								            border-collapse: collapse;
								            text-align: left;
								        }
								        table, th, td {
								            border: 1px solid black;
								        }
								        th, td {
								            padding: 10px;
								        }
								        th {
								            background-color: #F2F2F2;
								        }
								    </style>
								</head>
								<body>
								    <table>
								        <tr>
								            <th>APPOINTMENT ID</th>
								            <td>#'.$data['appoint_id'].'</td>
								        </tr>
								        <tr>
								            <th>NAME</th>
								            <td>'.$data['name'].'</td>
								        </tr>				 
								        <tr>
								            <th>DATE</th>
								            <td>'.$data['date'].'</td>
								        </tr>
								        <tr>
								            <th>TIMING</th>
								            <td>'.$data['time'].'</td>
								        </tr>
								        <tr>
								            <th>SERVICES</th>
								            <td>'.str_replace(['"', '[', ']'], "", $data['service']).'</td>
								        </tr>
								        <tr>
								            <th>PAID AMOUNT</th>
								            <td>₹'.$data['amount'].'</td>
								        </tr>
								    </table>
								    <hr style="border: 1px solid #ddd; margin: 20px 0;">
									<p style="font-size: 14px; color: #333; text-align: center;">
									    Thank you for choosing us! We look forward to serving you. <br>
									    If you have any questions or need assistance, feel free to contact us anytime.
									</p>
									<p style="font-size: 12px; color: #888; text-align: center;">
									    /* <strong>Contact Us:</strong> <a href="mailto:winkin365@gmail.com">winkin365@gmail.com</a> | <a href="tel:+91 9677033077">+91 9677033077</a> */
									</p>
								</body>
								</html>';
			}
			return $template;
		}
	}	
	if(!function_exists('Bgcolors')) { /* get today bday and anniversary detail */
		function Bgcolors($type) {
			switch($type) {
				case 'Active':
					$color = 'success';
					break;
				case 'Deactive':
					$color = 'secondary';
					break;
				case 'Confirm':
					$color = 'primary';
					break;
				case 'Completed':
					$color = 'success';
					break;
				case 'Hold':
					$color = 'warning';
					break;
				case 'In-Progress':
					$color = 'info';
					break;
				case 'Cancelled':
					$color = 'danger';
					break;
				case 'Approved':
					$color = 'success';
					break;
				case 'Rejected':
					$color = 'danger';
					break;
				case 'Pending':
					$color = 'info';
					break;
				default:
					$color = 'info';
					break;
			}
	        return $color;
		}
	}
