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
  echo '<tr><td class="label">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></td>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' . 
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function hefo_field_textarea($name, $label='', $tips='', $attrs='')
{
  global $options;
  
  if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
  if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';
  
  echo '<tr><td class="label">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></td>';
  echo '<td><textarea wrap="off" ' . $attrs . ' name="options[' . $name . ']">' . 
    htmlspecialchars($options[$name]) . '</textarea>';
  echo '<br /> ' . $tips;
  echo '</td></tr>';
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
<style type="text/css">
td.label
{
  width: 150px;
  vertical-align: top;
  font-weight: bold;
  text-align: right;
}
td textarea {
    font-family: monospace;
    font-size: 11px;
}
</style>
<div class="wrap">

<form method="post">

<h2>Blog Control</h2>

<h3>Header</h3>
<table>
<? hefo_field_textarea('head', 'Head html code', '(eg. the verification metatag of Google or MSN Webmaster Tools)', 'rows="10"'); ?>
</table>

<h3>Footer</h3>
<p>This text code will be added to the footer of the blog.</p>
<table>
<? hefo_field_textarea('footer', 'Footer code', '(eg. the Google Analytics code or the MyBlogLog tracking code)', 'rows="10"'); ?>
</table>

<p><input type="submit" name="save" value="Save"></p>

</form>
</div>
