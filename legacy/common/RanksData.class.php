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

class RanksData extends CodonData {
    static $lasterror;

    /**
     * Return information about the rank, given the ID
     */
    public static function getRankInfo($rankid) {
        $sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ranks
					WHERE rankid=' . $rankid;

        return DB::get_row($sql);
    }

    public static function getRankByName($name) {
        $sql = 'SELECT * 
				FROM `' . TABLE_PREFIX . "ranks`
				WHERE `rank`='{$name}'";

        return DB::get_row($sql);
    }

    /**
     * Returns all the ranks, and the total number of pilots
     * on each rank
     */
    public static function getAllRanks() {
        
        $allranks = CodonCache::read('all_ranks');

        if ($allranks === false) {
            $sql = 'SELECT r.*, 
                        (SELECT COUNT(*) FROM ' . TABLE_PREFIX .'pilots WHERE rank=r.rank) as totalpilots
					FROM ' . TABLE_PREFIX . 'ranks r
					ORDER BY r.minhours ASC';

            $allranks = DB::get_results($sql);
            CodonCache::write('all_ranks', $allranks, 'long');
        }

        return $allranks;
    }

    public static function getRankImage($rank) {
        $sql = 'SELECT `rankimage` FROM ' . TABLE_PREFIX . 'ranks WHERE rank="' . $rank .
            '"';
        return DB::get_var($sql);
    }

    /**
     * Get the level the passed rank is in the list
     */
    public static function getRankLevel($rankid) {
        
        if ($rankid == 0) {
            return 0;
        }
        
        $all_ranks = self::getAllRanks();

        $i = 0;
        foreach ($all_ranks as $rank) {
            
            $i++;

            if ($rank->rankid == $rankid) {
                return $i;
            }
        }

        return 0;
    }

    /**
     * Give the number of hours, return the next rank
     */
    public static function getNextRank($hours) {
        $sql = "SELECT * FROM " . TABLE_PREFIX . "ranks
				WHERE minhours>$hours ORDER BY minhours ASC LIMIT 1";        
        return DB::get_row($sql);
    }

    /**
     * Add a ranking. This will automatically call
     * CalculatePilotRanks() at the end
     */
    public static function addRank($title, $minhours, $imageurl, $payrate) {
        
        $minhours = intval($minhours);
        $payrate = floatval($payrate);

        $sql = "INSERT INTO " . TABLE_PREFIX .
                "ranks (rank, rankimage, minhours, payrate)
                VALUES('$title', '$imageurl', '$minhours', $payrate)";

        $ret = DB::query($sql);

        if (DB::$errno == 1062) {
            self::$lasterror = 'This already exists';
            return false;
        }

        CodonCache::delete('all_ranks');
        self::calculatePilotRanks();

        return true;
    }

    /**
     * Update a certain rank
     */
    public static function updateRank($rankid, $title, $minhours, $imageurl, $payrate) {
        
        $minhours = intval($minhours);
        $payrate = floatval($payrate);

        $sql = "UPDATE " . TABLE_PREFIX . "ranks
					SET rank='$title', rankimage='$imageurl', minhours='$minhours', payrate=$payrate
					WHERE rankid=$rankid";

        $res = DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_ranks');

        self::calculatePilotRanks();
        return true;
    }

    /**
     * Delete a rank, and then recalculate
     */

    public static function deleteRank($rankid) {
        
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'ranks WHERE rankid=' . $rankid;

        DB::query($sql);

        if (DB::errno() != 0) return false;

        CodonCache::delete('all_ranks');
        self::CalculatePilotRanks();
        return true;
    }

    /**
     * Go through each pilot, check their hours, and see where they
     *  stand in the rankings. If they are above the minimum hours
     *  for that rank level, then make $last_rank that text. At the
     *  end, update that
     */
    public static function calculatePilotRanks() {
        
        /* Don't calculate a pilot's rank if this is set */
        if (Config::Get('RANKS_AUTOCALCULATE') === false) {
            return;
        }

        $ranks_list = self::getAllRanks();
        $pilots = PilotData::getAllPilots();
        if (count($pilots) == 0 || !is_array($pilots)) {
            return;
        }

        foreach ($pilots as $pilot) {
            self::calculateUpdatePilotRank($pilot->pilotid, $ranks_list);
        }
    }

    public static function calculateUpdatePilotRank($pilotid, $ranks_list = null) {
        
        /* Don't calculate a pilot's rank if this is set */
        if (Config::Get('RANKS_AUTOCALCULATE') == false) {
            return;
        }

        if($ranks_list === null) {
            $ranks_list = self::getAllRanks();
        }
        
        $pilotid = intval($pilotid);
        
        $pilot = PilotData::getPilotData($pilotid);
        $pilothours = $pilot->totalhours;
        
        if (Config::Get('TRANSFER_HOURS_IN_RANKS') == true) {
            $pilothours += $pilot->transferhours;
        }

        $i = 0;
        foreach ($ranks_list as $rank) {
            
            $i++;

            if ($pilothours >= intval($rank->minhours)) {
                $rank_level = $i;
                $last_rank = $rank->rank;
                $last_rankid = $rank->rankid;
            }
        }

        $update = array(
            'rankid' => $last_rankid, 
            'rank' => $last_rank, 
            'ranklevel' => $rank_level, 
            );

        PilotData::updateProfile($pilot->pilotid, $update);
        
        if($pilot->rank != $last_rank) {
        
            $message = Lang::get('activity.pilot.promotion');
            $message = str_replace('$rank', $last_rank, $message);
            
            # Add it to the activity feed
            ActivityData::addActivity(array(
                'pilotid' => $pilotid,
                'type' => ACTIVITY_PROMOTION,
                'refid' => $pilotid,
                'message' => htmlentities($message),
            ));   
            
        }
    }
}
