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

/* Static implementation for TemplateSet
*/


class Template {
    
    public static $tplset;
    
    public static function init() {
        self::$tplset = new TemplateSet();
    }

    public static function setTemplatePath($path) {
        return self::$tplset->setTemplatePath($path);
    }

    public static function setSkinPath($path) {
        return self::$tplset->setSkinPath($path);
    }

    public static function setTemplateExt($ext) {
        self::$tplset->tpl_ext = $ext;
    }

    public static function EnableCaching($bool = true) {
        self::$tplset->enable_caching = $bool;
    }

    public static function ClearVars() {
        self::$tplset->ClearVars();
    }

    public static function showVars() {
        return self::$tplset->showvars();
    }

    public static function set($name, $value) {
        return self::$tplset->Set($name, $value);
    }

    public static function show($tpl_name) {
        return self::$tplset->ShowTemplate($tpl_name);
    }

    public static function get($tpl_path, $ret = false, $checkskin = true, $force_base = false) {
        return self::$tplset->GetTemplate($tpl_path, $ret, $checkskin, $force_base);
    }

    public static function showTemplate($tpl_name, $checkskin = true) {
        return self::$tplset->ShowTemplate($tpl_name, $checkskin);
    }

    public static function getTemplate($tpl_path, $ret = false, $checkskin = true, $force_base = false) {
        return self::$tplset->getTemplate($tpl_path, $ret, $checkskin, $force_base);
    }

    public static function showModule($ModuleName, $Method) {
        return self::$tplset->ShowModule($ModuleName, $Method);
    }
}
