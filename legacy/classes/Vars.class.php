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
 
class Vars
{
	public static $getcount=null;
	public static $postcount=null;
	
	public static $post;
	public static $get;
	public static $request;
	
	public static $rewrite_rules;
	public static $matches;
	
	/**
	 * Set the $post and $get variables, since they will
	 * be set to the same properties in the CodonModule class
	 *
	 */
	public static function setParameters()
	{
		self::$post = new stdClass();
		self::$get =  new stdClass();
		self::$request =  new stdClass();
		
		foreach($_POST as $key=>$value)
		{
			self::$post->$key = self::cleaned($value);
		}
		
		foreach($_GET as $key=>$value)
		{
			self::$get->$key = self::cleaned($value);
		}
		
		foreach($_REQUEST as $key=>$value)
		{
			self::$request->$key = self::cleaned($value);
		}
		
	}
	
	public static function cleaned(&$V)
	{
		return $V;
	}
	
	/**
	 * Sanitize $var for XSS, JS, and HTML.
	 * 
	 * @param Mixed $var - variable to sanitize  
	 * @return a sanitized variable filtered for XSS and any blacklisted javascript/html tags
	 */
	public static function Filter($var) {
		$filter = new InputFilter;
		return $filter->process(self::cleaned($var));
	}
	
	public static function Request($name)
	{
		return self::cleaned($_REQUEST[$name]);
	}
	
	public static function POST($name)
	{
		if(is_array($_POST[$name]))
		{
			return $_POST[$name];
		}
		
		return self::cleaned($_POST[$name]);
	}
	
	public static function iPOST($name)
	{
		return intval($_POST[$name]);
	}
	
	public static function GET($name)
	{
		if(isset($_GET[$name]) && is_array($_GET[$name]))
		{
			return $_GET[$name];
		}
		
		return self::cleaned($_GET[$name]);
	}
	
	public static function iGET($name)
	{
		return intval($_GET[$name]);
	}
	
	public static function POST_COUNT()
	{
		if(self::$postcount == null)
			self::$postcount = count($_POST);
		
		return self::$postcount;
	}
	
	public static function GET_COUNT()
	{
		if(self::$getcount == null)
			self::$getcount = count($_GET);
		
		return self::$getcount;
	}
}