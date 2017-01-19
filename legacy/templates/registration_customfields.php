<?php if(!defined('IN_PHPVMS') && IN_PHPVMS !== true) { die(); } ?>
<?php
/* Show any extra fields
 */
if($field_list) {
	foreach($field_list as $field) {
?>
	<dt><?php echo $field->title; ?></dt>
	<dd>
	<?php
		if($field->type == 'dropdown') {
			echo "<select name=\"{$field->fieldname}\">";
			$values = explode(',', $field->value);
		
			if(is_array($values))
			{						
				foreach($values as $val)
				{
					$val = trim($val);
					echo "<option value=\"{$val}\">{$val}</option>";
				}
			}
			
			echo '</select>';
		} elseif($field->type == 'textarea') {
			echo '<textarea name="'.$field->fieldname.'" class="customfield_textarea"></textarea>';
		} else { ?>
            <input type="text" name="<?php echo $field->fieldname; ?>" value="<?php echo Vars::POST($field->fieldname);?>" /></dd>
<?php	}
	}
}
?>