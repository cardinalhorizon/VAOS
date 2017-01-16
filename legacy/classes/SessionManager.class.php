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

class SessionManager
{
	public static $error_message;
	
	/**
	 * Add session data en-masse
	 *
	 * @param array $info Associative array to add into the session
	 */
	public static function RegisterData(&$info)
	{
		foreach($info as $key=>$val)
		{
			$_SESSION[$key] = serialize($val);
		}
	}
	
	public static function Set($key, $value)
	{
		$_SESSION[$key] = serialize($value);
	}
	
	public static function AddData($key, $value)
	{
		$_SESSION[$key] = serialize($value);
	}
	
	
	public static function Get($key)
	{
		return self::GetData($key);
	}
	
	public static function GetData($key)
	{
		if(isset($_SESSION[$key]))
			return unserialize($_SESSION[$key]);
			
		return '';
	}
	
	public static function GetValue($key, $index)
	{
		$upack = unserialize($_SESSION[$key]);
		
		if(is_object($upack))
			return $upack->$index;
		else
			return $upack[$index];
	}
	
	public static function Logout()
	{
		session_destroy();
	}
}
