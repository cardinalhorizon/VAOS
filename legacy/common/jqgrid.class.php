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

/* Common function for the jq grid */


class jqgrid extends CodonData {
    /* This is for jqGrid */
    public static function constructWhere($s) {
        $qwery = "";
        //['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']
        $qopers = array('eq' => " = ", 'ne' => " <> ", 'lt' => " < ", 'le' => " <= ",
            'gt' => " > ", 'ge' => " >= ", 'bw' => " LIKE ", 'bn' => " NOT LIKE ", 'in' =>
            " IN ", 'ni' => " NOT IN ", 'ew' => " LIKE ", 'en' => " NOT LIKE ", 'cn' =>
            " LIKE ", 'nc' => " NOT LIKE ");

        if ($s) {
            $jsona = json_decode($s, true);
            if (is_array($jsona)) {
                $gopr = $jsona['groupOp'];
                $rules = $jsona['rules'];
                $i = 0;
                foreach ($rules as $key => $val) {
                    $field = $val['field'];
                    $op = $val['op'];
                    $v = $val['data'];
                    if ($v && $op) {
                        $i++;
                        // ToSql in this case is absolutley needed
                        $v = self::ToSql($field, $op, $v);
                        if ($i == 1) $qwery = " AND ";
                        else  $qwery .= " " . $gopr . " ";
                        switch ($op) {
                                // in need other thing
                            case 'in':
                            case 'ni':
                                $qwery .= $field . $qopers[$op] . " (" . $v . ")";
                                break;
                            default:
                                $qwery .= $field . $qopers[$op] . $v;
                        }
                    }
                }
            }
        }
        return $qwery;
    }

    /* This is for jqGrid */
    public static function ToSql($field, $oper, $val) {
        // we need here more advanced checking using the type of the field - i.e. integer, string, float
        switch ($field) {
            case 'id':
                return intval($val);
                break;
            case 'amount':
            case 'tax':
            case 'total':
                return floatval($val);
                break;
            default:
                //mysql_real_escape_string is better
                if ($oper == 'bw' || $oper == 'bn') return "'" . addslashes($val) . "%'";
                else
                    if ($oper == 'ew' || $oper == 'en') return "'%" . addcslashes($val) . "'";
                    else
                        if ($oper == 'cn' || $oper == 'nc') return "'%" . addslashes($val) . "%'";
                        else  return "'" . addslashes($val) . "'";
        }
    }

    public static function Strip($value) {
        if (get_magic_quotes_gpc() != 0) {
            if (is_array($value))
                if (self::array_is_associative($value)) {
                    foreach ($value as $k => $v) $tmp_val[$k] = stripslashes($v);
                    $value = $tmp_val;
                } else
                    for ($j = 0; $j < sizeof($value); $j++) $value[$j] = stripslashes($value[$j]);
                    else  $value = stripslashes($value);
        }
        return $value;
    }

    public static function array_is_associative($array) {
        if (is_array($array) && !empty($array)) {
            for ($iterator = count($array) - 1; $iterator; $iterator--) {
                if (!array_key_exists($iterator, $array)) {
                    return true;
                }
            }

            return !array_key_exists(0, $array);
        }

        return false;
    }

}
