<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
if(!$lastbids)
{
	echo 'No bids have been made';
	return;
   
}

foreach($lastbids as $lastbid);
{
?>
<style type="text/css">
<!--
.style2 {
	font-family: Arial;
	font-size: 10px;
}
-->
</style>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td><p class="style2"><?php echo $lastbid->bidid . ' - ' . $lastbid->code.$lastbid->flightnum.' - '.$lastbid->depicao.' to '.$lastbid->arricao?></a>
        </p>
        <?php
}
?></p></td>
  </tr>
</table>
