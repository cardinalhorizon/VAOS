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

class Activity extends CodonModule 
{
    /**
     * Activity::index()
     * 
     * @return void
     */
    public function index() {
        
        $activities = ActivityData::getActivity(array(), $count);
        if(!$activities) {
            $activities = array();
        }
        
        $this->set('allactivities', $activities);
        $this->render('activity_list.tpl');
    }
    
    /**
     * Activity::frontpage()
     * 
     * @param integer $count
     * @return void
     */
    public function frontpage($count = 20)
    {
        $activities = ActivityData::getActivity(array(), $count);
        if(!$activities) {
            $activities = array();
        }
        
        $this->set('allactivities', $activities);
        $this->render('activity_list.tpl');
    }

}
