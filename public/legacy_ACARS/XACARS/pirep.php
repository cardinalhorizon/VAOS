<?php

/**
 * PIREP Service for XAcars
 *
 */


// ----------------------------------------------------------------------------
// In functions.php the database is connected as used to
require("../boot.php");

// ----------------------------------------------------------------------------
// General Constants

define("ACARS_UNKNOWN",     0);
define("ACARS_XACARS",      3);
define("ACARS_XACARS_MSFS", 4);
define("ONLINE_VATSIM",     1);
define("ONLINE_IVAO",       2);
define("ONLINE_FPI",        3);
define("ONLINE_OTHER",      0);


// ----------------------------------------------------------------------------
// PIREP Class

Class acarsPirep
{
    var $pirepID       = 0;
    var $timeReport    = 0;
    var $acarsID       = ACARS_UNKNOWN;
    var $userID        = 0;
    var $flightRot     = 0;
    var $acICAO        = '';
    var $flightType    = 'IFR';
    var $departure     = '';
    var $destination   = '';
    var $alternate     = '';

    var $depTime       = '00:00';
    var $blockTime     = 0;
    var $blockFuel     = 0;
    var $flightTime    = 0;
    var $flightFuel    = 0;

    var $cruise        = 0;
    var $pax           = 0;
    var $cargo         = 0;
    var $online        = 0;
    var $message       = '';
            
    function doInsert()
    {
        $sql  = 'INSERT INTO acars_pirep (timereport, acarsid, iduser, flightrot, acicao, 
                    flighttype, departure, destination, alternate, deptime, blocktime, blockfuel, flighttime,
                    flightfuel, cruise, pax, cargo, online, message) VALUES ( ' 
              . $this->timeReport . ', '
              . $this->acarsID . ', '
              . $this->userID . ', '        
              . "'{$this->flightRot}', "
              . "'{$this->acICAO}', "
              . "'{$this->flightType}', "
              . "'{$this->departure}', "
              . "'{$this->destination}', "
              . "'{$this->alternate}', "
              . $this->depTime . ', '
              . $this->blockTime . ', '
              . $this->blockFuel . ', '
              . $this->flightTime . ', '
              . $this->flightFuel . ', '
              . $this->cruise . ', '
              . $this->pax . ', '
              . $this->cargo . ', '
              . $this->online . ', '
              . "'{$this->message}');";
              
        if(!mysql_query($sql))
            die('0|SQL query failed (INSERT acars pirep)   ' . $sql);
        
        $this->pirepID = mysql_insert_id();
                              
        return 1;
    }
            
}

// ----------------------------------------------------------------------------
// Functions

function testUserLogin($pid, $pw)
{
    $client = new GuzzleHttp\Client();

    $res = $client->request('POST', VAOS_URL.'api/v1/auth', [
        'query' => [
            'format' => 'username'
        ],
        'form_params' => [

            'username' => $pid,
            'password' => $pw,

        ]
    ])->getBody();

    $jdec = json_decode($res, true);

    if ($jdec['status'] == 200)
        return $jdec['user']['id'];
    else
        return -1;
}

function CheckXAcarsVersion($DATA1)
{
    if( (strcmp($DATA1, "XACARS|1.0")==0)
        || (strcmp($DATA1, "XACARS|1.1")==0)
        || (strcmp($DATA1, "XACARS|2.0")==0)        
        || (strcmp($DATA1, "XACARS|2.5")==0) 
        || (strcmp($DATA1, "XACARS|3.0")==0) )        
        return ACARS_XACARS;
    elseif( (strcmp($DATA1, "XACARS_MSFS|1.0")==0)
        || (strcmp($DATA1, "XACARS_MSFS|1.1")==0)
        || (strcmp($DATA1, "XACARS_MSFS|2.0")==0)        
        || (strcmp($DATA1, "XACARS_MSFS|2.5")==0) 
        || (strcmp($DATA1, "XACARS_MSFS|3.0")==0) ) 
        return ACARS_XACARS_MSFS;
    else
        return ACARS_UNKNOWN;
}

Function data2Int( $data, $default )
{
    if( !empty($data) || (isset($data) && $data == '0') )
        return( (integer)$data );
    else
        return $default;
}

Function data2Str( $data, $default, $allowTags, $stripSql = True )
{
    if( !empty($data) )
    {
        if( $stripSql == True)
            $str = mysqlclean($data, -1);
        else
            $str = $data;
            
        if( $allowTags == True )
            return( strip_tags($str, ALLOWABLE_TAGS) );
        else
            return( strip_tags($str) );
    }
    else
        return $default;
}

Function time2min( $time )
{
    if( !empty($time) )
        return( substr($time,0,2)*60 + substr($time,3,2) );
    else
        return 0;
}

Function lbs2kg( $lbs )
{
    return( $lbs / 2.204622915 );
}

// ----------------------------------------------------------------------------
// Decoding ACARS Message

if (!isset($_REQUEST['DATA1'])) die('0|Invalid Data1');
if (!isset($_REQUEST['DATA2'])) die('0|Invalid Data2');

if (($version  = CheckXAcarsVersion($_REQUEST['DATA1'])) <= 0)
    die('0|ERROR: Unknown XAcars version!');

$data2 = $_REQUEST['DATA2'];
$data = explode("~", $data2);
$client = new GuzzleHttp\Client();
$res = $client->request('POST', VAOS_URL.'api/v1/auth', [
    'query' => [
        'format' => 'username'
    ],
    'form_params' => [

        'username' => $data[0],
        'password' => $data[1],

    ]
])->getBody();

$jdec = json_decode($res, true);


//$uid = testUserLogin($data[0], $data[1]);
if ($jdec['status'] != 200)
    die('0|Invalid Username/Password');

// ----------------------------------------------------------------------------
// Parameterlist:
// DATA1=XACARS|1.0&DATA2=username~password~flightnumber~aircrafticao~altitudeorFL~flightrules~depicao~desticao~alticao~deptime(dd.mm.yyyy hh:mm)~blocktime(hh:mm)~flighttime(hh:mm)~blockfuel~flightfuel~pax~cargo~online(VATSIM|ICAO|FPI|[other])

$pirepdata = array(
    'pilotid' => $jdec['user']['id'],
    'flightnum' => $data[2],
    'landingrate' => 1,
    'comment' => "Landing Rate Not Supported",
    'source' => 'XACARS'
);
//var_dump($pirepdata);


$ret = $client->request('POST', VAOS_URL.'api/v1/pireps', [
    'query' => [
        'format' => 'phpVMS'
    ],
    'form_params' => [
        'pilotid' => $jdec['user']['id'],
        'flightnum' => $data[2],
        'landingrate' => 1,
        'comment' => "Landing Rate Not Supported",
        'source' => 'XACARS'
    ]
])->getBody();

//echo $ret;
$jdec = json_decode($ret, true);
if ($jdec['status'] == 200)
{
    echo "1|PIREP ACCEPTED";
}
else
{
    echo "0|ERROR";
}
