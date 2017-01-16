<?php
/**
 * Codon PHP Framework
 *	www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon_core
 */

class CodonForm
{
	public $form;
	public $options;
	public $formname;
	public $method;
	public $url;

	public function __construct($formname='', $url='', $method="post")
	{
		$this->form = '';
		$this->formname = $formname;
		$this->method = $method;
		$this->url = $url;
	}
	
	public function SetOptions($options)
	{
		$this->options = $options;
	}

	public function StartForm($options='')
	{
		$this->SetOptions($options);
		
		if($this->options['ajax'] == true)
		{
			$this->StartFormAJAX($this->options['updatediv'], $this->options['callback']);
			return;
		}
		
		$url = ($this->options['url']=='') ? $this->url : $this->options['url'];
		$method = ($this->options['method']=='') ? $this->method : $this->options['method'];
		$formname = ($this->options['name']=='') ? $this->formname : $this->options['name'];
		$url = (substr($url, 0, 1) != '/' ? '/'.$url : $url);
		
		$url = SITE_URL.'/index.php'.$url;
			
		echo '<form action="'.$url.'" method="'.$method.'" name="'.$name.'" '.$attrib.'>';
	}

	public function StartFormAJAX($update_div='', $callback='')
	{
		$url = ($this->options['url']=='') ? $this->url : $this->options['url'];
		$method = ($this->options['method']=='') ? $this->url : $this->options['method'];
		$formname = ($this->options['name']=='') ? $this->formname : $this->options['name'];
		$url = (substr($url, 0, 1) != '/' ? '/'.$url : $url);
	
		$url = SITE_URL.'/action.php'.$url;
			
		echo '<form action="'.$url.'" method="'.$method.'" class="codonform" name="'.$name.'"
					id="'.$id.'" divupdate="'.$update_div.'" callback="'.$callback.'" '.$attrib.'>';
	}
	
	/**
	 * Add a textbox
	 *
	 * @param string $name Name of the textbox
	 * @param string $value Value of the textbox
	 * @param string $attrib Any extra attributes (i.e. class="textboxclass")
	 */
	public function Textbox($label='', $name, $value='', $attrib='')
	{
		if($label != '')
			echo "<label for=\"$name\">$label</label>";
		
		echo '<input type="text" name="'.$name.'" value="'.$value.'" '.$attrib.' />';
	}
	
	/**
	 * Add a checkbox, with the label
	 *
	 * @param string $label Label for the checkbox
	 * @param string $name Name of the checkbox
	 * @param string $value Value of the checkbox
	 * @param bool $checked Whether it's checked by default or not
	 * @param string $attrib Any extra attributes (i.e. class="textboxclass")
	 */
	public function Checkbox($label='', $name, $value, $checked, $attrib='')
	{
		$checked = ($checked == true ? 'checked="checked"' : '');
					
		if($label != '')
			echo "<label for=\"$name\">$label</label>";
			
		echo "<input type=\"checkbox\" id=\"$name\" name=\"$name\" value=\"$value\" $checked $attrib />";
	}
	
	/**
	 * Add a radio button
	 *
	 * @param string $label Label for the checkbox
	 * @param string $name Name of the checkbox
	 * @param string $value Value of the checkbox
	 * @param bool $checked Whether it's checked by default or not
	 * @param string $attrib Any extra attributes (i.e. class="textboxclass")
	 */
	public function Radio($label='', $name, $value, $checked, $attrib='')
	{
		$checked = ($checked == true ? 'checked="checked"' : '');
			
		if($label != '')
			echo "<label for=\"$name\">$label</label>";
			
		echo "<input type=\"radio\" id=\"$name\" name=\"$name\" value=\"$value\" $checked $attrib />";
	}
	
	/**
	 * Add a dropdown box with options
	 * @example Dropdown('States', array('Name'=>'Value', 'New York'=>'NY'));
	 *
	 * @param string $label Optional label
	 * @param string $name Name of the select box
	 * @param array $options Array of key/value name/value of selectbox
	 * @return unknown
	 */
	public function Dropdown($label='', $name, $options)
	{
		if(!is_array($options)) return false;
		
		if($label != '')
			echo "<label for=\"$name\">$label</label>";
			
		echo '<select name="'.$name.'">';
		foreach($options as $name=>$value)
		{
			echo '<option value="'.$value.'">'.$name.'</option>';
		}
		echo '</select>';
	}
	
	public function Textarea($label='', $name, $value='', $height='', $width='', $attribs='')
	{
		if($width!='')	$attribs = 'width="'.$width.'" '.$attribs;
		if($height!='') $attribs = 'height="'.$height.'" '.$attribs;
		
		if($label != '')
			echo "<label for=\"$name\">$label</label>";
			
		echo '<textarea '.$attribs.'>'.$value.'</textarea>';
	}
	
	public function File($label='', $name, $value='')
	{
		if($label != '')
			echo "<label for=\"$name\">$label</label>";
			
		echo '<input type="file" name="'.$name.'" value="'.$value.'" />';
	}
	
	public function Hidden($name, $value)
	{
		echo '<input name="'.$name.'" value="'.$value.'" />';
	}
	
	public function Button($name, $text, $callback='', $attrib='')
	{
		echo '<input type="button" name="'.$name.'" value="'.$text.'"
				onClick="'.$callback.';" '.$attrib.' />';
	}
	
	public function Submit($name, $text, $callback='', $attrib='')
	{
		echo '<input type="submit" name="'.$name.'" value="'.$text.'"
					class="codonform" onClick="'.$callback.';" />';
	}
	
	public function ShowForm()
	{
		echo '</form>';
		
		if($this->options['ajax'] == true && $this->options['updatediv']!='')
		{
		?>
		
		<script type="text/javascript">
		$(document).ready(function(){
		$(".codonform").ajaxForm({
			target: "<?php echo $this->options['updatediv'] ?>"
			<?php
			if($callback != ''){ ?>,
			success: function(){ <?php echo $this->options['callback']; ?>();
			<?php
				if($this->options['closemodal'] == true)
				{
					echo "$('#jqmdialog').jqmHide();";
				}
			?>
			}
			<?php } ?>
		})});
		</script>
		<?php
		}
	}
}
?>