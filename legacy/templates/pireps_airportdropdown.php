<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
if (!$airport_list) {
    echo 'There are no routes for this airline<br />';
    return;
}
?>
<select id="<?php echo $name; ?>" name="<?php echo $name;?>">
    <option value="">Select an airport</option>

<?php
foreach ($airport_list as $airport) {
    echo '<option value="' . $airport->icao . '">' . $airport->icao . ' - ' . $airport->name . '</option>';
}
?>

</select>