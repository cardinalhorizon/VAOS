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

class CodonEvent
{
	public static $listeners;
	public static $lastevent;
	public static $stopList = array();
	
	public static function addListener($module_name, $event_list='')
	{
		self::$listeners[$module_name] = $event_list;
	}
	
	/**
	 * Dispatch an event to the modules, will call the
	 *  modules with the EventListener() function.
	 *
	 * @see http://www.nsslive.net/codon/docs/events
	 * @param string $eventname
	 * @param string $origin
	 * @param list $params list additional parameters after $origin
	 * @return boolean true by default
	 */
	public static function Dispatch($eventname, $origin)
	{
		// if there are parameters added, then call the function
		//	using those additional params
		
		$params=array();
		$params[0] = $eventname;
		$params[1] = $origin;
		
		$args = func_num_args();
		if($args>2)
		{
			for($i=2;$i<$args;$i++)
			{
				$tmp = func_get_arg($i);
				array_push($params, $tmp);
			}
		}
		
		# Load each module and call the EventListen function
		if(!self::$listeners) self::$listeners = array();
		foreach(self::$listeners as $ModuleName => $Events)
		{
			$ModuleName = strtoupper($ModuleName);
			global $$ModuleName;
			
			# Run if no specific events specified, or if the eventname is there
			if(!$Events || in_array($eventname, $Events))
			{
				self::$lastevent = $eventname;
				MainController::Run($ModuleName, 'EventListener', $params);
				
				if(isset(self::$stopList[$eventname]) && self::$stopList[$eventname] == true)
				{
					unset(self::$stopList[$eventname]);
					return false;
				}
			}
		}
		
		return true;
	}
	
	public function hasStop($eventname)
	{
		if(isset(self::$stopList[$eventname]) && self::$stopList[$eventname] == true)
		{
			return true;
		}
		
		return false;
	}
	
	public function CheckStop($eventname)
	{
		if(isset(self::$stopList[$eventname]) && self::$stopList[$eventname] == true)
		{
			return false;
		}
		
		return true;
	}
	
	public function Stop($eventname='')
	{
		if($eventname != '')
			self::$stopList[$eventname] = true;
		else
			self::$stopList[self::$lastevent] = true;
	}
}