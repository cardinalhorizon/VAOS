<?php
/**
 * Codon PHP Framework
 *	www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon
 */

class Util
{

	public static $trace;


	/**
	 * Send a file out to the browser so a user can download it
	 *
	 * @param string $contents The contents of what they downloading
	 * @param string $save_as The default filename
	 * @param string $mime_type The mime-type (default text/plain)
	 * @return none
	 *
	 */
	public static function downloadFile($contents, $save_as, $mime_type='text/plain')
	{
		# Set the headers so the browser things a file is being sent
		header('Content-Type: '.$mime_type);
		header('Content-Disposition: attachment; filename="'.$save_as.'"');
		header('Content-Length: ' . strlen($contents));

		echo $contents;
	}


	public static function get_coordinates($line)
	{
		/* Get the lat/long */
		preg_match('/^([A-Za-z])(\d*).(\d*.\d*).([A-Za-z])(\d*).(\d*.\d*)/', $line, $coords);

		$lat_dir = $coords[1];
		$lat_deg = $coords[2];
		$lat_min = $coords[3];

		$lng_dir = $coords[4];
		$lng_deg = $coords[5];
		$lng_min = $coords[6];

		$lat_deg = ($lat_deg*1.0) + ($lat_min/60.0);
		$lng_deg = ($lng_deg*1.0) + ($lng_min/60.0);

		if(strtolower($lat_dir) == 's')
			$lat_deg = '-'.$lat_deg;

		if(strtolower($lng_dir) == 'w')
			$lng_deg = $lng_deg*-1;

		/* Return container */
		$coords = array(
			'lat' => $lat_deg,
			'lng' => $lng_deg
		);

		return $coords;
	}

	/**
	 * Convert PHP 0-6 days to the Compact M T W R F S Su
	 */
	public static function GetDaysCompact($days)
	{
		#$all_days = array('Su', 'M', 'T', 'W', 'Th', 'F', 'S', 'Su');
		$all_days = Config::Get('DAYS_COMPACT');

		foreach($all_days as $index=>$day) {
			$days = str_replace($index, $day.' ', $days);
		}

		return $days;
	}

	/**
	 * Convert PHP 0-6 days to the full date string
	 */
	public static function GetDaysLong($days)
	{
		$all_days = Config::Get('DAYS_LONG');
		foreach($all_days as $index=>$day) {
			$days = str_replace($index, $day.' ', $days);
		}

		return $days;
	}
	/**
	 * Add two time's together (1:30 + 1:30 = 3 hours, not 2.6)
	 *
	 * @param mixed $time1 Time one
	 * @param mixed $time2 Time two
	 * @return mixed Total time
	 *
	 */
	public static function AddTime($time1, $time2)
	{
		//self::$trace = array();
		$time1 = str_replace(':', '.', $time1);
		$time2 = str_replace(':', '.', $time2);

		//self::$trace[] = "Inputted as: $time1 + $time2";

		$time1 = number_format((double)$time1, 2);
		$time2 = number_format((double)$time2, 2);

		$time1 = str_replace(',', '', $time1);
		$time2 = str_replace(',', '', $time2);

		//self::$trace[] = "After format: $time1 + $time2";

		$t1_ex = explode('.', $time1);
		$t2_ex = explode('.', $time2);

		# Check if the minutes are fractions
		# If they are (minutes > 60), convert to minutes
		if($t1_ex[1] > 60) {
			$t1_ex[1] = intval((intval($t1_ex[1])*60)/100);
		}

		if($t2_ex[1] > 60) {
			$t2_ex[1] = intval((intval($t2_ex[1])*60)/100);
		}

		//self::$trace[] = "After fraction check: $time1 + $time2";

		$hours = ($t1_ex[0] + $t2_ex[0]);
		$mins = ($t1_ex[1] + $t2_ex[1]);

		//self::$trace[] = "Added, before conversion: $hours:$mins";

		while($mins >= 60) {
			$hours++;
			$mins -= 60;
		}

		//self::$trace[] = "Minutes left: $mins";

		# Add the 0 padding
		if(intval($mins) < 10)
			$mins = '0'.$mins;

		$time = number_format($hours.'.'.$mins, 2);
		$time = str_replace(',', '', $time);

		/*self::$trace[] = "Translated to $hours.$mins";
		self::$trace[] = "";*/

		return $time;
		#return $hours.'.'.$mins;
	}

    /**
     * Util::secondsToTime()
     *
     * @param mixed $secs
     * @return
     */
    public static function secondsToTime($secs)
    {
       $times = array(3600, 60, 1);
       $time = '';
       $tmp = '';

        for($i = 0; $i < 3; $i++) {
            $tmp = floor($secs / $times[$i]);
            if($tmp < 1) {
                $tmp = '00';
            } elseif($tmp < 10) {
                $tmp = '0' . $tmp;
            }

            $time .= $tmp;
            if($i < 2) {
                $time .= ':';
            }

            $secs = $secs % $times[$i];
        }

        return $time;
    }


	/**
	 * Send an email
	 *
	 * @param string $email Email Address to send to
	 * @param string $subject Email Subject
	 * @param string $message Email Message
	 * @param string $fromname From name (optional, will use SITE_NAME)
	 * @param string $fromemail From email (option, will use ADMIN_EMAIL)
	 * @return mixed
	 *
	 */
	public static function SendEmail($email, $subject, $message, $fromname='', $fromemail='')
	{
		ob_start();
		# PHPMailer
		include_once(SITE_ROOT.'/core/lib/phpmailer/class.phpmailer.php');
		$mail = new PHPMailer();

		if($fromemail == '') {
			$fromemail = Config::Get('EMAIL_FROM_ADDRESS');

			if($fromemail == '') {
				$fromemail = ADMIN_EMAIL;
			}
		}

		if($fromname == '') {

			$fromname = Config::Get('EMAIL_FROM_NAME');

			if($fromname == '') {
				$fromname = SITE_NAME;
			}
		}

		$return_path_email = Config::Get('EMAIL_RETURN_PATH');
		if($return_path_email == '') {
			$return_path_email = $fromemail;
		}

		$mail->From     = $fromemail;
		$mail->FromName = $fromname;

		// Fix thanks to jm (Jean-Michel)
		$mail->Sender = $return_path_email;

		$mail->Mailer = 'mail';
		$mail->CharSet = 'UTF-8'; #always use UTF-8
		$mail->IsHTML(true);

		if(Config::Get('EMAIL_USE_SMTP') == true) {

			$mail->IsSMTP();

			$mail->Host = Config::Get('EMAIL_SMTP_SERVERS');
			$mail->Port = Config::Get('EMAIL_SMTP_PORT');

			if(Config::Get('EMAIL_SMTP_USE_AUTH') == true) {
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = Config::get('EMAIL_SMTP_SECURE');
				$mail->Username = Config::Get('EMAIL_SMTP_USER');
				$mail->Password = Config::Get('EMAIL_SMTP_PASS');
			}
		}

		$mail->SetFrom($fromemail, $fromname);
		$mail->AddReplyTo($fromemail, $fromname);

		$message = "<html><head></head><body>{$message}</body></html>";
		//$message = nl2br($message);
		$alt = strip_tags($message);

                //allowing function to send to an array of email addresses, not just one
		if(is_array($email))    {
                    foreach($email as $emailrec)    {
                        $mail->AddAddress($emailrec);
                    }
                }
                else    {
                    $mail->AddAddress($email);
                }
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->AltBody = $alt;

		$mail->Send();
		ob_end_clean();
	}
}