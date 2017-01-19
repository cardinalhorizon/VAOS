<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<h3>Add Comment to PIREP</h3>
<form action="<?php echo url('/pireps/viewpireps');?>" method="post">
<?php
// List all of the custom PIREP fields
if(!$pirepfields)
{
	echo '<p>There are no custom fields to edit for PIREPs!</p>';
	return;
}

foreach($pirepfields as $field)
{
?>
	<dt><?php echo $field->title ?></dt>
	<dd>
	<?php
	
	// Determine field by the type
	$value = PIREPData::GetFieldValue($field->fieldid, $pirep->pirepid);

	if($field->type == '' || $field->type == 'text')
	{
	?>
		<input type="text" name="<?php echo $field->name ?>" value="<?php echo $value ?>" />
	<?php
	} 
	elseif($field->type == 'textarea')
	{
		echo '<textarea name="'.$field->name.'">'.$value.'</textarea>';
	}
	elseif($field->type == 'dropdown')
	{
		$values = explode(',', $field->options);
		
		echo '<select name="'.$field->name.'">';
		foreach($values as $fvalue)
		{
			if($value == $fvalue)
			{
				$sel = 'selected="selected"';
			}
			else	
			{
				$sel = '';
			}
			
			$value = trim($fvalue);
			echo '<option value="'.$fvalue.'" '.$sel.'>'.$fvalue.'</option>';
		}
		echo '</select>';		
	}
	?>
	
	</dd>
	<?php
}
?>

<br />
<input type="hidden" name="action" value="editpirep" />
<input type="hidden" name="pirepid" value="<?php echo $pirep->pirepid?>" />
<input type="submit" name="submit" value="Save fields" />
</form>