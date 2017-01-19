<?php

/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class SiteData extends CodonData {

    /**
     * SiteData::loadSiteSettings()
     *
     * @return
     */
    public static function loadSiteSettings() {
        $all_settings = SettingsData::getAllSettings();

        if (!$all_settings) {
            return false;
        }

        foreach ($all_settings as $setting) {
            if ($setting->name == 'PHPVMS_VERSION') continue;

            //correct value for booleans
            if ($setting->value == 'true') {
                $setting->value = true;
            } elseif ($setting->value == 'false') {
                $setting->value = false;
            }

            define($setting->name, $setting->value);
        }

        /*define('PHPVMS_VERSION',file_get_contents(SITE_ROOT.'/core/version'));*/
    }

    /**
     * SiteData::getNewsItem()
     *
     * @param mixed $id
     * @return
     */
    public static function getNewsItem($id) {
        return DB::get_row('SELECT *, UNIX_TIMESTAMP(postdate) AS postdate
									FROM ' . TABLE_PREFIX . 'news WHERE id=' . $id);
    }

    /**
     * SiteData::getAllNews()
     *
     * @return
     */
    public static function getAllNews() {
        return DB::get_results('SELECT id, subject, body, UNIX_TIMESTAMP(postdate) as postdate, postedby
									FROM ' . TABLE_PREFIX . 'news ORDER BY postdate DESC');
    }

    /**
     * SiteData::AddNewsItem()
     *
     * @param mixed $subject
     * @param mixed $body
     * @return
     */
    public static function AddNewsItem($subject, $body) {

        $subject = DB::escape($subject);
        $body = DB::escape($body);
        $postedby = Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname;

        //added to combat pilot names with apostrophes
        $postedby = addslashes($postedby);

        $sql = 'INSERT INTO ' . TABLE_PREFIX . "news (subject, body, postdate, postedby)
					VALUES ('$subject', '$body', NOW(), '$postedby')";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

	/**
	 * Set the last modified date of the news item to now
	 * @param $id
	 * @return bool
	 */
	public static function bumpNewsItem($id) {

		$id = DB::escape($id);

		$sql = 'UPDATE '.TABLE_PREFIX.'news SET postdate=NOW() WHERE id='.$id;
		$res = DB::query($sql);

		if (DB::errno() != 0) return false;

		return true;
	}

    /**
     * SiteData::EditNewsItem()
     *
     * @param mixed $id
     * @param mixed $subject
     * @param mixed $body
     * @return
     */
    public static function EditNewsItem($id, $subject, $body) {
        $subject = DB::escape($subject);
        $body = DB::escape($body);

        $sql = 'UPDATE '.TABLE_PREFIX.'news SET subject=\''.$subject.'\', body=\''.$body.'\'
        		WHERE id='.$id;

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * SiteData::DeleteItem()
     *
     * @param mixed $id
     * @return
     */
    public static function DeleteItem($id) {
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'news WHERE id=' . $id;

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    /**
     * SiteData::GetAllPages()
     *
     * @param bool $onlyenabled
     * @param bool $loggedin
     * @return
     */
    public static function GetAllPages($onlyenabled = false, $loggedin = false) {
        $sql = "SELECT * FROM " . TABLE_PREFIX . "pages";

        if ($onlyenabled == true) {
            $sql .= ' WHERE enabled=1';

            if ($loggedin == false) {
                $sql .= ' AND public=1';
            }

        }

        return DB::get_results($sql);
    }

    /**
     * SiteData::GetPageData()
     *
     * @param mixed $pageid
     * @return
     */
    public static function GetPageData($pageid) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pages WHERE pageid=' . $pageid;

        return DB::get_row($sql);
    }

    /**
     * SiteData::GetPageDataByName()
     *
     * @param mixed $pagename
     * @return
     */
    public static function GetPageDataByName($pagename) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pages WHERE filename=\'' . $pagename .
            '\'';

        return DB::get_row($sql);
    }

    /**
     * SiteData::AddPage()
     *
     * @param mixed $title
     * @param mixed $content
     * @param bool $public
     * @param bool $enabled
     * @return
     */
    public static function AddPage($title, $content, $public = true, $enabled = true) {
        $filename = strtolower($title);

        //TODO: replace this with a regex
        $filename = str_replace(' ', '', $filename);
        $filename = str_replace('?', '', $filename);
        $filename = str_replace('!', '', $filename);
        $filename = str_replace('@', '', $filename);
        $filename = str_replace('.', '', $filename);
        $filename = str_replace(',', '', $filename);
        $filename = str_replace('\'', '', $filename);

        $filename = str_replace('+', 'and', $filename);
        $filename = str_replace('&', 'and', $filename);

        //take out any slashes
        $filename = preg_replace('/(\/|\\\)++/', '', $filename);

        if ($public == true) $public = 1;
        else  $public = 0;

        if ($enabled == true) $enabled = 1;
        else  $enabled = 0;

        //$filename .= '.html';
        $postedby = Auth::DisplayName();

        if (DB::get_row('SELECT * FROM ' . TABLE_PREFIX . "pages WHERE pagename='$title'")) {
            return false;
        }

        $sql = "INSERT INTO " . TABLE_PREFIX .
            "pages (pagename, filename, postedby, postdate, public, enabled)
					VALUES ('$title', '$filename', '$postedby', NOW(), $public, $enabled)";

        $ret = DB::query($sql);

        if (DB::errno() != 0) return false;

        return self::EditPageFile($filename, $content);
    }

    /**
     * SiteData::DeletePage()
     *
     * @param mixed $pageid
     * @return
     */
    public static function DeletePage($pageid) {

        $info = self::GetPageData($pageid);

        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'pages WHERE pageid=' . $pageid;

        @unlink(PAGES_PATH . '/' . $info->filename . PAGE_EXT);

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }


    /**
     * SiteData::GetPageContent()
     *
     * @param mixed $filename
     * @return
     */
    public static function GetPageContent($filename) {
        // Round-about way, I know. But it's in the name of security. If they're giving a
        //	bogus name, then it won't find it.

        $sql = 'SELECT pagename, filename
					FROM ' . TABLE_PREFIX . 'pages
					WHERE filename=\'' . $filename . '\'';
        $row = DB::get_row($sql);

        if (!$row) return;

        //run output buffering, so we can parse any PHP in there
        if (!file_exists(PAGES_PATH . '/' . $row->filename . PAGE_EXT)) {
            return;
        }

        // In case there is PHP present
        ob_start();
        include PAGES_PATH . '/' . $row->filename . PAGE_EXT;
        $row->content = ob_get_contents();
        ob_end_clean();

        return $row;
    }

    /**
     * SiteData::EditFile()
     *
     * @param mixed $pageid
     * @param mixed $content
     * @param mixed $public
     * @param mixed $enabled
     * @return
     */
    public static function EditFile($pageid, $content, $public, $enabled) {
        $pagedata = SiteData::GetPageData($pageid);

        if ($public == true) $public = 1;
        else  $public = 0;

        if ($enabled == true) $enabled = 1;
        else  $enabled = 0;

        $sql = 'UPDATE ' . TABLE_PREFIX . 'pages
				  SET public=' . $public . ', enabled=' . $enabled . '
				  WHERE pageid=' . $pageid;

        DB::query($sql);

        if (self::EditPageFile($pagedata->filename, stripslashes($content))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * SiteData::EditPageFile()
     *
     * @param mixed $filename
     * @param mixed $content
     * @return
     */
    public static function EditPageFile($filename, $content) {
        //create the file
        $filename = PAGES_PATH . '/' . $filename . PAGE_EXT;
        $fp = fopen($filename, 'w');

        if (!$fp) {
            return false;
        } else {
            flock($fp, LOCK_EX);
            fwrite($fp, $content, strlen($content));
            flock($fp, LOCK_UN);
            fclose($fp);
            return true;
        }
    }

    /**
     * SiteData::GetAvailableSkins()
     *
     * @return
     */
    public static function GetAvailableSkins() {
        $skins = array();
        $skins_dir = SITE_ROOT . '/lib/skins';

        if (is_dir($skins_dir)) {
            $fh = opendir($skins_dir);

            while (($file = readdir($fh)) !== false) {

                if ($file == '.' || $file == '..' || $file == '.svn') continue;

                $filepath = $skins_dir . '/' . $file;
                $script_path = '';

                if (is_dir($filepath)) {
                    array_push($skins, $file);
                }
            }
            closedir($fh);
        }

        return $skins;
    }
}
