<?php
/**

 * VAOS Legacy Compatability for the
 * phpVMS Codon PHP Framework
 * www.fsvaos.com/legacy
 * Software License Agreement (BSD License)
 *
 * Modifications Copyright (c) 2016 Taylor Broad, cardinalhorizon.com
 * 
 * For information about backwards compatibility in future VAOS releases,
 * visit http://fsvaos.com/legacy
 *
 * phpVMS and Codon PHP Framework
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
 */

/* This is for a popup box, or an AJAX call
	Don't show the site header/footer
*/

// Turn on the lights to the old hangar (Composer Bootstrap).

require __DIR__ . '/../legacy/vendor/autoload.php';

// Fire up the database connections the new way.

require __DIR__ . '/../legacy/database.php';

// Fire up the old girl the old fashioned way (until I get a better loader)

define('CODON_MODULES_PATH', dirname(__FILE__).'/../legacy/modules');
define('CODON_DEFAULT_MODULE', 'Frontpage');
include '../legacy/codon.config.php';

define('SKINS_PATH', LIB_PATH.DS.'skins'.DS.CURRENT_SKIN);
 
$BaseTemplate = new TemplateSet;

//load the legacy notification
$settings_file = SKINS_PATH . '/' . CURRENT_SKIN . '.php';
if(file_exists($settings_file)) {
    include $settings_file;
}

$BaseTemplate->template_path = SKINS_PATH;
$BaseTemplate->skin_path = SKINS_PATH;

Template::Set('MODULE_NAV_INC', $NAVBAR);
Template::Set('MODULE_HEAD_INC', $HTMLHead);

MainController::RunAllActions();

# Force connection close
DB::close();