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
 

class Pages extends CodonModule
{
	public function NavBar()
	{
		$this->set('allpages', SiteData::getAllPages(true, Auth::$loggedin));
		$this->render('pages_items.tpl');
	}
	
	public function __call($name, $args)
	{
		// $name here is the filename, but we don't call it in directly
		//	for security reasons
		
		$page = DB::escape($name);
		$pageinfo = SiteData::GetPageDataByName($page);
		
		if($pageinfo->public == 0 && Auth::LoggedIn() == false) {
			$this->render('pages_nopermission.tpl');
			return;
		}
		
		$content = SiteData::GetPageContent($page);
		if(!$content) {
			$this->render('pages_notfound.tpl');
		} else {
			// Do it this way, so then that this page/template
			//	can be customized on a skin-by-skin basis
			$this->title = $content->pagename;
			$this->set('pagename', $content->pagename);
			
			# Do entity encoding, compensate for a html_entity_decode() in the templates
			$this->set('content', htmlentities($content->content));
			
			$this->render('pages_content.tpl');
		}
	}
}