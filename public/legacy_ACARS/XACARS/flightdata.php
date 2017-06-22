<?php
// functions.php is required to connect to the database as usual
require("../boot.php");

$client = new GuzzleHttp\Client();

function returnData($result)
{
    echo "1|flightplan\n";
    echo $result['depapt']['icao']."\n";
    echo $result['arrapt']['icao']."\n";
    echo $result['arrapt']['icao']."\n";
    echo "PLEASE ENTER ROUTE\n";
    echo $result['pax']."\n";
    echo "0\n";
    echo "IFR\n";
    echo $result['aircraft']['icao']."\n";
}
// If Bid is placed, get the first for the user

    $res = $client->request('GET', VAOS_URL . 'api/v1/bids', [
        'query' => [
            'format' => 'xacars',
            'username' => $_REQUEST['DATA4'],
            'flightnum' => $_REQUEST['DATA2']
        ]
    ])->getBody();
    $jdec = json_decode($res, true);
    if ($jdec['status'] == 404)
        echo "0|No Bids Found In System";
    else
    	returnData($jdec['bid']);
/*
$query = mysql_query("SEECT * FROM `flights` where flightnumber='".$_REQUEST['DATA2']."'");
$num_result = mysql_num_rows($query);
if ($num_result > 0)
{
	for ($i=0;$i<$num_result;$i++)
	{
		$result = mysql_fetch_array($query);
		echo "1|flightplan\n";
		echo $result['departure']."\n";
		echo $result['destination']."\n";
		echo $result['alternate']."\n";
		echo $result['route']."\n";
		echo $result['pax']."\n";
		echo $result['cargo']."\n";
		echo $result['rules']."\n";
		echo $result['aircraft']."\n";
	}
}
else
{
	echo "0|Flightnumber not found";
}*/
?>
