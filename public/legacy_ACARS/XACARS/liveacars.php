<?php

/**
 * LiveACARS Service for XAcars
 *
 */

// ----------------------------------------------------------------------------
// In functions.php the database is connected as used to
require("../boot.php");

define("ACARS_UNKNOWN",   -1);
define("ACARS_XACARS",    3);
define("ACARS_XACARS_MSFS",    4);


define("FLIGHTSTATUS_BOARDING", 1);
define("FLIGHTSTATUS_TAXIOUT",  2);
define("FLIGHTSTATUS_DEPARTURE",3);
define("FLIGHTSTATUS_CLIMB",    4);
define("FLIGHTSTATUS_CRUISE",   5);
define("FLIGHTSTATUS_DESC",     6);
define("FLIGHTSTATUS_APPROACH", 7);
define("FLIGHTSTATUS_LANDED",   8);
define("FLIGHTSTATUS_TAXIIN",   9);
define("FLIGHTSTATUS_IN",      10);
define("FLIGHTSTATUS_END",     10);
define("FLIGHTSTATUS_UNKNOWN",  0);
define("FLIGHTSTATUS_CRASH",   99);

define("VSPEED_CRZ",   0);
define("VSPEED_GND",   0);  
define("VSPEED_CLB",   +1);
define("VSPEED_DES",   -1);
   
// ----------------------------------------------------------------------------
// Classes for ACARS_FLIGHT and ACARS_POSITION

Class acarsFlight
{
    var $flightID;
    var $userID;
    var $acarsID       = ACARS_XACARS;
    var $aircraft      = '';
    var $flightRot     = '';
    var $flightType    = 'IFR';
    var $flightPlan    = '';
    var $departure     = '';
    var $destination   = '';
    var $curPositionID = 0;
    
    function doInsert()
    {
        $sql  = 'INSERT INTO acars_flight(acarsFlightID, userID, acarsID, acType, callsign,
                    flightType, flightPlan, departure, destination) VALUES ( ' 
              . $this->flightID . ', '
              . $this->userID . ', '        
              . $this->acarsID . ', '
              . "'{$this->aircraft}', "
              . "'{$this->flightRot}', "
              . "'{$this->flightType}', "
              . "'{$this->flightPlan}', "
              . "'{$this->departure}', "
              . "'{$this->destination}');";

        if(!mysql_query($sql))
            die('0|SQL query failed (INSERT acars flight)   ' . $sql);
    }    
}    

Class acarsPosition
{
    var $msgtype    = '??';
    var $flightID   = 0;
    var $remoteTime = 0;
    var $posLat     = 0; 
    var $posLon     = 0;
    var $flightstat = FLIGHTSTATUS_UNKNOWN;
    var $vs         = VSPEED_GND;
    var $hdg = 0;
    var $alt = 0;
    var $gs  = 0;
    var $tas = 0;
    var $ias = 0;
    var $wnd = '00000';
    var $oat = 0;
    var $tat = 0;
    var $fob = 0;
    var $distFromDep    = 0;
    var $distTotal      = 0;    
    var $msgdata = '';
    
    function doInsert()
    {
        $sql  = 'INSERT INTO acars_position (acarsFlightID, acarsMsgID, systemTime, remoteTime, msgtype, flightStatus,  
                 latitude, longitude, heading, altitude, VS, GS, IAS, TAS, FOB, WND, OAT, TAT, distFromDep, 
                 distTotal, pauseMode, message) 
                 VALUES ( '                
             . $this->flightID . ", '', "               // FlightID, MsgID := ''
             . time() . ', '                            // Time Stamp
             . $this->remoteTime . ', '                 // Remote Time Stamp
             . "'{$this->msgtype}', "                   // Messagetype
             . $this->flightstat . ', '                 // Flightstatus
             . $this->posLat .', '. $this->posLon .', ' // Position
             . $this->hdg . ', '                        // Heading
             . $this->alt . ', '                        // Altitude
             . $this->vs . ', '                         // vertical speed
             . $this->gs  . ', ' . $this->ias . ', '    // GS und IAS
             . $this->tas . ', ' . $this->fob . ', '    // TAS, FUEL
             . "'{$this->wnd}', "                       // Winddaten
             . $this->oat . ', ' . $this->tat . ', '    // OAT und TAT
             . $this->distFromDep . ', '                // distFromDep
             . $this->distTotal . ', '                  // distTotal              
             . "0, \"{$this->msgdata}\");";  
        
        if(!mysql_query($sql))
            die('0|SQL query failed (INSERT acars position)   ' . $sql);

        if( $this->msgtype <> 'ZZ' )
        {
              // If the message wasn't a ENDFLIGHT message, the actual position is going to be written in acars_flight
            $newID = mysql_insert_id(); // maybe this function does not work on each system
            $query = "UPDATE acars_flight SET curPositionID = {$newID}, active = 1 WHERE acarsFlightID = {$this->flightID};";
            $result = mysql_query($query) or die("0|SQL query failed (UPDATE acars_flight)");
        }
                 
        return True;
    }
}

// ----------------------------------------------------------------------------
// Functions

function testPilotID($pid)
{
    $q_user = @mysql_query("SELECT id FROM user WHERE isactive=1 and username = '{$pid}' LIMIT 1");

    if(@mysql_num_rows($q_user) == 0)
        return -1;
    else
    {
        $user = @mysql_fetch_array($q_user);
        return $user['id'];
    }
}

function testUserLogin($pid, $pw)
{
    $client = new GuzzleHttp\Client();

    $res = $client->request('POST', VAOS_URL.'api/1_0/auth', [
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

Function lbs2kg( $lbs )
{
    return( $lbs / 2.204622915 );
}

function latDegDecMin2DecDeg( $dat )
{
    if( $dat=='' )
        return( 0 ); 

    $i = 0;
    $j = strpos($dat, ' ', 0);
    $k = max( strpos($dat, '.', $j), $j+1);

    $dec  = substr($dat,1,$j);                // degrees
    $dec += substr($dat, $j+1, $k-$j+2) / 60; // decimal minutes
    
    If( $dat{0} == 'S' )
        $dec = -$dec;
        
    return round($dec,4);
}

function lonDegDecMin2DecDeg( $dat )
{
    if( $dat=='' )
        return( 0 ); 
    
    $i = 0;
    $j = strpos($dat, ' ', 0);
    $k = max( strpos($dat, '.', $j), $j+1);

    $dec  = substr($dat,1,$j);                // degrees
    $dec += substr($dat, $j+1, $k-$j+2) / 60; // decimal minutes
    
    If( $dat{0} == 'W' )
        $dec = -$dec;

    return round($dec,4);
}

// ----------------------------------------------------------------------------
// Decode ACARS message

if (!isset($_REQUEST['DATA1'])) die('0|Invalid Data1');
if (!isset($_REQUEST['DATA2'])) die('0|Invalid Data2');

if (($version  = CheckXAcarsVersion($_REQUEST['DATA1'])) <= 0)
    die('0|Invalid XAcars Version');

$data2 = $_REQUEST['DATA2'];
if (!isset($_REQUEST['DATA3']))
    $data3 = '';
else
    $data3 = $_REQUEST['DATA3'];

if (!isset($_REQUEST['DATA4']))
    $data4 = '';
else
    $data4 = $_REQUEST['DATA4'];

$data2 = str_replace("\'", "''", $data2);
$data3 = str_replace("\'", "''", $data3);
$data4 = str_replace("\'", "''", $data4);

// Temp logging if you want to see the output in liveacars.log
// ----------------------------------------------------------------------------
/*
$logcontent = '?'.$_REQUEST['DATA1'].'&'.$_REQUEST['DATA2'].'&'.$_REQUEST['DATA3'].'&'.$_REQUEST['DATA4']."\n";

$logfilename = './liveacars.log';
$handle = fopen($logfilename, "a");
fwrite($handle, $logcontent);
fclose($handle);
*/


// ----------------------------------------------------------------------------
// Writing the date into the database

switch ($data2) {
    case "TEST":
        /* We cannot confirm nor deny that the user exists, so to not break anything, just return true. */
        echo '1';

        break;

    case "BEGINFLIGHT":
        /* Begin Flight logging on ACARS */
        $data = preg_split("\|", $data3);
        if( count($data) < 16 )
        {
            echo '0|Invalid login data ('. count($data) . ')';
            break;
        }
        
        $uid = testUserLogin($data[0], $data[17]);
        if( $uid == -1 )
        {
            echo '0|Login failed';
            break;
        }
        if( $data[6] <> '' )
        {
            //N52 23.1890 E13 31.0944
            $tmp = preg_split(' ', $data[6]);

            $lat = latDegDecMin2DecDeg(trim($tmp[0] . ' ' . $tmp[1]));
            $lon = lonDegDecMin2DecDeg(trim($tmp[2] . ' ' . $tmp[3]));
        }
        $fields = array(
            'pilotid' =>$uid,
            'flightnum' =>$data[2],
            'lat' =>$lat,
            'lng' =>$lon,
            'heading' =>$data[12],
            'alt' =>$data[7],
            'gs' =>$groundspeed,
            'route' =>$route,
            'distremain' =>$distanceremaining,
            'timeremaining' =>$timeremaining,
            'phasedetail' => "N/A",
            'client' =>'XACARS'
        );

        $acarsflight = new acarsFlight();
        $acarsflight->flightID = time();
        $acarsflight->userID = $uid;
        $acarsflight->acarsID  = ACARS_XACARS;
        
        // *** Origin and Destination Airports
        if (strlen($data[5]) != 0)
        {
            $plan = preg_split('~', $data[5]);
            $acarsflight->departure   = strtoupper($plan[0]);

            if (count($plan) > 1)
                $acarsflight->destination = strtoupper($plan[count($plan) - 1]);
        }

        $acarsflight->aircraft   = $data[3];       // AircraftRegistration
        $acarsflight->flightType = $data[15];      // flightType   
        $acarsflight->flightPlan = $data[5];       // flightPlan
        $acarsflight->flightRot  = $data[2];       // FlightNumber
        $acarsflight->doInsert();
                
        $acarspos = new acarsPosition();
        $acarspos->flightID     = $acarsflight->flightID;
        $acarspos->msgtype      = 'PR';
        if( $data[6] <> '' )
        {
            //N52 23.1890 E13 31.0944
            $tmp = preg_split(' ', $data[6]);
            
            $acarspos->posLat       = latDegDecMin2DecDeg(trim($tmp[0] . ' ' . $tmp[1]));
            $acarspos->posLon       = lonDegDecMin2DecDeg(trim($tmp[2] . ' ' . $tmp[3]));
        }
        $acarspos->flightstat   = FLIGHTSTATUS_BOARDING;
        $acarspos->waypoint     = $acarsflight->departure;
        $acarspos->hdg          = $data[12];
        $acarspos->alt          = $data[7];
        $acarspos->wnd          = $data[13];
        $acarspos->oat          = $data[14];
        $acarspos->tat          = $data[14];    //Just because GS:=0 at the moment
        $acarspos->fob          = lbs2kg($data[11]);
        $acarspos->distTotal    = $data[16];
        $acarspos->msgdata      = $data3;
        $acarspos->doInsert();

        print '1|'.$acarsflight->flightID;
        break;

    case "MESSAGE":
        $acarspos = new acarsPosition();

        $acarspos->flightID     = $data3;
        $acarspos->msgdata      = $data4;
                
        // Decode the message
        // Messagetype: PR=Position Report, AR=Alitude Report, WX=Weather,
        //              QA=OUT, QB=OFF, QC=ON, QD=IN, QM=Flight Statistics, CM=User Message        
        $j = strpos($data4, 'Msg Label: ', 0);
        if ($j != false) 
        {
            $j = $j + strlen('Msg Label: ');
            $acarspos->msgtype = substr($data4, $j, 2);
        }
        else
            die('ERROR - Wrong Message format: Msg Label is missing');

        // Remote Timestamp auslesen  [01/17/2006 06:58Z]
        $acarspos->remoteTime = strtotime( substr($data4, 1,17));
            
        $j = strpos($data4, 'Message:', 0);
        if ($j == false)
            die('ERROR - Wrong Message format: Messagebody is missing');
            
        $j = $j + 9; // strlen('Message:\n')
        $data = preg_split('/', substr($data4, $j));
        
        foreach( $data as $cmdStr)
        {           

            $k = strpos($cmdStr, ' '); 
            $cmd = strtoupper(substr($cmdStr, 0, $k));
            $cnt = trim(substr($cmdStr,$k));

            switch( $cmd )
            {
                case 'POS':
                    $tmp = preg_split(' ', $cnt);
                    $acarspos->posLat       = latDegDecMin2DecDeg(trim($tmp[0] . ' ' . $tmp[1]));
                    $acarspos->posLon       = lonDegDecMin2DecDeg(trim($tmp[2] . ' ' . $tmp[3]));
                    $i = strpos($cnt, '[');
                    if( $i > 0 )
                        $acarspos->waypoint = substr($cnt, $i+1, -1);
                break;

                case 'HDG':
                    if( is_numeric($cnt) == True)
                        $acarspos->hdg = $cnt;
                    break;
                
                case 'ALT':
                    $i = strpos($cnt, ' ');
                    if( $i == false )
                        $acarspos->alt = $cnt;
                    else
                    {
                        $acarspos->alt = substr($cnt, 0, $i);
                        if( strpos( $cnt, 'CLIMB' ) != False)
                            $acarspos->vs = VSPEED_CLB;
                        elseif( strpos( $cnt, 'DESC' ) != False)
                            $acarspos->vs = VSPEED_DES;
                        elseif( strpos( $cnt, 'LEVEL' ) != False)
                            $acarspos->vs = VSPEED_CRZ;
                    }
                    break;
 
                case 'IAS':
                    if( is_numeric($cnt) == True)
                        $acarspos->ias = $cnt;
                    break;
                    
                case 'TAS':
                    if( is_numeric($cnt) == True)
                        $acarspos->tas = $cnt;
                    break;

                case 'OAT':
                    if( is_numeric($cnt) == True)
                        $acarspos->oat = $cnt;
                    break;
                    
                case 'TAT':
                    if( is_numeric($cnt) == True)
                        $acarspos->tat = $cnt;
                    break;

                case 'FOB':
                    if( is_numeric($cnt) == True)
                        $acarspos->fob = lbs2kg($cnt);
                    break;
                    
                case 'WND':
                    if( is_numeric($cnt) == True)
                        $acarspos->wnd = $cnt;
                    break;

                case 'DST':
                    $i = strpos( $cnt, '-' );
                    $acarspos->distFromDep = substr($cnt,0, $i-1);
                    $acarspos->distTotal   = substr($cnt,$i+2);
                    break;

                case 'AP':
                    $acarspos->airport = $cnt;
                    break;
            }
        }
        
        switch( $acarspos->msgtype )
        {
            case 'QA':   // QA = OUT Message
                $acarspos->waypoint     = $acarspos->airport;  
                $acarspos->flightstat   = FLIGHTSTATUS_TAXIOUT;
                $acarspos->vs           = VSPEED_GND;
                $acarspos->doInsert();        
                print '1|';
            break;

            case 'QB':   // QB = OFF Message
                $query = "SELECT curWaypoint FROM acars_position WHERE acarsFlightID = {$acarspos->flightID} AND msgtype = 'QA'";                
                $result = @mysql_query($query) or die("0|SQL query failed");
                if (@mysql_num_rows($result) > 0)
                {
                    $temp = @mysql_fetch_array($result);
                    $acarspos->waypoint     = $temp[0];  
                }
                $acarspos->flightstat   = FLIGHTSTATUS_DEPARTURE;
                $acarspos->vs           = VSPEED_CLB;
                $acarspos->doInsert();        
                print '1|';
            break;

            case 'QC':   // QC = ON  Message
                $acarspos->waypoint     = $acarspos->airport;  
                $acarspos->flightstat   = FLIGHTSTATUS_LANDED;
                $acarspos->vs           = VSPEED_GND;
                $acarspos->doInsert();        
                print '1|';
            break;

            case 'QD':   // QD = IN Message
                $acarspos->flightstat   = FLIGHTSTATUS_IN;
                $acarspos->vs           = VSPEED_GND;
                $acarspos->doInsert();        
                print '1|';
            break;

            case 'PR':   // PR = Position Report Message
                $acarspos->doInsert();        
                print '1|';
            break;
                
            case 'AR':   // AR = Altitude Report Message
                if( $acarspos->vs == VSPEED_CRZ )
                    $acarspos->flightstat = FLIGHTSTATUS_CRUISE;
                elseif( $acarspos->vs == VSPEED_CLB )
                {
                    if( $acarspos->distFromDep > 30 ) 
                        // Inflight Climb (more then 30nm from departure - insert what you want)
                        $acarspos->flightstat = FLIGHTSTATUS_CRUISE;
                    else
                        // Initial Climb
                        $acarspos->flightstat = FLIGHTSTATUS_CLIMB;
                }
                elseif( $acarspos->vs == VSPEED_DES )
                    if( ($acarspos->distTotal - $acarspos->distFromDep) > 100    ) 
                        // Inflight Descend (more then 100Nm to destination - insert what you want)
                        $acarspos->flightstat = FLIGHTSTATUS_CRUISE;
                    else
                        // Initial Descend
                        $acarspos->flightstat = FLIGHTSTATUS_DESC;
                    
                $acarspos->doInsert();        
                print '1|';
            break;
        }
        break;

    case "PAUSEFLIGHT":
        print "1|";
        break;

    case "ENDFLIGHT":
        $acarspos = new acarsPosition();

        $acarspos->flightID     = $data3;
        $acarspos->flightstat   = FLIGHTSTATUS_END;
        $acarspos->msgtype      = 'ZZ';        
        $acarspos->doInsert();        

        print "1|";
        break;

    default:
        print "0|Wrong Function";
        break;

}