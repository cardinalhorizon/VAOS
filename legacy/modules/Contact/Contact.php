<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Contact extends CodonModule
{
	public function index()
	{
                //Google reCaptcha
                //updated to Google noCaptcha 1/15
		require_once CORE_LIB_PATH.'/recaptcha/recaptchalib.php';

                $this->set('sitekey', RECAPTCHA_PUBLIC_KEY);
                $this->set('lang', 'en');


		if($this->post->submit)	{
			if(Auth::LoggedIn() == false) {
				# Make sure they entered an email address
				if(trim($this->post->name) == '' || trim($this->post->email) == '') {
					$this->set('message', 'You must enter a name and email!');
					$this->render('core_error.tpl');
					return;
				}
			}

                        //Google reCaptcha
                        //updated to Google noCaptcha 1/15
                        $resp = null;
                        $reCaptcha = new ReCaptcha(RECAPTCHA_PRIVATE_KEY);
                        // Was there a reCAPTCHA response?
                        if ($_POST["g-recaptcha-response"]) {
                                $resp = $reCaptcha->verifyResponse(
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["g-recaptcha-response"]
                            );
                        }

                        //check if reCaptcha response was valid
                        if ($resp == null) {
                            $this->set('captcha_error', 'reCaptcha Validation Error');
                            $this->render('contact_form.tpl');
                            return;
                        }
                        //end Google reCaptcha

			if($this->post->subject == '' || trim($this->post->message) == '') {
				$this->set('message', 'You must enter a subject and message!');
				$this->render('core_error.tpl');
				return;
			}

			$subject = 'New message from '.$this->post->name.' - "'.$this->post->subject.'"';
			$message = DB::escape($this->post->message) . PHP_EOL . PHP_EOL;

			foreach($_POST as $field=>$value) {
				$message.="-$field = $value".PHP_EOL;
			}

			$message = nl2br($message);
			$message = utf8_encode($message);
			Util::SendEmail(ADMIN_EMAIL, $subject, $message);

			$this->render('contact_sent.tpl');
			return;
		}

		$this->render('contact_form.tpl');
	}

}
