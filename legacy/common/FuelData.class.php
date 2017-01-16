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

class FuelData extends CodonData {
    /**
     * Get the current fuel price for an airport, returns it in the
     * unit specified in the config file
     *
     * @param string $apt_icao ICAO of the airport
     * @return float Fuel price
     *
     * @version 709 rewritten
     */
    public static function getFuelPrice($apt_icao) {
        
        $price = false;
        $aptinfo = OperationsData::GetAirportInfo($apt_icao);

        // Live pricing enabled, and the airport is set to '0' for a live price
        if (Config::Get('FUEL_GET_LIVE_PRICE') == true 
                && ($aptinfo->fuelprice == '0' || $aptinfo->fuelprice == '')) {
            $price = self::get_cached_price($apt_icao);

            if (!$price) {
                $price = self::get_from_server($apt_icao);

                if (!is_bool($price)) {
                    return $price; // Returns the JetA price
                }
            } else {
                return $price->jeta;
            }
        }

        /* Live price stuff above failed or was "off" */
        if ($aptinfo->fuelprice == '' || $aptinfo->fuelprice == 0) return Config::Get('FUEL_DEFAULT_PRICE');
        else  return $aptinfo->fuelprice;
    }


    /**
     * Grab a cached version of the fuel price, check for three days worth
     *
     * @param mixed $apt_icao ICAO of airport
     * @return mixed This is the return value description
     *
     */
    protected static function get_cached_price($apt_icao) {
        $apt_icao = strtoupper($apt_icao);
        $sql = "SELECT * FROM `" . TABLE_PREFIX . "fuelprices`
				WHERE `icao`='$apt_icao' 
					AND (DATEDIFF(NOW(), `dateupdated`) < 3)";

        return DB::get_row($sql);
    }


    /**
     * Save the fuel price in our local cache so the api
     *  server won't get hammered
     *
     * @param object $xmlObj The object with the fuel data
     * @return mixed This is the return value description
     *
     */
    protected static function save_cached_price($xmlObj) {
        $query = "SELECT * 
					FROM `" . TABLE_PREFIX . "fuelprices`
					WHERE `icao`='{$xmlObj->icao}'";

        $res = DB::get_row($query);

        if ($res) {
            $query = "UPDATE `" . TABLE_PREFIX . "fuelprices`
						SET `icao`='{$xmlObj->icao}', 
							`lowlead`='{$xmlObj->lowlead}', 
							`jeta`='{$xmlObj->jeta}', 
							dateupdated=NOW()
						WHERE `id`={$res->id}";
        } else {
            $query = "INSERT INTO `" . TABLE_PREFIX . "fuelprices`
							(`icao`, 
							 `lowlead`, 
							 `jeta`,
							 `dateupdated`)
						VALUES ('{$xmlObj->icao}', 
								'{$xmlObj->lowlead}', 
								'{$xmlObj->jeta}', 
								NOW())";

        }

        DB::query($query);
    }


    /**
     * Ask the API server for information about the fuel price
     *  This ignores the cache for retrieval, but does save
     *  to the cache on successful completion. Returns false
     *  if failed
     * 
     * Best practice is to use GetFuelPrice() which will
     *  check the cache before checking the server
     *
     * @param string $apt_icao Airport ICAO
     * @return float Returns the JET-A fuelprice
     *
     */
    public static function get_from_server($apt_icao) {
        if ($apt_icao == '') return false;

        # Bug fix, get the proper units from API server
        $unit = Config::Get('LIQUID_UNIT_NAMES', Config::Get('LiquidUnit'));

        $url = Config::Get('PHPVMS_API_SERVER') . '/fuel/get/' . $apt_icao . '/' . $unit;

        $curl_loader = new CodonWebService();
        $resp = $curl_loader->get($url);

        if ($resp == '' || $resp === false) return false;

        $results = @simplexml_load_string($resp);

        if ($results === false) {
            return false;
        }

        # Error message tag was there
        if (isset($results->errormessage)) {
            return false;
        } else {
            self::save_cached_price($results);
            return $results->jeta;
        }
    }
}
