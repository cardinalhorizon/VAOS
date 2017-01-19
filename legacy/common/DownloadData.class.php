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


/**
 * This class is the model for the Downloads
 *
 */
class DownloadData extends CodonData {

    /**
     * Get all of the categories for the downloads
     *
     * @return array Returns all of hte categories
     *
     */
    public static function GetAllCategories() {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'downloads
					WHERE pid=0';

        return DB::get_results($sql);
    }


    /**
     * Return as asset (category or download)
     *
     * @param int $id ID of the asset (ID column)
     * @return array Asset data row
     *
     */
    public static function GetAsset($id) {
        $id = DB::escape($id);

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'downloads
					WHERE id=' . $id;

        return DB::get_row($sql);
    }


    /**
     * Find a category given the name
     *
     * @param string $categoryname Category name
     * @return array Category row
     *
     */
    public static function findCategory($categoryname) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'downloads
					WHERE name=\'' . $categoryname . '\' AND pid=0';

        return DB::get_row($sql);
    }


    /**
     * Get all of the downloads in a certain category
     *
     * @param int $categoryid the ID of the category
     * @return array Array of objects of all the downloads
     *
     */
    public static function GetDownloads($categoryid) {
        if ($categoryid == '') return false;

        $categoryid = intval($categoryid);

        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'downloads
					WHERE pid=' . $categoryid;

        return DB::get_results($sql);
    }

    public static function GetAllDownloads() {

    }

    public static function AddCategory($name, $link = '', $image = '') {
        if ($name == '') return false;

        $sql = "INSERT INTO " . TABLE_PREFIX . "downloads
					(pid, name, link, image)
				VALUES	(0, '$name', '$link', '$image')";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    public static function RemoveCategory($id) {
        if ($id == '') return false;
        $id = intval($id);

        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'downloads
					WHERE pid=' . $id;

        DB::query($sql);

        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'downloads
					WHERE id=' . $id;

        DB::query($sql);
    }

    /**
     * $data = array(
     * 'parent_id' => '',
     * 'name' => '',
     * 'description' => '',
     * 'link' => '',
     * 'image' => '',
     * );
     */
    public static function AddDownload($data) {
        /*$data = array(
        'parent_id' => '',
        'name' => '',
        'description' => '',
        'link' => '',
        'image' => '',
        );*/

        if ($data['parent_id'] == '') return false;
        if ($data['name'] == '') return false;

        $data['parent_id'] = intval($data['parent_id']);
        $data['name'] = DB::escape($data['name']);

        $sql = "INSERT INTO " . TABLE_PREFIX . "downloads
				(`pid`, `name`, `description`, `link`, `image`, `hits`)
				VALUES	({$data['parent_id']}, '{$data['name']}', '{$data['description']}', 
						'{$data['link']}', '{$data['image']}', 0)";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }


    /**
     * $data = array(
     * 'id' => ''
     * 'parent_id' => '',
     * 'name' => '',
     * 'description' => '',
     * 'link' => '',
     * 'image' => '',
     * );
     *
     * @param mixed $data This is a description
     * @return mixed This is the return value description
     *
     */
    public static function EditAsset($data) {
        /*$data = array(
        'id' => ''
        'parent_id' => '',
        'name' => '',
        'description' => '',
        'link' => '',
        'image' => '',
        );
        */
        if ($data['id'] == '' || $data['name'] == '') return false;

        $data['id'] = intval($data['id']);
        $data['name'] = DB::escape($data['name']);
        $data['parent_id'] = intval($data['parent_id']);

        $sql = "UPDATE " . TABLE_PREFIX . "downloads
					SET `pid`={$data['parent_id']}, `name`='{$data['name']}', 
						`description`='{$data['description']}', link='{$data['link']}', image='{$data['image']}'
					WHERE id={$data['id']}";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    public static function RemoveAsset($id) {
        if ($id == '') return false;

        $id = intval($id);

        $sql = "DELETE FROM " . TABLE_PREFIX . "downloads
					WHERE id=$id";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }

    public static function IncrementDLCount($id) {
        $sql = 'UPDATE ' . TABLE_PREFIX . 'downloads
					SET hits=hits+1
					WHERE id=' . intval($id);

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        return true;
    }
}
