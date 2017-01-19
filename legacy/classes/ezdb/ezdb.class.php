<?php
/**
 * Copyright (c) 2010 Nabeel Shahzad
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008-2010, Nabeel Shahzad
 * @link http://github.com/nshahzad/ezdb
 * @license MIT License
 * 
 * Based on ezSQL by Justin Vincent: http://justinvincent.com/docs/ezsql/ez_sql_help.htm
 */

/**
 * This is the < PHP 5.3 version
 *
 */
class DB
{
	public static $DB;
	public static $insert_id;
	public static $errno;
	public static $error;
	public static $num_rows;
	public static $rows_affected;
	public static $connected = false;
	public static $last_query;
	
	public static $throw_exceptions = true;
	public static $default_type = OBJECT;
	public static $show_errors = false;
	public static $log_errors = false;
	public static $error_handler = null;
	
	public static $table_prefix = '';

	protected static $dbuser;
	protected static $dbpass;
	protected static $dbname;
	protected static $dbserver;
	
	/**
	 * Private contructor, don't allow for
	 * initialization of this class
	 */
	private function __contruct()
	{
		return;
	}
	
	public function __destruct()
	{
		@self::$DB->close();
	}
	
	/**
	 * Return the singleton instance of the DB class
	 *
	 * @return object
	 */
	public static function get_instance()
	{
		return self::$DB;
	}
		
	/**
	 * Initialize the database connection
	 *
	 * @param string $type Either mysql, mysqli, oracle. Default is mysql
	 * @return boolean
	 */
	public static function init($type='mysql')
	{
		$class_name = strtolower('ezdb_'.$type);
		include dirname(__FILE__).DIRECTORY_SEPARATOR.$class_name.'.class.php';
		
		if(!self::$DB = new $class_name())
		{
			self::$error = self::$DB->error;
			self::$errno = self::$DB->errno;
			
			return false;
		}
		
		return true;
	}
	
	public static function set_log_errors($bool)
	{
		self::$log_errors = $bool;
	}
	
	public static function set_throw_exceptions($bool)
	{
		self::$throw_exceptions = $bool;
	}
		
	/**
	 * Set a function/class as an error handler. Function is the 
	 * same parameter sent to 
	 * 
	 * http://us.php.net/manual/en/function.call-user-func-array.php
	 * 
	 * @param $handler method name or class to call on an error
	 * 
	 */
	public static function set_error_handler($function)
	{
		self::$error_handler = $function;		
	}
		
	/**
	 * Enable or disable caching, can be set per-query
	 * 
	 * @param bool $bool True/False
	 * @return none
	 */
	public static function set_caching($bool)
	{
		self::$DB->set_caching($bool);
	}
	
	/**
	 * Set the cache type (file, memcache)
	 *
	 * @param string $type Caching type
	 * @return none 
	 *
	 */
	public static function cache_type($type)
	{
		self::$DB->cache_type($type);
	}
	
	/**
	 * Set the path of the cache
	 *
	 * @param mixed $path This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	public static function set_cache_dir($path)
	{
		self::$DB->set_cache_dir($path);
	}
	
		
	/* Aliases for above, backwards compat */
	
	public static function setCacheDir($path)
	{
		self::set_cache_dir($path);
	}
	
	public static function enableCache()
	{
		self::$DB->set_caching(true);
	}
	
	public static function disableCache()
	{
		self::$DB->set_caching(false);
	}
	
	/* End aliases */
	
	/**
	 * Connect to database
	 *
	 * @param string $user
	 * @param string $pass
	 * @param string $name
	 * @param string $server
	 * @return boolean
	 */
	public static function connect($user='', $pass='', $name='', $server='')
	{
		if(!self::$DB->connect($user, $pass, $server))
		{
			self::$error = self::$DB->error;
			self::$errno = self::$DB->errno;
			
			return false;
		}
		
		if(!self::$DB->select($name))
		{
			self::$error = self::$DB->error;
			self::$errno = self::$DB->errno;
			
			return false;
		}
		
		self::$dbuser = $user;
		self::$dbpass = $pass;
		self::$dbname = $name;
		self::$dbserver = $server;
		
		self::$DB->dbuser = $user;
		self::$DB->dbpassword = $pass;
		self::$DB->dbname = $name;
		self::$DB->dbhost = $server;
		
		self::$DB->throw_exceptions = self::$throw_exceptions;
		self::$connected = true;
		return true;
	}

	public static function num_queries()
	{
		
		return self::$DB->num_queries();

	}

	
	/**
	 * Select/Change the active database. It's called from
	 * connect(), but can also be changed
	 *
	 * @param string $dbname
	 * @return boolean
	 */
	public static function select($dbname)
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->select($dbname);
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		
		if(self::$errno == 0)
			return true;
		
		return false;
	}
	
	/**
	 * Close the database connector
	 *
	 * @return unknown
	 */
	public static function close()
	{
		return @self::$DB->close();
	}
	
	/**
	 * Set a table prefix for the quick_ functions
	 * If it's set to blank (default), then no prefix will be used
	 *
	 * @param unknown_type $prefix
	 */
	public static function set_table_prefix($prefix)
	{
		self::$table_prefix = '';
	}
	
	/**
	 * Do a "quick select".
	 * @see http://www.nsslive.net/codon/docs/database#quick_functions
	 *
	 * @param string $table Table name
	 * @param array $fields Fields to select (array)
	 * @param string $cond Conditions to select for
	 * @param constant $type
	 * @return resultset
	 */
	public static function quick_select($table, $fields='', $cond='')
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		return self::$DB->quick_select($table, $fields, $cond);
	}
	
	/**
	 * Do a quick insert into a table
	 * @see http://www.nsslive.net/codon/docs/database#quick_functions
	 *
	 * @param string $table Table Name
	 * @param array $fields Associatve arrays of keys to isnert
	 * @param string $flags INSERT flags (DELAYED, etc)
	 * @return result
	 */
	public static function quick_insert($table, $fields, $flags= '', $allowed_cols='')
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		return self::$DB->quick_insert($table, $fields, $flags, $allowed_cols);
	}
	
	/**
	 * Do a "quick update"
	 * @see http://www.nsslive.net/codon/docs/database#quick_functions
	 *
	 * @param string $table Table name
	 * @param array $fields Associative array (column=>value) to update
	 * @param unknown_type $cond Conditions to update on
	 * @return result
	 */
	public static function quick_update($table, $fields, $cond='', $allowed_cols='')
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		return self::$DB->quick_update($table, $fields, $cond, $allowed_cols);
	}
	
	/**
	 * Build a SELECT statement
	 *
	 */
	public static function build_select($params)
	{
		return self::$DB->build_select($params);
	}
	
	/**
	 * Build a WHERE clause for an SQL statement with supplied parameters
	 *
	 * @param array $fields associative array with column=>value
	 * @return string string where
	 *
	 */
	public static function build_where($fields)
	{
		return self::$DB->build_where($fields);
	}
	
	
	/**
	 * Build the update clause (after the SET and before WHERE)
	 *
	 * @param array $fields associative array (col_name=>value)
	 * @return string the SQL string
	 *
	 */
	public static function build_update($fields)
	{
		return self::$DB->build_update($fields);
	}
	
	/**
	 * Write out the last query to a debug log, or error
	 *
	 * @return mixed This is the return value description
	 *
	 */
	public static function write_debug()
	{
		if(self::$error_handler === null || self::$log_errors == false)
		{
			return;
		}
		
		$backtrace = debug_backtrace();
			
		$debug_info = array(
			'backtrace' => $backtrace,
			'sql' => self::$last_query,
			'error' => self::$error,
			'errno' => self::$errno,
			'dbuser' => self::$dbuser,
			'dbname' => self::$dbname,
			'dbpass' => self::$dbpass,
			'dbserver' => self::$dbserver,
		);
			
		call_user_func_array(self::$error_handler, array($debug_info));
	}
	
	/**
	 * Return array of results. Default returns array of
	 * objects. Can be ARRAY_A, ARRAY_N or OBJECT, for
	 * array associative, numeric array, or an object.
	 *
	 * @see http://www.nsslive.net/codon/docs/database
	 * @param string $query
	 * @param constant $type Return type
	 * @return array/object
	 */
	public static function get_results($query, $type='')
	{
		if($type == '') $type = self::$default_type;
		
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->get_results($query, $type);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$num_rows = self::$DB->num_rows;
		self::$last_query = $query;
		
		// Log any erronious queries
		if(self::$DB->errno != 0)
		{
			self::write_debug();
		}
		
		return $ret;
	}
	
	/**
	 * Return a single row
	 *
	 * @param string $query
	 * @param constant $type
	 * @param offset $y
	 * @return unknown
	 */
	public static function get_row($query, $type='', $y=0)
	{
		if($type == '') $type = self::$default_type;
		
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->get_row($query, $type, $y);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$last_query = $query;
		
		// Log any erronious queries
		if(self::$DB->errno != 0)
		{
			self::write_debug();
		}
		
		return $ret;
	}
	
	/**
	 * Perform a query
	 *
	 * @param unknown_type $query
	 * @return boolean/int Returns true/false, or rows affected
	 */
	public static function query($query)
	{
		self::$DB->throw_exceptions = self::$throw_exceptions;
		
		$ret = self::$DB->query($query);
		
		self::$error = self::$DB->error;
		self::$errno = self::$DB->errno;
		self::$rows_affected = self::$num_rows = self::$DB->num_rows;
		self::$insert_id = self::$DB->insert_id;
		self::$last_query = $query;
		
		// Log any erronious queries
		if(self::$DB->errno != 0)
		{
			self::write_debug();
		}
		
		return $ret; //self::$insert_id;
	}

	/** 
	 * Return all of the columns
	 */
	public static function get_cols()
	{
		return self::$DB->get_cols();
	}
	
	/**
	 * Get information about a column
	 *
	 * @param string $info_type
	 * @param int $col_offset
	 * @return unknown
	 */
	public static function get_col_info($info_type="name",$col_offset=-1)
	{
		return self::$DB->get_col_info($info_type, $col_offset);
	}
	
	/**
	 * Return a single value from a query
	 *
	 * @param query $query
	 * @param int $offset
	 * @return unknown
	 */
	public static function get_col($query=null,$offset=0)
	{
		return self::$DB->get_col($query, $offset);
	}
		
	public static function get_var($query=null, $x=0, $y=0)
	{
		return self::$DB->get_var($query, $x, $y);
	}
	
	public static function num_rows()
	{
		return self::$num_rows;
	}
	
	public static function vardump($mixed='')
	{
		return self::$DB->vardump($mixed);
	}
	
	public static function dumpvar($mixed='')
	{
		return self::$DB->vardump($mixed);
	}
	
	public static function get_cache($query)
	{
		return self::$DB->get_cache($query);
	}
	
	public static function store_cache($query, $is_insert)
	{
		return self::$DB->store_cache($query, $is_insert);
	}
	
	/**
	 * Get the error string from the last query
	 *
	 * @return string
	 */
	public static function error()
	{
		return self::$DB->error();
	}

	/**
	 * Return the last query error number
	 *
	 * @return int
	 */
	public static function errno()
	{
		return self::$DB->errno();
	}
	
	/**
	 * Return array of all the errors
	 *
	 * @return array
	 */
	public static function get_all_errors()
	{
		return self::$DB->get_all_errors();
	}
	
	public static function flush()
	{
		return self::$DB->flush();
	}
	public static function show_errors()
	{
		return self::$DB->show_errors();
	}
	
	public static function hide_errors()
	{
		return self::$DB->hide_errors();
	}
	
	public static function escape($val)
	{
		return self::$DB->escape($val);
	}
	
	public static function debug($return = false)
	{
		if(self::$show_errors === true)
			return self::$DB->debug($return);
	}
}