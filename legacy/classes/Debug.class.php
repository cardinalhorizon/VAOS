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
 * @package codon_core
 */
 
/**
 * Catch errors thrown by trigger_error() or other
 *  "soft" errors which are thrown
 */
function CatchPHPError($errno, $errstr, $errfile='', $errline='', $errcontext = '')
{
	// form string:
	$errlevel = error_reporting();
	if($errlevel == 0 || !Debug::$debug_enabled)
		return;
		
	$errcodes = array('1'=>'E_ERROR', '2'=>'E_WARNING', '4'=>'E_PARSE', '8'=>'E_NOTICE',
					  '16'=>'E_CORE_ERROR', '32'=>'E_CORE_WARNING', '64'=>'E_COMPILE_ERROR',
					  '128'=>'E_COMPILE_WARNING', '256'=>'E_USER_WARNING', '512'=>'E_USER_WARNING',
					  '1024'=>'E_USER_NOTICE', '2047'=>'E_ALL');
	
	if($errlevel & $errno)
	{
		$errfile = str_replace(SITE_ROOT,'',$errfile);
		// Show error on two lines, so 2 arguments
		$line1 = '<strong><span style="color:red">Error! </span>'.$errcodes[$errno] . '</strong> - "'.$errstr.'"';
		$line2 = 'Line <strong>'.$errline.':</strong>, in <i>'.$errfile.'</i>';

		Debug::Show($line1, $line2, array('Variables in scope'=>$errcontext));
	}
}

class Debug
{
	public static $heading_shown = false;
	public static $debug_enabled = false;
	public static $callinfo; // last call info
	public static $bt; // backtrace
	public static $count = 1;
	
	protected static $fp;
	
	/**
	 * Write out any DB errors to a log
	 * 
	 * @param mixed $debug_info
	 * @return void
	 */
	public static function db_error($debug_info) {
	   
		$call_list = array();
		foreach($debug_info['backtrace'] as $caller) {
			$call_list[] = $caller['class'].$caller['type'].$caller['function'];
		}
        
		$callers = implode(' > ', $call_list);
		unset($call_list);
		
		$debug_info['sql'] = preg_replace('/[\r\n\s]+/xms', ' ', trim($debug_info['sql']));
		$debug_info['error'] = preg_replace('/[\r\n\s]+/xms', ' ', trim($debug_info['error']));
				
		$time = date('m.d.y H:i:s');
		$log_text="=====\n"
				 ."Time: {$time}\n"
				 ."Backtrace: {$callers}\n"
				 ."Query: {$debug_info['sql']}\n"
				 ."Error: ({$debug_info['errno']}) - {$debug_info['error']}\n=====\n\n";
				 
		self::log($log_text);
	}
	
	public static function log($string, $filename = 'log') {
	   
		if(Config::Get('DEBUG_MODE') === false){
			return;
		}
			
		if($filename == '')
			$filename = 'log';
			
		$time = date('m.d.y H:i:s');
		$string = "=====\n"
				 ."Time: {$time}\n"
				 ."{$string}\n=====\n\n";
					
		self::$fp = fopen(SITE_ROOT.'/core/logs/'.$filename.'.txt', 'a');
		fwrite(self::$fp, $string);
	}
	
	public static function firebug() {
		include_once CORE_PATH.DS.'lib'.DS.'firebug'.DS.'FirePHP.class.php';
		
		$instance = FirePHP::getInstance(true);
		$args = func_get_args();
		return call_user_func_array(array($instance,'fb'),$args);
	}
	
	public static function showCritical($message, $title='') {
		if($title == '')
			$title = 'An Error Was Encountered';
			$site = SITE_URL;
		echo <<<MESSAGE
		<div id="codon_crit_error" 
			style="font-family: 'Lucida Sans',Verdana;border:#999 1px solid;background-color:#fff;padding:20px 20px 12px 20px;">
			<h1 style="font-family: verdana; font-weight:16px;font-size:18px;color:#6B001B;margin:0 0 4px 0;">$title</h1>
			<p style="font-size: 16px; color: #001B6B">$message</p>
            <p style="font-size: 10px;"><center><a href="$site">Return to Homepage</a></p>
		</div>
MESSAGE;
	}
	
	/**
	 * Show the CSS and JS code for the debug box
	 * Is set to only show once on the page
	 */
	public static function showHeader() {
		if(self::$heading_shown == true) return;
		
		self::$heading_shown = true;
		
		?>
		<style>
		.codon_debug
		{border: 1px solid #000000; background: #FFF;font-family: Arial, Verdana;
			font-size: 12px;margin: 5px;padding: 5px;}
		.codon_debug_header
		{background: #B8C8FF;padding: 3px;margin-bottom: 5px;cursor: pointer;margin-top: 5px;}
		.codon_debug_text
		{padding-left: 7px;padding-bottom: 7px;}
		.codon_debug_subheader
		{background: #FFE9A8;padding: 3px;margin: 4px 0 4px 20px;cursor: pointer;}
		.codon_debug_innerarray
		{margin-left: 25px;background: #FFF;}
		</style>
		
		<script type="text/javascript">
		var elements = Array();
		function codon_debug_toggle(id)
		{	if (elements[id] == "hide")
			{	document.getElementById(id).style.display = "none";
				elements[id] = "show";} else
			{	document.getElementById(id).style.display = "block";
				elements[id] = "hide";}
			return false;
		}
		</script>
		<?php
	}
	
	public static function Show()
	{
		if(!self::$debug_enabled) return;
		if (func_num_args() == 0) return;
		
		if(!self::$heading_shown) self::showHeader();
		
		// generate our backtrace
		self::$bt = debug_backtrace();
		self::$callinfo = self::$bt[0];
		unset(self::$bt[0]);
				
		//self::$bt = array_reverse(self::$bt, true);
		
		echo '<div class="codon_debug">';
		
		$args = func_get_args();
		
		foreach($args as $value) {
		  
			$id = mt_rand();
			
			if(is_array($value) || is_object($value)) { 
				
				if(count($value)> 1)
					self::printArrayObj($value, 'More than 1 value in '.gettype($value).' - ');
				else {	
				    
                    /*this will run once usually, unless its multiple arrays
						but thats taken care of in the loop */
					foreach($value as $key=>$val) {
						
                        if(is_array($val)) {
							self::printArrayObj($val, $key);
						} else {
							if(is_int($key)) $key = '';
							else $key .= ' - ';
								
							echo $key.$val.'<br />';
						}
					}
				}
			} else {
				echo $value.'<br />';
			}
		}
					
		echo'<div style="border-top: solid 1px #000; margin-top: 6px;"></div>
				<div class="codon_debug_header"
					onClick="codon_debug_toggle(\'showbacktrace'.$id.'\');">Show Backtrace:  (click to expand)</div>
				<div class="codon_debug_text" id="showbacktrace'.$id.'" style="display: none;">';
				
				self::showBacktrace();
		
		echo '</div>';
		self::showOrigin();
		echo '</div>';
	}
	
	public static function printArrayObj($array, $title='') {
	   
		if(count($array) == 0) return;
		
		//if($title!='') $title_header = 'Variable name: '.$title;
		
		$id = mt_rand();
		/*echo '<div class="codon_debug_subheader"
				onClick="codon_debug_toggle(\''.$id.'\');"><strong>'.$title.'</strong> ('
						. gettype($array) .') (click to expand)</div>
				<div id="'.$id.'" class="codon_debug_innerarray" style="display: none;">';*/
		
		echo '<div class="codon_debug_header"
				onClick="codon_debug_toggle(\'showdebug'.$id.'\');">'.$title.' (click to expand)</div>
				<div class="codon_debug_text" id="showdebug'.$id.'" style="display: none;">';
						
		foreach($array as $key => $value) {
			if(is_array($value) || is_object($value)) {
				self::printArrayObj($value, '$'.$key);
			} else {
				echo '[<strong>'.$key.'</strong>] = "'.$value.'" <br />';
			}
		}
		
		echo '</div>';
	}
	
	public static function showBacktrace() {
		$i = 1;
		
		foreach(self::$bt as $section) {
		  
			echo '<strong>'. $i++ . '. '
					.$section['class'].$section['type'].$section['function']
					.'</strong>'
					.'    ('.str_replace(SITE_ROOT,'',$section['file']) . ')<br />';
			
			$args = count($section['args']);
			
			if($args > 0) {
				self::printArrayObj($section['args'], $args.' arguments were passed to this function:');
				echo '<br />';
			}
		}
	}
	
	public static function showOrigin() {
	   
		self::$bt = array_reverse(self::$bt, true);
		$called = array_pop(self::$bt);
			
		echo '<div style="padding: 3px;">Called by: '
					.$called['class'].$called['type'].$called['function']
					.'() from line '.self::$callinfo['line']
					.' in '.str_replace(SITE_ROOT, '', self::$callinfo['file'])
					.'</div>';
	}	
}