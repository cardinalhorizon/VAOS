<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
/**
 * 
 * 
 */
 
?>
<h3>My Routes Map</h3>
<div class="mapcenter" align="center">
	<div id="routemap" style="width: 800px; height: 600px;"></div>
</div>

<script type="text/javascript">
var options = {
	mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("routemap"), options);
var flightMarkers = [];

<?php 
$shown = array();
foreach($pirep_list as $pirep) {	
	// Dont show repeated routes		
	if(in_array($pirep->code.$pirep->flightnum, $shown))
		continue;
	else
		$shown[] = $pirep->code.$pirep->flightnum;
	
	if(empty($pirep->arrlat) || empty($pirep->arrlng)
		|| empty($pirep->deplat) || empty($pirep->deplng))
	{
		continue;
	}
?>
	dep_location = new google.maps.LatLng(<?php echo $pirep->deplat?>, <?php echo $pirep->deplng?>);
	arr_location = new google.maps.LatLng(<?php echo $pirep->arrlat?>, <?php echo $pirep->arrlng?>);

	flightMarkers[flightMarkers.length] = new google.maps.Marker({
		position: dep_location,
		map: map,
		title: "<?php echo "$pirep->depname ($pirep->depicao)";?>"
	});

	flightMarkers[flightMarkers.length] = new google.maps.Marker({
		position: arr_location,
		map: map,
		title: "<?php echo "$pirep->arrname ($pirep->arricao)";?>"
	});

	var flightPath = new google.maps.Polyline({
		path: [dep_location, arr_location],
		strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2
	}).setMap(map);
<?php
}
?>

if(flightMarkers.length > 0)
{
	var bounds = new google.maps.LatLngBounds();
	for(var i = 0; i < flightMarkers.length; i++) {
		bounds.extend(flightMarkers[i].position);
	}
}

map.fitBounds(bounds); 
</script>