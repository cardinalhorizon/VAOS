<?php
/**
 * Codon PHP Framework
 *  www.nsslive.net
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
 * This class condenses many files into one, to reduce HTTP requests
 * Stores a cached version of the file, and uses that cache
 *	for upto a certain amount of time
 */
class CodonCondenser
{	
	public $path;
	public $url;
	public $timeout = 24;
	public $file_ext = '';
	public $filename;
	
	public function SetOptions($path, $url, $file_ext, $timeout=24)
	{
		$this->path = $path;
		$this->url = $url;
		$this->timeout = $timeout;
		$this->file_ext = $file_ext;
	}
	
	public function GetCondensedFile($files, $filename='', $usecache=true)
	{
		
		if(!is_array($files))
			return false;
			
		if($filename != '')
		{
			$this->filename = $filename;
		}
		else
		{
			$this->filename = md5(implode('', $files));
			$this->filename .= '.'.$this->file_ext;	
		}
		
		# Check if we've already made this condensed cache file
		#	If we have, then just give the URL of that file
		$ret = false;
		if($usecache == true)
		{
			$ret = $this->getCachedFile();
		}
		
		if($ret == true && $usecache == true)
		{
			return $this->url.'/'.$this->filename;
		}
		
		# The file is old, or we don't have it, so
		#	build it
		if(!is_writable($this->path.'/'.$this->filename))
			return $this->url.'/'.$this->filename;
			
		$fp = @fopen($this->path.'/'.$this->filename, 'w');
		
		if(!$fp || $fp === false)
			return $this->url.'/'.$this->filename;
			
		foreach($files as $file)
		{
			if($file == '' || !file_exists($this->path.'/'.$file))
			{
				continue;
			}
			
			fwrite($fp, file_get_contents($this->path.'/'.$file).PHP_EOL);
		}
		
		fclose($fp);
		
		return $this->url.'/'.$this->filename;		
	}	
	
	protected function getCachedFile()
	{
		if(!file_exists($this->path.'/'.$this->filename))
		{
			return false;
		}
		
		# Check if the version that exists
		#	is older than the timeout we have alloted
		# Value of "" skips the time check
		if($this->timeout == '')
		{
			if ((time() - @filemtime($file)) > ($this->timeout*3600))
			{
				# It is older, so delete it
				@unlink($this->path.'/'.$file);
				return false;			
			}
		}
		
		# The cache file is ok
		return true;
	}
}