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
 
 class ActivityData extends CodonData {
    
 
    /**
     * ActivityData::getActivity()
     * 
     * @param integer $count
     * @return void
     */
    public static function getActivity($where = '', $limit = 10, $start = '') {
        
        $sql = "SELECT p.*, a.*
				FROM `".TABLE_PREFIX."activityfeed` a
				LEFT JOIN `".TABLE_PREFIX."pilots` p ON p.pilotid=a.pilotid ";

        if(is_array($where)) {
            $sql .= DB::build_where($where);    
        }
        
        $sql .= ' ORDER BY `submitdate` DESC';

        if (strlen($limit) != 0) {
            $sql .= ' LIMIT ' . $limit;
        }

        if (strlen($start) != 0) {
            $sql .= ' OFFSET ' . $start;
        }
        
        $ret = DB::get_results($sql);
        return $ret;
        
    }
    
    
    /**
     * ActivityData::addActivity()
     * 
     * @param mixed $params
     * @return void
     */
    public static function addActivity($params) {
        
        $params = array_merge(array(
            'pilotid' => '',
            'type' => '',
            'refid' => '',
            'message' => '',
            'submitdate' => 'NOW()',
            'checkrefid' => false, 
        ), $params);
        
        if($params['checkrefid'] === true) {
            $exists = self::getActivity(array('a.refid' => $params['refid']), 1);
            if($exists) {
                return false;
            }
        }
    
        $sql = 'INSERT INTO `'.TABLE_PREFIX.'activityfeed`
                (`pilotid`, `refid`, `type`, `message`, `submitdate`)
                VALUES
                ('.$params['pilotid'].','.$params['refid'].','.$params['type']
                  .',\''.DB::escape($params['message']).'\', '.$params['submitdate'].')';
        
        $ret = DB::query($sql);
        
        if(Config::get('TWITTER_ENABLE_PUSH') == true) {
            self::pushToTwitter($params);
        }
            
    }
    
    /**
     * ActivityData::pushToTwitter()
     * 
     * @param mixed $params
     * @return void
     */
    public static function pushToTwitter($params) {
        
        require_once CORE_LIB_PATH.'/twitteroauth/twitteroauth.php';
        
        $params = array_merge(array(
            'pilotid' => '',
            'type' => '',
            'refid' => '',
            'message' => '',
        ), $params);
        
        $message = '';
        # These defaults will be ignored
        $lat = -120;
        $long = -120; 
        
        if(!empty($params['pilotid'])) {
            $pilot = PilotData::getPilotData($params['pilotid']);
            $message .= PilotData::getPilotCode($pilot->code, $pilot->pilotid)
                     . ' ' .$pilot->firstname.' '.$pilot->lastname.' ';
        }
        
        $message .= $params['message'].' ';
        
        # Show a new PIREP, also get the airport information
        if($params['type'] == ACTIVITY_NEW_PIREP) {
            $message .= url('/pireps/view/'.$params['refid']);
            
            $pirep = PIREPData::findPIREPS(array('pirepid'=>$params['refid']), 1);
            $pirep = $pirep[0];
            
            $airport = OperationsData::getAirportInfo($pirep->arricao);
            $lat = $airport->lat;
            $long = $airport->lng;
                        
        } elseif($params['type']== ACTIVITY_NEW_PILOT) {
            
            $message .= url('/profile/view/'.$params['pilotid']);
            
            $airport = OperationsData::getAirportInfo($pilot->hub);
            $lat = $airport->lat;
            $long = $airport->lng;
        } 
        
        $tweet = new TwitterOAuth(
            Config::get('TWITTER_CONSUMER_KEY'), 
            Config::get('TWITTER_CONSUMER_SECRET'), 
            Config::get('TWITTER_OAUTH_TOKEN'), 
            Config::get('TWITTER_OAUTH_SECRET')
        );
        
        $status = $tweet->post('statuses/update', array(
            'status' => $message,
            'lat' => $lat,
            'long' => $long,
            'trim_user' => true,
        ));
        
        return $status;
    }
 
 
    /**
     * Import the latest data from Twitter into the activity feed
     * 
     * @return void
     */
    public static function readTwitter() {
        
        # Don't pull data from Twitter if we are pushing
        if(Config::get('TWITTER_ENABLE_PUSH') == true) {
            return false;
        }
        
        $twitterAccount = Config::get('TWITTER_AIRLINE_ACCOUNT');
        if(empty($twitterAccount)) {
            return false;
        }
        
        $twitterURL = TWITTER_STATUS_URL.$twitterAccount;
        
        $lastsubmit = self::getActivity(array('a.type' => ACTIVITY_TWITTER), 1);
        if(count($lastsubmit) > 0) {
            $twitterURL .= '&since_id='.$lastsubmit[0]->refid;
        }
        
        $cws = new CodonWebService();
        $feed_contents = $cws->get($twitterURL);
        $feed_contents = json_decode($feed_contents);
     
        if(isset($feed_contents->error)) {
            self::log('TWITTER ERROR: '.$feed_contents->request ."\nError:".$feed_contents->error);
            return false;
        }
        
        if(!is_array($feed_contents) || count($feed_contents) == 0) {
            return false; 
        }
        
        foreach($feed_contents as $tweet) {
            
            $date_created = strtotime($tweet->created_at);
            
            self::addActivity(array(
                'pilotid' => 0,
                'type' => ACTIVITY_TWITTER,
                'refid' => $tweet->id,
                'message' => DB::escape($tweet->text),
                'submitdate' => 'FROM_UNIXTIME('.$date_created.')',
                'checkrefid' => true,
            ));
        }
        
        CronData::set_lastupdate('twitter_update');
    }
 
 }
 
 
 
 
 
 