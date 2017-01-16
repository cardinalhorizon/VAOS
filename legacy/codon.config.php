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
 
session_start();

error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'on');

define('DS', DIRECTORY_SEPARATOR);
define('SITE_ROOT', str_replace('core', '', dirname(__FILE__)));
define('CORE_PATH', dirname(__FILE__) );
define('CORE_LIB_PATH', CORE_PATH.DS.'lib');
define('CLASS_PATH', CORE_PATH.DS.'classes');
define('LOGS_PATH', CORE_PATH.DS.'logs');
define('TEMPLATES_PATH', CORE_PATH.DS.'templates');
define('MODULES_PATH', CORE_PATH.DS.'modules');
define('CACHE_PATH', CORE_PATH.DS.'cache');
define('COMMON_PATH', CORE_PATH.DS.'common');
define('PAGES_PATH', CORE_PATH.DS.'pages');
define('LIB_PATH', SITE_ROOT.DS.'lib');
define('VENDORS_PATH', CORE_PATH.DS.'vendors');

$version = phpversion();
if(intval($version[0]) < 5) {
	die('You are not running PHP 5+');
}
// VAOS BOOTLOADER

require 'VAOS_Boot.php';

$dotenv = new \Dotenv\Dotenv(__DIR__."/../");
$dotenv->load();

// Load VAOS

require CLASS_PATH.DS.'autoload.php';
spl_autoload_register('codon_autoload');

Config::Set('MODULES_PATH', CORE_PATH.DS.'modules');
Config::Set('MODULES_AUTOLOAD', true);

Template::init();

require CORE_PATH.DS.'app.config.php';
@include CORE_PATH.DS.'local.config.php';

/* Set the language */
Lang::set_language(Config::Get('SITE_LANGUAGE'));

error_reporting(Config::Get('ERROR_LEVEL'));
Debug::$debug_enabled = Config::Get('DEBUG_MODE');

if(Debug::$debug_enabled == true) {
    ini_set('log_errors','On');
    ini_set('display_errors', 'Off');
    ini_set('error_log', LOGS_PATH.'/errors.txt');
}

/* Init caching engine */
CodonCache::init($cache_settings);

if(DBASE_NAME != '' && DBASE_SERVER != '' && DBASE_NAME != 'DBASE_NAME') {
    
	require CLASS_PATH.DS.'ezdb/ezdb.class.php';
	
	DB::$show_errors = Config::Get('DEBUG_MODE');
	DB::$throw_exceptions = false;
	
	DB::init(DBASE_TYPE);
	
	DB::set_log_errors(Config::Get('DEBUG_MODE'));
	DB::set_error_handler(array('Debug', 'db_error'));
	
	DB::set_caching(false);
	DB::$table_prefix = TABLE_PREFIX;
	DB::set_cache_dir(CACHE_PATH);
	DB::$DB->debug_all = false;
	
	if(Config::Get('DEBUG_MODE') == true) {
	   DB::show_errors();
	} else {
	   DB::hide_errors();
	}		
		
	if(!DB::connect(DBASE_USER, DBASE_PASS, DBASE_NAME, DBASE_SERVER)) {	
		Debug::showCritical(Lang::gs('database.connection.failed').' ('.DB::$errno.': '.DB::$error.')');
		die();
	}
	
	# Set the charset type to send to mysql
	if(Config::Get('DB_CHARSET_NAME') !== '') {
		DB::query('SET NAMES \''.Config::Get('DB_CHARSET_NAME').'\'');
	}
    
    
    # Include ORM
    #include_once(VENDORS_PATH.DS.'orm'.DS.'idiorm.php');
    #include_once(VENDORS_PATH.DS.'orm'.DS.'paris.php');
    #ORM::configure('mysql:host='.DBASE_SERVER.';dbname='.DBASE_NAME);
    #ORM::configure('username', DBASE_USER);
    #ORM::configure('password', DBASE_PASS);
}

include CORE_PATH.DS.'bootstrap.inc.php';

if(function_exists('pre_module_load')) {
	pre_module_load();
}

MainController::loadEngineTasks();

define('ACTIVE_SKIN_PATH', LIB_PATH.DS.'skins'.DS.CURRENT_SKIN);

Template::setTemplatePath(TEMPLATES_PATH);
Template::setSkinPath(ACTIVE_SKIN_PATH);

if(function_exists('post_module_load'))
	post_module_load();
	
