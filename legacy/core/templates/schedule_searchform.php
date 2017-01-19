<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Search Schedules</h3>
<form id="form" action="<?php echo url('/schedules/view');?>" method="post">

<div id="tabcontainer">
	<ul>

		<li><a href="#depapttab"><span>By Departure Airport</span></a></li>
		<li><a href="#arrapttab"><span>By Arrival Airport</span></a></li>
		<li><a href="#airlinetab"><span>By Airline</span></a></li>
                <li><a href="#aircrafttab"><span>By Aircraft Type</span></a></li>
		<li><a href="#distance"><span>By Distance</span></a></li>
	</ul>
	<div id="depapttab">
		<p>Select your departure airport:</p>
		<select id="depicao" name="depicao">
		<option value="">Select All</option>
		<?php
		if(!$depairports) $depairports = array();

		foreach($depairports as $airport) {
			echo '<option value="'.$airport->icao.'">'.$airport->icao
					.' ('.$airport->name.')</option>';
		}
		?>

		</select>
		<input type="submit" name="submit" value="Find Flights" />
	</div>
	<div id="arrapttab">
		<p>Select your arrival airport:</p>
		<select id="arricao" name="arricao">
			<option value="">Select All</option>
		<?php
		if(!$depairports) $depairports = array();

		foreach($depairports as $airport) {
			echo '<option value="'.$airport->icao.'">'.$airport->icao
					.' ('.$airport->name.')</option>';
		}
		?>

		</select>
		<input type="submit" name="submit" value="Find Flights" />
	</div>
	<div id="aircrafttab">
		<p>Select aircraft:</p>
		<select id="equipment" name="equipment">
			<option value="">Select aircraft</option>
		<?php
		if(!$aircraft_list) {
            $aircraft_list = array();
		}

		foreach($aircraft_list as $aircraft) {
			echo '<option value="'.$aircraft->name.'">'.$aircraft->name.'</option>';
		}

		?>
		</select>
		<input type="submit" name="submit" value="Find Flights" />
	</div>
    <div id="airlinetab">
        <p>Select An Airline</p>
        <select id="airlines" name="airlines">
        <option value="">Select Airline</option>
        <?php
        if(!$airlines) $airlines = array();
        foreach ($airlines as $airline) {
            echo '<option value="'.$airline->code.'">'.$airline->name.'</option>';
        }
        ?>

        </select>

        <input type="submit" name="submit" value="Find Flights" />
    </div>
	<div id="distance">
		<p>Select Distance:</p>
		<select id="type" name="type">
			<option value="greater">Greater Than</option>
			<option value="less">Less Than</option>
		</select>
		<input type="text" name="distance" value="" />
		<input type="submit" name="submit" value="Find Flights" />
	</div>
</div>

<p>
<input type="hidden" name="action" value="findflight" />
</p>
</form>
<script type="text/javascript">

</script>
<hr>