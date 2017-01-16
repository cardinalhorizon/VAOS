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

class LedgerData extends CodonData {
    
    /**
     * LedgerData::getPaymentByPIREP()
     * 
     * @param mixed $id
     * @return
     */
    public static function getPaymentByID($id) {
        
        return DB::get_row(
            'SELECT * FROM `'.TABLE_PREFIX.'ledger` 
            WHERE `id`='.$id
        );
        
    }
    
    
    /**
     * LedgerData::getPaymentByPIREP()
     * 
     * @param mixed $pirepid
     * @return void
     */
    public static function getPaymentByPIREP($pirepid) {
        
        return DB::get_row(
            'SELECT * FROM `'.TABLE_PREFIX.'ledger` 
             WHERE `pirepid`='.$pirepid
        );
        
    }
    
    
    /**
     * LedgerData::getTotalForMonth()
     * 
     * @param mixed $timestamp
     * @return void
     */
    public static function getTotalForMonth($timestamp) {
        
        $total = DB::get_row(
            "SELECT SUM(`amount`) as `total`
            FROM `".TABLE_PREFIX."ledger`
            WHERE DATE_FORMAT(submitdate, '%Y-%m') = DATE_FORMAT(FROM_UNIXTIME(".$timestamp."), '%Y-%m')
                AND `paysource` = ".PAYSOURCE_PIREP
        );
        
        return $total->total;
    }
    
    /**
     * Add a payment for a pilot (give them money)
     * 
     * @param mixed $params
     * @return void
     */
    public static function addPayment($params) {
        
        $params = array_merge(array(
            'pilotid' => '',
            'pirepid' => 0,
            'paysource' => PAYSOURCE_PIREP,
            'paytype' => 1,
            'amount' => 0,
        ), $params);
        
        if(empty($params['pilotid'])) {
            return false;
        }
        
        $sql = 'INSERT INTO `'.TABLE_PREFIX.'ledger`
                  (`pilotid`, `pirepid`, `paysource`, `paytype`, `amount`, `submitdate`, `modifieddate`)
                VALUES ('
                    .$params['pilotid'].','
                    .$params['pirepid'].','
                    .$params['paysource'].','
                    .$params['paytype'].','
                    .$params['amount'].', NOW(), NOW()
                );';
        
        DB::query($sql);
        
        PilotData::resetPilotPay($params['pilotid']);
    }
    
    /**
     * LedgerData::editPayment()
     * 
     * @param mixed $id
     * @param mixed $params
     * @return
     */
    public static function editPayment($id, $params) {
        
        $params = array_merge(array(
            'pilotid' => '',
            'pirepid' => 0,
            'paytype' => 1,
            'amount' => 0,
        ), $params);
        
        $sql = "UPDATE `".TABLE_PREFIX."ledger` SET ";
        $sql .= DB::build_update($params);
        $sql .= " WHERE `id`=".$id;
        
        $ret = DB::query($sql);
        
        PilotData::resetPilotPay($params['pilotid']);
        
        return $ret;
    }
    
    
    /**
     * PilotData::deletePaymentByID()
     * 
     * @param mixed $id
     * @return
     */
    public static function deletePaymentByID($id) {
        
        $payment = self::getPaymentByID($id);
        
        $ret = DB::query(
            'DELETE FROM `'.TABLE_PREFIX.'ledger`
             WHERE `pirepid`='.$pirep_id
        );
        
        self::resetPilotPay($payment->pilotid);
        
        return $ret;
    }   
    
    /**
     * LedgerData::deletePayment()
     * 
     * @param mixed $pirep_id
     * @return void
     */
    public static function deletePaymentByPIREP($pirepid) {
        
        $payment = self::getPaymentByPIREP($pirepid);
        
        $ret = DB::query(
            'DELETE FROM `'.TABLE_PREFIX.'ledger`
             WHERE `pirepid`='.$pirepid
        );
        
        PilotData::resetPilotPay($payment->pilotid);
        
        return $ret;
    }
    
}