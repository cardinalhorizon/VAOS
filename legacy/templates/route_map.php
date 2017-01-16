<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Route Map</h3>
<div class="mapcenter" align="center">
	<div id="routemap" style="width:<?php echo  Config::Get('MAP_WIDTH');?>; height: <?php echo Config::Get('MAP_HEIGHT')?>"></div>
</div>
<?php
/**
 * 
 * This is the new Google Maps v3 code. Be careful of changing
 * things here, only do something if you know what you're doing.
 * 	          
 * These are some options for the map, you can change here.
 * 
 * This map is used for schedules and PIREPS
 * 
 * By default, the zoom level and center are ignored, and the map 
 * will try to fit the all the flights in. If you want to manually set
 * the zoom level and center, set "autozoom" to false.
 * 
 * If you want to adjust the size of the map - Look at the above
 * "routemap" div with the CSS width/height parameters. You can 
 * easily adjust it from there.
 * 
 * And for reference, you want to tinker:
 * http://code.google.com/apis/maps/documentation/v3/basics.html
 */
 
if(isset($pirep))
	$mapdata = $pirep;
if(isset($schedule))
	$mapdata = $schedule;
?>
<?php
/*	This is a small template for information about a navpoint popup 
	
	Variables available:
	
	<%=nav.title%>
	<%=nav.name%>
	<%=nav.freq%>
	<%=nav.lat%>
	<%=nav.lng%>
	<%=nav.type%>	2=NDB 3=VOR 4=DME 5=FIX 6=TRACK
 */
?>
<script type="text/html" id="navpoint_bubble">
	<span style="font-size: 10px; text-align:left; width: 100%" align="left">
	<strong>Name: </strong><%=nav.title%> (<%=nav.name%>)<br />
	<strong>Type: </strong>
	<?php	/* Show the type of point */ ?>
	<% if(nav.type == 2) { %> NDB <% } %>
	<% if(nav.type == 3) { %> VOR <% } %>
	<% if(nav.type == 4) { %> DME <% } %>
	<% if(nav.type == 5) { %> FIX <% } %>
	<% if(nav.type == 6) { %> TRACK <% } %>
	<br />
	<?php	/* Only show frequency if it's not a 0*/ ?>
	<% if(nav.freq != 0) { %>
	<strong>Frequency: </strong><%=nav.freq%>
	<% } %>
	</span>
</script>

<?php
/*	Below here is all the javascript for the map. Be careful of what you
	modify!! */
?>
<script type="text/javascript">
var options = {
	mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("routemap"), options);
var dep_location = new google.maps.LatLng(<?php echo $mapdata->deplat?>,<?php echo $mapdata->deplng;?>);
var arr_location = new google.maps.LatLng(<?php echo $mapdata->arrlat?>,<?php echo $mapdata->arrlng;?>);

var bounds = new google.maps.LatLngBounds();                                                                                                     
bounds.extend(dep_location);
bounds.extend(arr_location);

var depMarker = new google.maps.Marker({
	position: dep_location,
	map: map,
	icon: depicon,
	title: "<?php echo $mapdata->depname;?>"
});
<?php
/* Populate the route */
if(is_array($mapdata->route_details))
{
	$list = array();
	
	foreach($mapdata->route_details as $route)
	{
		if($route->type == NAV_VOR)
			$icon = fileurl('/lib/images/icon_vor.png');
		else
			$icon = fileurl('/lib/images/icon_fix.png');
		
		/*	Build info array for the bubble */
		?>
		var v<?php echo $route->name?>_info = {
			freq: "<?php echo $route->freq ?>",
			name: "<?php echo $route->name ?>",
			title: "<?php echo $route->title ?>",
			type: "<?php echo $route->type ?>",
			lat: "<?php echo $route->lat ?>",
			lng: "<?php echo $route->lng ?>"
		};
		
		var v<?php echo $route->name?>_navpoint_info = tmpl("navpoint_bubble", {nav: v<?php echo $route->name?>_info});
		var v<?php echo $route->name?>_coords = new google.maps.LatLng(<?php echo $route->lat?>, <?php echo $route->lng?>);
		var v<?php echo $route->name?>_marker = new google.maps.Marker({
			position: v<?php echo $route->name?>_coords,
			map: map,
			icon: "<?php echo $icon; ?>",
			title: "<?php echo $route->title; ?>",
			infowindow_content: v<?php echo $route->name?>_navpoint_info
		})
		
		bounds.extend(v<?php echo $route->name?>_coords);
		
		google.maps.event.addListener(v<?php echo $route->name?>_marker, 'click', function() 
		{
			info_window = new google.maps.InfoWindow({ 
				content: this.infowindow_content,
				position: this.position
			});
			
			info_window.open(map, this);
		});
		
		<?php
			
		// For the polyline
		$list[] = "v{$route->name}_coords";
	}
}
?>
var arrMarker = new google.maps.Marker({
	position: arr_location,
	map: map,
	icon: arricon,
	title: "<?php echo $mapdata->arrname;?>"
});

var flightPath = new google.maps.Polyline({
	path: [dep_location, <?php if(count($list) > 0) { echo implode(',', $list).','; }?> arr_location],
	strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2
}).setMap(map);

// Resize the view to fit it all in
map.fitBounds(bounds); 
</script>