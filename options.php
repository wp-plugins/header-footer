<?
function hefo_request($name, $default=null) 
{
	if (!isset($_REQUEST[$name])) return $default;
	if (get_magic_quotes_gpc()) return hefo_stripslashes($_REQUEST[$name]);
	else return $_REQUEST[$name];
}

function hefo_stripslashes($value)
{
	$value = is_array($value) ? array_map('hefo_stripslashes', $value) : stripslashes($value);
	return $value;
}
	
function hefo_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' . 
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td>';
}

function hefo_field_textarea($name, $label='', $tips='', $attrs='')
{
  global $options;
  
  if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
  if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';
  
  echo '<th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><textarea wrap="off" ' . $attrs . ' name="options[' . $name . ']">' . 
    htmlspecialchars($options[$name]) . '</textarea>';
  echo '<br /> ' . $tips;
  echo '</td>';
}	

if (isset($_POST['save']))
{
    $options = hefo_request('options');
    update_option('hefo', $options);
}
else 
{
    $options = get_option('hefo');
}
?>	

<div class="wrap">

<form method="post">

<h2>Header-Footer</h2>

<table class="form-table">
<tr valign="top"><?php hefo_field_textarea('head', 'Head html code', '(eg. the verification metatag of Google or MSN Webmaster Tools)', 'rows="10"'); ?></tr>
<tr valign="top"><?php hefo_field_textarea('footer', 'Footer code', '(eg. the Google Analytics code or the MyBlogLog tracking code)', 'rows="10"'); ?></tr>
</table>

<p class="submit"><input type="submit" name="save" value="Save"></p>

</form>
</div>
