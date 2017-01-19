<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
/*
 * DO NOT EDIT THIS TEMPLATE UNLESS:
 *   1. YOU HAVE ALOT OF TIME
 *   2. YOU DON'T MIND LOSING SOME HAIR
 *   3. YOU HAVE BIG BALLS MADE OF STEEL
 *
 *	It can cause incontinence
 *
 *	YOU HAVE BEEN WARNED!!!
 */
?><?php Template::Show('finance_header.tpl'); ?>
<h3><?php echo $title?></h3>
<?php

	$total = 0;
	
	$profit = array();
	$pilot_pay = array();
	$revenue = array();	
	$expenses = array();
	$flightexpenses = array();
	$fuelexpenses = array();
	$months=array();
	
?>
<table width="670px" align="center" class="balancesheet" cellpadding="0" cellspacing="0">

	<tr class="balancesheet_header" style="text-align: center">
		<td align="left">Month</td>
		<td align="center">Flights</td>
		<td align="left">Revenue</td>
		<td align="center" nowrap>Pilot Pay</td>
		<td align="left">Expenses</td>
		<td align="left">Fuel</td>
		<td align="center">Total</td>
	</tr>
	
<?php 
echo '<pre>';
//print_r($allfinances);
echo '</pre>';
foreach ($allfinances as $month)
{
?>
	<tr>
		<td align="right">
			<?php 
			echo $month->ym;
			?>
		</td>
		<td align="center">
		<?php 
			echo $month->total;
		?>
		</td>
		<td align="right" nowrap>
			<?php 
			echo FinanceData::FormatMoney($month->gross);
			?>
		</td>
		<td align="right" nowrap>
			<?php 
			echo FinanceData::FormatMoney(-1 * $month->pilotpay);
			?>
		</td>
		<td align="right" nowrap>
			<?php 
			echo FinanceData::FormatMoney((-1) * $month->expenses_total);
			?>
		</td>
		<td align="right" nowrap>
			<?php 
			echo FinanceData::FormatMoney((-1) * $month->fuelprice);
			?>
		</td>
		<td align="right" nowrap>
			<?php 
			$profit[] = round($month->revenue, 2);
			$total += $month->revenue;
			echo FinanceData::FormatMoney($month->revenue);
			?>
		</td>
	</tr>
<?php
}
?>
<tr class="balancesheet_header" style="border-bottom: 1px dotted">
	<td align="" colspan="8" style="padding: 1px;"></td>
</tr>
	
<tr>
	<td align="right" colspan="6"><strong>Total:</strong></td>
	<td align="right" colspan="2"><strong><?php echo FinanceData::FormatMoney($total);?></strong></td>
</tr>
	
</table>

<h3>Breakdown</h3>
<div align="center">
<?php
/*
	Added in 2.0!
*/
$chart_width = '800';
$chart_height = '500';

/* Don't need to change anything below this here */
?>
<div align="center" style="width: 100%;">
	<div align="center" id="summary_chart"></div>
</div>

<script type="text/javascript" src="<?php echo fileurl('/lib/js/ofc/js/json/json2.js')?>"></script>
<script type="text/javascript" src="<?php echo fileurl('/lib/js/ofc/js/swfobject.js')?>"></script>
<script type="text/javascript">
swfobject.embedSWF("<?php echo fileurl('/lib/js/ofc/open-flash-chart.swf');?>", 
	"summary_chart", "<?php echo $chart_width;?>", "<?php echo $chart_height;?>", 
	"9.0.0", "expressInstall.swf", 
	{"data-file":"<?php echo actionurl('/finances/viewmonthchart?'.$_SERVER['QUERY_STRING']); ?>"});
</script>
<?php
/* End added in 2.0
*/
?>
</div>