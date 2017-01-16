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
 * 
 * Based on ezSQL by Justin Vincent: http://justinvincent.com/docs/ezsql/ez_sql_help.htm
 */

/**********************************************************************
* 
*  Name..: ezDB
*  Desc..: ezDB Core module - database abstraction library to make
*          it very easy to deal with databases.
*
*/

/**********************************************************************
*  ezDB Constants
*/

define('ezDB_VERSION','3.00');
define('OBJECT','OBJECT', true);
define('ARRAY_A','ARRAY_A', true);
define('ARRAY_N','ARRAY_N', true);

/**
 *  Simple exception container class
 *
 */
class ezDB_Error extends Exception 
{
	/* Use these to keep it consistant with ezDB_Base */
	public $error;
	public $errno;
	public $last_query = '';
	
	public function __construct($message, $code, $query='')
	{
		parent::__construct($message, $code);
		$this->last_query = $query;
		$this->error = $message;
		$this->errno = $code;
	}
	
	public function __toString()
	{
		$html .= '<blockquote>';
		$html .= '<font face=arial size=2 color=000099><b>Last Error --</b> [<font color=000000><b>'.
						$this->message . ' (' . $this->code . ')</b></font>]<br />';		
		$html .= '[<font color=000000><b>'.$this->last_query.'</b></font>]</font><p>
					</blockquote><hr noshade color=dddddd size=1>';
			
		return $html;
	}
}


/**
 * Base class for ezDB
 *
 */
class ezDB_Base
{
	
	public $trace            = false;  // same as $debug_all
	public $debug_all        = false;  // same as $trace
	public $debug_called     = false;
	public $vardump_called   = false;
	public $show_errors      = true;
	public $num_queries      = 0;
	public $last_query       = null;
	public $error		      = null;
	public $errno			  = null;
	public $col_info         = null;
	public $captured_errors  = array();
	public $insert_id;
	
	public $table_prefix = '';
	
	protected $dbuser = false;
	protected $dbpassword = false;
	protected $dbname = false;
	protected $dbhost = false;
	public $result;
	
	public $default_type = OBJECT;
	public $get_col_info = false;
	
	public $debug_echo_is_on = true;
	public $throw_exceptions = true;
	
	/* These settings are handled by __set() below, but can still
		be called as $db->cache_type = '...';, just under-go some
		checking to make sure that they're valid */
	protected $settings = array(
		'cache_type'	=> 'file',
		);
		
	public $cache_timeout	= 3600;		# In seconds
	public $cache_dir       = false;	# Directory to cache to if using 'file'
	public $cache_queries   = false;
	public $cache_inserts   = false;
	public $use_disk_cache  = false;
	
	protected $last_result;	
	
	public function __construct()
	{
		# Check for memcache support
	}
	
	public function __set($name, $value)
	{
		switch($name)
		{
			case 'cache_type':
				$this->set_cache_type($value);
				break;
				
			default:
				$this->settings[$name] = $value;
				break;
		}
		
	}
	
	public function __get($name)
	{
		if(!isset($this->settings[$name]))
		{
			$this->settings[$name] = null;
			return null;
		}
		
		return $this->settings[$name];
	}
	
	/**
	 * Clear any previous errors
	 */
	public function clear_errors()
	{
		$this->error = '';
		$this->errno = 0;
	}	
	
	
	/**
	 * Set a table prefix for quick_*() functions
	 *
	 * @param string $prefix Table prefix
	 * @return none 
	 *
	 */
	public function set_table_prefix($prefix='')
	{
		$this->table_prefix = $prefix;
	}
	
	
	/**
	 * Set the option to throw exceptions or not
	 *
	 * @param bool $bool True or false
	 * @return none 
	 *
	 */
	public function throw_exceptions($bool=true)
	{
		$this->throw_exceptions = $bool;
	}
	
	/**
	 * Enable or disable caching, can be set per-query
	 * 
	 * @param bool $bool True/False
	 * @return none
	 */
	public function set_caching($bool)
	{
		
		if($bool === true)
		{
			$this->cache_query = true;
			$this->use_disk_cache = true;
		}
		else
		{
			$this->cache_query = false;
			$this->use_disk_cache = false;			
		}
	}
	
	/**
	 * Set the cache type (file, memcache, check)
	 *
	 * @param string $type Caching type
	 * @return none 
	 *
	 */
	public function set_cache_type($type)
	{
		if($type == 'memcache')
		{
			$this->settings['cache_type'] = 'file'; # Not enabled for now
			
			if($this->throw_exceptions)
				throw new ezDB_Error('memcache not available', -1);
				
			return false;
		}
		elseif($type == 'apc')
		{
			if(!function_exists('apc_add'))
			{
				$this->settings['cache_type'] = 'file';
				
				if($this->throw_exceptions)
					throw new ezDB_Error('apc not available', -1);
					
				return false;
			}
		}
		else
		{
			# Default to file if they selected anythign weird
			$this->settings['cache_type'] = 'file';
		}
		
		return true;
	}
	
	
	/**
	 * Set the path of the cache
	 *
	 * @param mixed $path This is a description
	 * @return mixed This is the return value description
	 *
	 */
	public function set_cache_dir($path)
	{
		$this->cache_dir = $path;
	}
	
	public function set_cache_timeout($timeout)
	{
		$this->cache_timeout= $timeout;
	}
	
	
	/**
	 * Save an error that occurs in our log
	 *
	 * @param string $err_str This is the error string
	 * @param int $err_no This is the error number
	 * @return bool True
	 *
	 */
	public function register_error($err_str, $err_no=-1)
	{
		// Keep track of last error
		$this->error = $err_str;
		$this->errno = $err_no;
	
		// Capture all errors to an error array no matter what happens
		$this->captured_errors[] = array(
							'error' => $err_str,
							'errno' => $err_no,
							'query' => $this->last_query);
			
		//show output if enabled
		//$this->show_errors ? trigger_error($this->error . '(' . $this->last_query . ')', E_USER_WARNING) : null;
	}
	
	
	/**
	 * Get the error log from all the query
	 *
	 * @return array Queries and their error/errno values
	 *
	 */
	public function get_all_errors()
	{
		return $this->captured_errors;
	}
	
		
	/**
	 * Returns the error string from the previous query
	 *
	 * @return string Error string
	 *
	 */
	public function error()
	{
		return $this->error;
	}
	
	
	/**
	 * Returns the error code from the previous query
	 *
	 * @return mixed Error code
	 *
	 */
	public function errno()
	{
		return $this->errno;
	}
	
	/**
	 * Show all errors by default
	 *
	 * @return bool true
	 *
	 */
	public function show_errors()
	{
		$this->show_errors = true;
		return true;
	}
	
	
	/**
	 * Hide any errors from showing by default.
	 * Can also access the property as $this->show_errors=false
	 *
	 * @return bool true
	 *
	 */
	public function hide_errors()
	{
		$this->show_errors = false;
		return true;
	}
	
	/**
	 * Remove the results from the last query
	 *
	 * @return bool Returns true
	 *
	 */
	public function flush()
	{
		// Get rid of these
		$this->last_result = null;
		$this->col_info = null;
		$this->last_query = null;
		$this->from_disk_cache = false;
		
		return true;
	}


	public function num_queries()
	{
		return $this->num_queries;
	}
			
	/**
	 * Get a single column/variable
	 *
	 * @param string $query SQL query
	 * @param int $x Column offset (default 0, returns first column)
	 * @param int $y Row offset (default 0, first row returned)
	 * @return mixed Returns the value of the variable
	 *
	 */
	public function get_var($query=null,$x=0,$y=0)
	{
		
		// Log how the function was called
		$this->func_call = "\$db->get_var(\"$query\",$x,$y)";
		
		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}
		
		// Extract var out of cached results based x,y vals
		if ( $this->last_result[$y] )
		{
			$values = array_values(get_object_vars($this->last_result[$y]));
		}
		
		// If there is a value return it else return null
		return (isset($values[$x]) && $values[$x]!=='')?$values[$x]:null;
	}
	
		
	/**
	 * Return one row from the DB query (use if your doing LIMIT 1)
	 *	or are expecting/want only one row returned
	 *
	 * @param string $query The SQL Query
	 * @param type $output OBJECT (fastest, default), ARRAY_A, ARRAY_N
	 * @param string $y Row offset (0 for first, 1 for 2nd, etc)
	 * @return type Returns type as defined in $output
	 *
	 */
	public function get_row($query=null,$output='',$y=0)
	{
		if($output == '') $output = $this->default_type;
		
		// Log how the function was called
		$this->func_call = "\$db->get_row(\"$query\",$output,$y)";
		
		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}
		
		// If the output is an object then return object using the row offset..
		if ( $output == OBJECT )
		{
			return $this->last_result[$y]?$this->last_result[$y]:null;
		}
		// If the output is an associative array then return row as such..
		elseif ( $output == ARRAY_A )
		{
			return $this->last_result[$y]?get_object_vars($this->last_result[$y]):null;
		}
		// If the output is an numerical array then return row as such..
		elseif ( $output == ARRAY_N )
		{
			return $this->last_result[$y]?array_values(get_object_vars($this->last_result[$y])):null;
		}
		// If invalid output type was specified..
		else
		{
			$this->print_error(" \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N");
		}
		
	}
	
	
	/**
	 * Quote a value properly, check if its a function first though
	 * For instance, passing NOW() will just return NOW. Just a simple
	 * preg_match for string and a ( brace
	 *
	 * @param mixed $value Value to quote
	 * @return mixed Returns a quoted or non quoted value
	 *
	 */
	public function quote($value)
	{
		if(is_numeric($value))
		{
			return $value;
		}
		
		return '\''.$value.'\'';
	}	
	
	
	/**
	 * Build a SELECT SQL Query. All values except for
	 *  numeric and NOW() will be put in quotes
	 * 
	 * Values are NOT escaped
	 *
	 * @param string $table Table to build update on
	 * @param array $fields Associative array, with [column]=value
	 * @param string $cond Extra conditions, without WHERE
	 * @return array Results
	 *
	 */
	public function quick_select($table, $fields='', $cond='')
	{
		if($table == '') return false;

		$sql = 'SELECT ';
		$list = array();
		if(is_array($fields))
		{
			$fields = implode(',', $fields);
		}
		
		$sql .= $fields;
		$sql .= ' FROM '.$table;

		$sql .= $this->build_where($cond);
		
		return	$this->get_results($sql);
	}
	
	
	/**
	 * Build a quick INSERT query. For simplistic INSERTs only,
	 *  all values except numeric and NOW() are put in quotes
	 * 
	 * Values are NOT escaped
	 *
	 * @param string $table Table to insert into
	 * @param array $fields Associative array [column] = value
	 * @param string $flags Extra INSERT flags to add
	 * @return bool Results
	 *
	 */
	public function quick_insert($table, $fields, $flags= '', $allowed_cols='')
	{
		if($table ==  '') return false;
		
		$sql = 'INSERT '. $flags .' INTO '.$table.' ';
		
		$cols = array();
		$col_values = array();

		if(is_array($allowed_cols) && count($allowed_cols) > 0)
		{
			foreach($allowed_cols as $column)
			{
				$cols[] = "`{$column}`";
				$col_values[] = "'".$this->escape($fields[$column])."'";

				//$cols .= $column.',';
				//$col_values .= $this->escape($fields[$column]).',';
			}
		}
		else
		{
			if(is_array($fields))
			{
				foreach($fields as $key => $value)
				{
					// build both strings
					$cols[] = "`{$key}`";

					//$cols .= '`'.$key.'`, ';

					if($value == 'NOW()')
						$col_values[] = 'NOW()';
					else
						$col_values[] = "'".$this->escape($value)."'";
				}
			}
		}
				
		$sql .= '('.implode(', ', $cols).') VALUES ('.implode(', ', $col_values).')';
			
		return $this->query($sql);
	}
	
	
	/**
	 * Build a UPDATE SQL Query. All values except for
	 *  numeric and NOW() will be put in quotes
	 * 
	 * Values are NOT escaped
	 *
	 * @param string $table Table to build update on
	 * @param array $fields Associative array, with [column]=value
	 * @param string $cond Extra conditions, without WHERE
	 * @return bool Results
	 *
	 */
	public function quick_update($table, $fields, $cond='', $allowed_cols='')
	{
		if($table ==  '') return false;
		
		$sql = 'UPDATE '.$table.' SET ';
		
		/* If they passed an associative array of col=>value */
		if(is_array($fields))
		{
			/* Passed an array with the allowed columns */
			if(is_array($allowed_cols) && count($allowed_cols) > 0)
			{
				$allowed = array();
				foreach($allowed_cols as $column)
				{
					$allowed[$column] = $fields[$column];
				}

				$sql .= $this->build_update($allowed);
			}
			/* No specific columns, just process all the $fields */
			else
			{
				$sql.= $this->build_update($fields);
			}
		}
		else
		{
			$sql .= $fields;
		}
		
		$sql .= $this->build_where($cond);
		
		return $this->query($sql);
	}

	/**
	 * Build a SELECT query, specifying the table, fields and extra conditions
	 *
	 * @param array $data
	 * 
	 * $data = array(
	 *		'table' => 'Tablename',
	 *		'fields' => array(fields),
	 *		'where' => array(fields),
	 *		'order' => 'field ASC',
	 *		'group' => 'field',
	 * );
	 * 
	 * @return type Array of results
	 *
	 */
	public function build_select($params)
	{
		if(!is_array($params)) return;
		if($params['table'] == '') return false;
					
		$sql = 'SELECT ';
		
		if(is_array($params['fields']))
		{
			$sql .= implode(',', $params['fields']);
		}
		else
		{
			$sql .= $params['fields'];
		}
		
		$sql .= ' FROM '.$params['table'];
		
		if(!empty($params['where']))
		{
			$sql .= $this->build_where($params['where']);
		}
			
		if(!empty($params['group']))
		{
			$sql .= ' GROUP BY '.$params['group'];
		}
		
		if(!empty($params['order']))
		{
			$sql .= ' ORDER BY '.$params['order'];
		}
		
		if(!empty($params['limit']))
		{
			$sql .= ' LIMIT '.$params['limit'];
		}
		
		return $sql;
	}
	
	public function build_where($fields)
	{
		if(count($fields) === 0 || empty($fields) === true)
		{
			return '';
		}

		// It's a string
		if(!is_array($fields) && !is_object($fields))
		{
			$fields = str_ireplace('WHERE', '', $fields);
			return ' WHERE '.$fields;
		}
		
		// Cast it to an array...
		if(is_object($fields))
		{
			$fields = (array) $fields;
		}

		$sql = ' WHERE ';
		
		$where_clauses = array();
		foreach($fields as $column_name => $value)
		{
			# Convert to $columnname IN ($value)
			if(is_array($value))
			{
				$sql_temp = "{$column_name} IN (";
				
				$value_list = array();
				foreach($value as $in)
				{
					$in = $this->escape($in);
					$value_list[] = "'{$in}'";
				}
				
				$sql_temp .= implode(',', $value_list).")";
				$where_clauses[] = $sql_temp;
			}
			else
			{
				# If there's no value per-say, just a field value
				if(is_int($column_name))
				{
					$where_clauses[] = $value;
					continue;
				}
				
				# If there's a % (wildcard) in there, so it should use a LIKE
				if(substr_count($value, '%') > 0)
				{
					$value = $this->escape($value);
					$where_clauses[] = "{$column_name} LIKE '{$value}'";
					continue;
				}
				
				# If it's a greater than or equal to, or for some reason an equals
				if($value[0] == '<' || $value[0] == '>' || $value[0] == '=')
				{
					$where_clauses[] = "{$column_name} {$value}";
					continue;
				}
				
				$value = $this->escape($value);
				$where_clauses[] = "{$column_name} = '{$value}'";
			}
		}
			
		$sql.= implode(' AND ', $where_clauses).' ';
		unset($where_clauses);
		
		return $sql;		
	}
	
	public function build_update($fields)
	{
		if(!is_array($fields))
		{
			return $fields;
		}
		
		$sql = '';
		$sql_cols = array();
		
		foreach($fields as $col => $value)
		{
			/* If there's a value just added */
			if(is_int($col))
			{
				$sql_cols[] = $value;
				continue;
			}
			
			$tmp = "`{$col}`=";
			
			if(is_int($value))
			{
				$tmp .= $value;
			}
			else
			{
				if($value === "NOW()")
				{
					$tmp.='NOW()';
				}
				else
				{
					$value = $this->escape($value);
					$tmp.="'{$value}'";
				}
			}
			
			$sql_cols[] = $tmp;
		}
		
		$sql .= implode(', ', $sql_cols);
		unset($sql_cols);
		
		return $sql;
	}
		
	/**
	 * Get the value of one column from a query
	 *
	 * @param string $query The SQL query
	 * @param string $x Column to return
	 * @return array Return's the results of that one column
	 *
	 */
	public function get_col($query=null,$x=0)
	{
		
		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}
		
		// Extract the column values
		for ( $i=0; $i < count($this->last_result); $i++ )
		{
			$new_array[$i] = $this->get_var(null,$x,$i);
		}
		
		return $new_array;
	}
		
	/**
	 * Returns the query as a set of results. Default returns OBJECT,
	 * that is much faster than translating to ARRAY_A or ARRAY_N
	 *
	 * @param string $query SQL query
	 * @param define $output OBJECT, ARRAY_A (associative array), ARRAY_N (numeric indexed). OBJECT is default and fastest
	 * @return object Array of results, each array value being what $output is defined as
	 *
	 */
	public function get_results($query=null, $output = '')
	{
		if($output == '') $output = $this->default_type;
		
		// Log how the function was called
		$this->func_call = "\$db->get_results(\"$query\", $output)";
		
		// If there is a query then perform it if not then use cached results..
		if ( $query )
		{
			$this->query($query);
		}
				
		// Send back array of objects. Each row is an object
		if ( $output == OBJECT )
		{
			return $this->last_result;
		}
		elseif ( $output == ARRAY_A || $output == ARRAY_N )
		{
			if ( $this->last_result )
			{
				$i=0;
				foreach( $this->last_result as $row )
				{
					$new_array[$i] = get_object_vars($row);
					
					if ( $output == ARRAY_N )
					{
						$new_array[$i] = array_values($new_array[$i]);
					}
					
					$i++;
				}
				
				return $new_array;
			}
			else
			{
				return null;
			}
		}
	}

	/**
	 * Return all of the columns
	 * 
	 * @return array Column information
	 */
	public function get_cols()
	{
		
		return $this->col_info;

	}
		
	/**
	 * Get metadata regarding a column, about a column in the last query
	 *
	 * @param string $info_type Column information type to get
	 * @param int $col_offset Column number, -1 returns all columns
	 * @return array Column information
	 *
	 */
	public function get_col_info($info_type='name',$col_offset=-1)
	{
		if ($this->col_info )
		{
			if ( $col_offset == -1 )
			{
				$i=0;
				foreach($this->col_info as $col )
				{
					$new_array[$i] = $col->{$info_type};
					$i++;
				}
				return $new_array;
			}
			else
			{
				return $this->col_info[$col_offset]->{$info_type};
			}
		}
		
		return false;
	}
	
	
	/**
	 * Store a results in the cache for a certain query
	 *
	 * @param string $query SQL query to store
	 * @param bool $is_insert If it's an INSERT or not
	 * @return bool Success
	 *
	 */
	public function store_cache($query,$is_insert)
	{
		if($this->cache_query === false || $is_insert)
			return false;
			
		$result_cache = array('col_info' => $this->col_info,
								'last_result' => $this->last_result,
								'num_rows' => $this->num_rows,
								'return_value' => $this->num_rows);
		
		if($this->cache_type == 'memcache')
		{
			// @TODO: memcache 
		}
		elseif($this->cache_type == 'apc')
		{
			apc_add(md5($query), $result_cache, $this->cache_timeout);
		}
		else
		{
			$cache_file = $this->cache_dir.'/'.md5($query);

			if (!is_dir($this->cache_dir) )
			{
				$this->register_error("Could not open cache dir: $this->cache_dir");
				return false;
			}
			
			$ttl = strtotime('+'.$this->cache_timeout.' seconds');
			$value = $ttl.PHP_EOL.serialize($result_cache);

			$fp = fopen($cache_file, 'w');
			flock($fp);
			fwrite($fp, $value);
			fclose($fp);

		}
		
		return true;		
	}
	
	
	/**
	 * Get the cached results for a query. This is called more internally
	 *
	 * @param string $query SQL query to return results for
	 * @return mixed Returns the unserialized results
	 *
	 */
	public function get_cache($query)
	{
		if($this->cache_query === false || $is_insert)
			return false;
		
		# Check if we want to us memcache, and whether it's available
		if($this->cache_type == 'memcache')
		{
			// @TODO: memcache 
		}
		elseif($this->cache_type == 'apc')
		{
			$result_cache = apc_fetch(md5($query), $ret);
		}
		# Use type "file" for any other cache_type
		else
		{
			// The would be cache file for this query
			$cache_file = $this->cache_dir.'/'.md5($query);
			
			// Try to get previously cached version
			if (file_exists($cache_file) )
			{
				$contents = file($cache_file);
			
				# See if the current time is greater than that cutoff
				if(time() > $contents[0])
				{
					return false;
				}
			
				# Then return the unserialized version of the store
				$result_cache = unserialize($contents[1]);
			}
		}	
		
		$this->from_cache = true;
		$this->col_info = $result_cache['col_info'];
		$this->last_result = $result_cache['last_result'];
		$this->num_rows = $result_cache['num_rows'];
		
		$this->trace || $this->debug_all ? $this->debug() : null ;
		return $result_cache['return_value'];
	}
	
	
	/**
	 * Show values of any variable type "nicely"
	 *
	 * @param mixed $mixed Variable to show
	 * @param bool $return Return the results or show on screen
	 * @return mixed This is the return value description
	 *
	 */
	public function vardump($mixed='', $return=false)
	{
		
		// Start outup buffering
		ob_start();
		
		echo "<p><table><tr><td bgcolor=ffffff><blockquote><font color=000090>";
		echo "<pre><font face=arial>";
		
		if ( ! $this->vardump_called )
		{
			echo "<font color=800080><b>ezDB</b> (v".ezDB_VERSION.") <b>Variable Dump..</b></font>\n\n";
		}
		
		$var_type = gettype ($mixed);
		print_r(($mixed?$mixed:"<font color=red>No Value / False</font>"));
		echo "\n\n<b>Type:</b> " . ucfirst($var_type) . "\n";
		echo "<b>Last Query</b> [$this->num_queries]<b>:</b> ".($this->last_query?$this->last_query:"NULL")."\n";
		echo "<b>Last Function Call:</b> " . ($this->func_call?$this->func_call:"None")."\n";
		echo "<b>Last Rows Returned:</b> ".count($this->last_result)."\n";
		echo "</font></pre></font></blockquote></td></tr></table>".$this->donation();
		echo "\n<hr size=1 noshade color=dddddd>";
		
		// Stop output buffering and capture debug HTML
		$html = ob_get_contents();
		ob_end_clean();
		
		// Only echo output if it is turned on
		if ( $this->debug_echo_is_on || $return == false)
		{
			echo $html;
		}
		
		$this->vardump_called = true;
		
		return $html;
		
	}
	
	/**
	 * Show values of any variable type "nicely"
	 *
	 * @param mixed $mixed Variable to show
	 * @param bool $return Return the results or show on screen
	 * @return mixed This is the return value description
	 *
	 */
	public function dumpvar($mixed, $return=false)
	{
		$this->vardump($mixed, $return);
	}
		
	/**
	 *  Displays the last query string that was sent to the database & a
	 * table listing results (if there were any).
	 * (abstracted into a seperate file to save server overhead).
	 *
	 * @param bool $return Return the results, or display right away
	 * @return string The debug table is $return = true
	 *
	 */
	public function debug($return=false)
	{
		
		// Start outup buffering
		ob_start();
		
		echo "<blockquote>";
		
		// Only show ezDB credits once..
		if ( ! $this->debug_called )
		{
			echo "<font color=800080 face=arial size=2><b>ezDB</b> (v".ezDB_VERSION.") <b>Debug..</b></font><p>\n";
		}
		
		if ( $this->error )
		{
			echo "<font face=arial size=2 color=000099><b>Last Error --</b> [<font color=000000><b>$this->error ($this->errno)</b></font>]<p>";
		}
		
		if ( $this->from_disk_cache )
		{
			echo "<font face=arial size=2 color=000099><b>Results retrieved from disk cache</b></font><p>";
		}
		
		echo "<font face=arial size=2 color=000099><b>Query</b> [$this->num_queries] <b>--</b> ";
		echo "[<font color=000000><b>$this->last_query</b></font>]</font><p>";
		
		echo "<font face=arial size=2 color=000099><b>Query Result..</b></font>";
		echo "<blockquote>";
		
		if ( $this->col_info )
		{
			
			// =====================================================
			// Results top rows
			
			echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
			echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";
			
			
			for ( $i=0; $i < count($this->col_info); $i++ )
			{
				echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->col_info[$i]->type} {$this->col_info[$i]->max_length}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->col_info[$i]->name}</span></td>";
			}
			
			echo "</tr>";
			
			// ======================================================
			// print main results
			
			if ( $this->last_result )
			{
				
				$i=0;
				foreach ( $this->get_results(null,ARRAY_N) as $one_row )
				{
					$i++;
					echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";
					
					foreach ( $one_row as $item )
					{
						echo "<td nowrap><font face=arial size=2>$item</font></td>";
					}
					
					echo "</tr>";
				}
				
			} // if last result
			else
			{
				echo "<tr bgcolor=ffffff><td colspan=".(count($this->col_info)+1)."><font face=arial size=2>No Results</font></td></tr>";
			}
			
			echo "</table>";
			
		} // if col_info
		else
		{
			echo "<font face=arial size=2>No Results</font>";
		}
		
		echo "</blockquote></blockquote>".$this->donation()."<hr noshade color=dddddd size=1>";
		
		// Stop output buffering and capture debug HTML
		$html = ob_get_contents();
		ob_end_clean();
		
		// Only echo output if it is turned on
		if ( $this->debug_echo_is_on || $return == false )
		{
			echo $html;
		}
		
		$this->debug_called = true;
		
		return $html;
		
	}
	
	/**********************************************************************
	*  Naughty little function to ask for some remuniration!
	*/
	
	public function donation()
	{
		return "<font size=1 face=arial color=000000>If ezDB has helped <a href=\"https://www.paypal.com/xclick/business=justin%40justinvincent.com&item_name=ezDB&no_note=1&tax=0\" style=\"color: 0000CC;\">make a donation!?</a> &nbsp;&nbsp;<!--[ go on! you know you want to! ]--></font>";
	}
	
}