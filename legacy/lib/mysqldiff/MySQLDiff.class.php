<?php
/**
 * MySQLDiff
 * 
 * @package MySQLDiff
 * @author Nabeel Shahzad <https://github.com/nshahzad/MySQLDiff>
 */
class MySQLDiff {
    
    public $xml_errors = array();
    public $sql_errors = array();
    
    protected $params;
    protected $db;
    protected $xml;
    
    protected $missingCols = array();
    
    /**
     * MySQLDiff::construct()
     * 
     * @param mixed $params
     * @return
     */
    public function __construct($params) {
    
        if(!is_array($params)) {
            throw new Exception("Invalid parameters passed");
            return false;
        }
                
        $this->params = array_merge(array(
            'dbuser' => '',
            'dbpass' => '',
            'dbname' => '',
            'dbhost' => '',
            'dumpxml' => '',
        ), $params);
        
        # Connect to MySQL
        $this->db = mysql_connect(
            $this->params['dbhost'], 
            $this->params['dbuser'], 
            $this->params['dbpass'],
            true
        );
        
        if(!$this->db) {
            throw new Exception("Could not connect to {$this->params['dbuser']}@{$this->params['dbserver']}");
            return false;
        }
        
        if(!mysql_select_db($this->params['dbname'], $this->db)) {
            throw new Exception("Could not select database {$this->params['dbname']}");
            return false;
        }
        
        
        if(!file_exists($this->params['dumpxml'])) {
            throw new Exception("XML File \"{$this->params['dumpxml']}\" does not exist!");
            return false;
        }
        
        # Load the XML file
        libxml_use_internal_errors (true);
        $this->xml = simplexml_load_file($this->params['dumpxml']);
        if($this->xml === false) {
            #$this->xml_errors = implode("\n", libxml_get_errors());
            throw new Exception ("Errors in XML File: {$this->xml_errors}");
            return false;
        }
    }
    
    /**
     * Get a list of diffs, returns the table and fields within it
     * which are missing
     * 
     * @return array
     */
    public function getDiffs() {
        return $this->findDiffs();
    }
    
    /**
     * Return MySQL queries to add the missing columns into the table
     * 
     * @return void
     */
    public function getSQLDiffs() {
        
        $diffData = $this->findDiffs();
        if(count($diffData) == 0) {
            return $diffData;
        }
        
        $sqlList = array();
        
        # Add tables...
        foreach($diffData['tables'] as $table) {
            
            if(count($table) == 0) { # This table exists...
                continue;
            }

            $sql = array();
            $sql[] = 'CREATE TABLE `'.$table['Name'].'` (';
                        
            $colList = array();
            foreach($diffData['columns'][$table['Name']] as $column) {
                $colList[] = $this->getColumnLine($column);
            }
            
            $sql[] = implode(',', $colList);
            $sql[] = ')';
            
            $sql[] = 'ENGINE = '.$table['Engine'];
            $sql[] = 'AUTO_INCREMENT = '.$table['Auto_increment'];
            $sql[] = 'COMMENT = \''.$table['Comment'].'\'';
            $sql[] = 'COLLATE '.$table['Collation'];
            
            # Remove it from the columns list
            unset($diffData['columns'][$table['Name']]);
            
            $sqlList[] = implode(' ',$sql).';';
        }    
        
        # Now add columns....    
        foreach($diffData['columns'] as $tableName => $columnList) {
                
            foreach($columnList as $columnName => $column) {
                            
                $sql = array();    
                
                $sql[] = 'ALTER TABLE `'.$tableName.'` ADD';
                $sql[] = $this->getColumnLine($column);
                
                if($column['prevField'] === null) {
                    $sql[] = 'FIRST'; # Insert at top of table
                } else {
                    $sql[] = 'AFTER `'.$column['prevField'].'`';   
                }
                
                $sqlList[] = trim(implode(' ', $sql)).';';
            }
        }
        
        # ALTER TABLES for TYPES
        foreach($diffData['types'] as $tableName => $columnList) {
                
            foreach($columnList as $columnName => $column) {
               
                $sql = array();    
                
                $sql[] = 'ALTER TABLE `'.$tableName.'` CHANGE';
                $sql[] = '`'.$columnName.'`';
                $sql[] = $this->getColumnLine($column['newtype']);
                
                $sqlList[] = trim(implode(' ', $sql)).';';
            }
        }
        
        
        # Now create the SQL for generating indexes
        foreach($diffData['indexes'] as $tableName => $indexes) {
            
            if(count($indexes) == 0) {
                continue;
            }
            
            foreach($indexes as $index) {
                $sqlList[] = $this->getIndexLine($index);
            }
        }
        
        return $sqlList;
    }
    
    /**
     * MySQLDiff::getColumnLine()
     * 
     * @param mixed $column
     * @return void
     */
    protected function getColumnLine($column) {
        
        $sql = array();
        
        $sql[] = '`'.$column['Field'].'` '.$column['Type'];
                
        # Is this column null?
        if(strtolower(trim($column['Null'])) == 'no') {
            $sql[] = 'NOT NULL';
        } else {
            $sql[] = 'NULL';
        }
        
        # Is there a default value?
        if(isset($column['Default'])) {
            $sql[] = 'DEFAULT \''.$column['Default'].'\'';
        }
        
        # Any extra stuffs?
        if(isset($column['Extra'])) {
            $sql[] = strtoupper(trim($column['Extra']));
        }
        
        if(isset($column['Key'])) {
            $key = strtolower(trim($column['Key']));
            if($key == 'pri') {
                $sql[] = 'PRIMARY KEY';
            }/* elseif($key =='uni') {
                $sql[] = 'UNIQUE';
            }*/
        }
        
        return implode(' ', $sql);
    }
    
    
    /**
     * Create an ALTER TABLE to create indexes
     * 
     * @param mixed $index
     * @return
     */
    protected function getIndexLine($index) {
        $sql = array();
        
        $sql[] = 'ALTER TABLE `'.$index['Table'].'` ADD';
        
        if($index['Key_name'] == 'PRIMARY') {
            $sql[] = 'PRIMARY KEY ('.$index['Column_name'].')';
        } else {
            if($index['Non_unique'] == '0') {
                $sql[] = 'UNIQUE';
            } else {
                #$sql[] = $index['Index_type'];
                if(strtolower(trim($index['Index_type'])) == 'fulltext') {
                    $sql[] = 'FULLTEXT';
                }
            }
            
            $sql[] = 'INDEX ('.$index['Column_name'].')';
        }
        
        return implode(' ', $sql).';';
    }
    
    
    /**
     * Generate diffs from MySQL and the XML file, and then apply them
     * 
     * @return void
     */
    public function runSQLDiff() {
        
        $sqlList = $this->getSQLDiffs();
        
        foreach ($sqlList as $sql) {
            $res = mysql_query($sql, $this->db);
            #if(!$res) {
            #    throw new Exception(mysql_errno().': '.mysql_error());
            #}
            
            if(mysql_errno() != 0) {
                $this->sql_errors[] = array(
                    'sql' => $sql,
                    'errno' => mysql_errno(),
                    'error' => mysql_error()
                );
            }
        }
        
        return $sqlList;
    }
    
    /**
     * MySQLDiff::findDiffs()
     * 
     * @return void
     */
    protected function findDiffs() {
        
        $this->missingCols = array();
        
        $this->missingCols['tables'] = array();
        $this->missingCols['columns'] = array();
        $this->missingCols['types'] = array();
        $this->missingCols['indexes'] = array();
            
        foreach($this->xml->database->table_structure as $table) {
            
            $tableName = (string) $table['name'];
            
            # Get a list of columns from this table...
        	$desc_result = mysql_query('DESCRIBE '.$tableName, $this->db);
            
            # Make sure table exists...
            $columns = array();
            if(mysql_errno() == 1146) {
                foreach($table->options->attributes() as $key => $value) {
  		            $this->missingCols['tables'][$tableName][$key] = (string) $value;
                }
        	} else {
                # Get list of columns
                while($column = mysql_fetch_object($desc_result)) {
                    $columns[] = $column;
                }
            }
                    	
        	/* loop through all the columns returned by the above query and all the columns
        		from the fields in the xml file, and make sure they all match up, with the
        		fieldlist from the xml being the "master" outside loop which it looks up against 
             */
            $prevField = null;
        	foreach($table->field as $field) {
 	              
                $fieldName = strtolower(trim((string) $field['Field']));
                
        		$found = false;
        		foreach($columns as $column) {  	
        		  
        			if(strtolower(trim($column->Field)) == $fieldName) {
        			 
                        /* Check the column type, etc, see if those differ */
                        if((strtolower(trim($column->Type)) != (string) $field['Type'])
                            || (strtolower(trim($column->Null)) != strtolower(trim((string) $field['Null'])))
                            || (strtolower(trim($column->Default)) != strtolower(trim((string) $field['Default'])))
                            || (strtolower(trim($column->Extra)) != strtolower(trim((string) $field['Extra'])))
                        ) {
                            $this->missingCols['types'][$tableName][$fieldName]['oldtype'] = $column;
                            $this->missingCols['types'][$tableName][$fieldName]['newtype'] = $field;
                        }
                        
        				$found = true;
        				break;
        			}
        		}
        		
        		if($found == false) {
        		  
  		            # Add all attributes in, but not as SimpleXML objects
  		            $this->missingCols['columns'][$tableName][$fieldName] = array();
  		            foreach($field->attributes() as $key => $value) {
  		                $this->missingCols['columns'][$tableName][$fieldName][$key] = (string) $value;
  		            }
                    
                    # Also add the previous field, so we know where to place it...
                    $this->missingCols['columns'][$tableName][$fieldName]['prevField'] = $prevField;
        		}
                
                $prevField = $fieldName;
        	}
            
            
            # Find any missing indexes
            $indexes = array();
            $res = mysql_query('SHOW INDEXES IN '.$tableName);
            if($res) {
                while($index = mysql_fetch_object($res)) {
                    $indexes[] = $index;
                }
            }
            
            foreach($table->key as $tablekey) {
                
                $keyName = strtolower(trim($tablekey['Key_name']));
                
                $found = false;
        		foreach($indexes as $index) {  		   
        			if(strtolower(trim($index->Key_name)) == $keyName) {
        				$found = true;
        				break;
        			}
        		}
                
        		if($found == false) {
                    $this->missingCols['indexes'][$tableName][$keyName] = array();
                    foreach($tablekey->attributes() as $key => $value) {
  		                $this->missingCols['indexes'][$tableName][$keyName][$key] = (string) $value;
  		            }
                    
                    $this->missingCols['indexes'][$tableName][$keyName]['table'] = $tableName;
                }
                
            }
        }
        
        return $this->missingCols;
    }
}

























