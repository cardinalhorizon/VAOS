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

class CodonCache 
{
	public static $settings;
	
	public static function init($settings)
	{
		/*	These are the default settings, which will get merged with
			the incoming settings passed to this function */
		self::$settings = array(
			'active' => false,					/* enabled by default, self::setStatus() */
			'engine' => 'file',					/* "file" or "apc" */
			'location' => dirname(__FILE__),	/* For the "file" engine type */
			'prefix' => __CLASS__,				/* Specify a prefix for any entries */
			'suffix' => '.cache',
			
			/*	Setup different profiles. There must be a "default" one.
				You can pass a specific "profile" to use in the write() function
				If you don't, it just uses 'default' */
				
			'profiles' => array(
				
				'default' => array(
					'duration' => '+10 minutes',
				),
				
				'short' => array(
					'duration' => '+5 minutes',
				),
				
				'medium' => array(
					'duration' => '+1 hour',
				),
				
				'long' => array(
					'duration' => '+6 hours'
				),
			),
		);
		
		if(is_array($settings)) {
			self::$settings = array_merge(self::$settings, $settings);
		}
		
		if(self::$settings['engine'] == 'file') {
			# Add a trailing slash
			if(substr(self::$settings['location'], -1, 1) != '/') {
				self::$settings['location'] .= '/';
			}
		}
	}
	
	public static function setEnabled($bool)
	{
		self::setStatus($bool);
	}
	
	/**
	 * Enable or disable the caching engine
	 *
	 * @param bool $bool true/false of status
	 *
	 */
	public static function setStatus($bool)
	{
		if(!is_bool($bool))
		{
			$bool = intval($bool);
			if($bool === 1)
				$bool = true;
			else
				$bool = false;
		}
		
		self::$settings['active'] = $bool;
	}
	
	/**
	 * Return the current status of the cache engine
	 * 
	 * @param bool true/false
	 * 
	 */
	public static function getStatus()
	{
		return self::$settings['active'];
	}
	
	/**
	 * Read a key'd value from the cache store
	 *
	 * @param string $key Key name to read
	 * @return mixed Returns the value, or false if it's old/doesn't exist
	 *
	 */
	public static function read($key)
	{
		if(self::$settings['active'] === false || $key == '') {
			return false;
		}
		
		$key = self::$settings['prefix'].$key.self::$settings['suffix'];
		if(self::$settings['engine'] == 'file') {
			
			if(!file_exists(self::$settings['location'].$key)) {
				return false;
			}
			
			$contents = file(self::$settings['location'].$key);
			
			# See if the current time is greater than that cutoff
			if(time() > $contents[0]) {
				return false;
			}
			
			# Then return the unserialized version of the store
			return unserialize($contents[1]);

		} elseif(self::$settings['engine'] == 'apc') {
			
			/*$expire = apc_fetch($key.'_expire');
			if(time() > $expire) {
				return false;
			}*/
			
			$value = apc_fetch($key, $success);
			if($success == false) {
				return false;
			}
					
			return unserialize($value);
		}
		
		return false;
	}
	
	
	/**
	 * Write a key/value to the cache store
	 *
	 * @param string $key Key to write to the cache
	 * @param mixed $value Value to write to the store
	 * @param string $profile Profile to use to write to the store
	 * @return none 
	 *
	 */
	public static function write($key, $value, $profile = 'default')
	{
		if(self::$settings['active'] === false || $key == '') {
			return false;
		}
		
		$key = self::$settings['prefix'].$key.self::$settings['suffix'];
		$ttl = strtotime(self::$settings['profiles'][$profile]['duration']);
		$value = serialize($value);
		
		// Now actually save it
		if(self::$settings['engine'] == 'file') {
			
			/*	Save the expire time on one line, and then the serialized
				value on the second line. For the check in read() */
			$value = $ttl.PHP_EOL.$value;
			file_put_contents(self::$settings['location'].$key, $value);
		
		} elseif(self::$settings['engine'] == 'apc') {
			$seconds = $ttl - time();
			/*apc_store($key.'_expire', $ttl, 0);
			apc_store($key, $ttl, $seconds);*/
			apc_store($key, $value, $seconds);
		}
		
		return true;
	}
	
	
	/**
	 * Delete a certain key'd value from the cache store
	 *
	 * @param string $key The key to delete from the cache
	 * @return bool 
	 *
	 */
	public static function delete($key)
	{
		if(self::$settings['active'] === false || $key == '') {
			return false;
		}
		
		$key = self::$settings['prefix'].$key.self::$settings['suffix'];
		if(self::$settings['engine'] == 'file') {
			
			if(file_exists(self::$settings['location'].$key)) {
				unlink(self::$settings['location'].$key);
			}

		} elseif(self::$settings['engine'] == 'apc') {
			// Simple :)
			apc_delete($key);
		}
		
		return true;
	}
}