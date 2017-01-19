<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
/* This template is for FSFK log for the flight critique.
	Be careful, it's a bit fragile
	*/
?>
<div class="fsfk_section_title">Flight Critique</div>
<table class="fsfk_flightcritique">
<?php
// two columns
$tot = count($matches[0]);
for($i=0; $i<$tot; $i++)
{
	if($i%2==0)
		$class = 'even';
	else
		$class = 'odd';
	
	$criteria = $matches[1][$i];
	$score = $matches[2][$i];
	
	/* You can do something like:
	
	if($criteria == 'Landing Rating')
	{
		// Some special output or styling
	} 
	*/
	
	echo"<tr class=\"{$class}\">
			<td>{$criteria}</td>
			<td>{$score}</td>
		 </tr>";
}
?>
</table>
<br />