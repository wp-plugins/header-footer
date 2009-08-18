<?php
if (function_exists('load_plugin_textdomain')) {
    load_plugin_textdomain('header-footer', 'wp-content/plugins/header-footer');
}
function hefo_request($name, $default=null) 
{
	if (!isset($_REQUEST[$name])) return $default;
	return stripslashes_deep($_REQUEST[$name]);
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
    if (!wp_verify_nonce($_POST['_wpnonce'], 'save')) die('Securety violated');
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
<?php wp_nonce_field('save') ?>
<h2>Header and Footer</h2>

        <p>To have more information about Header and Footer go to the
        <a href="http://www.satollo.net/plugins/header-footer">official plugin page</a>
        or write me to info@satollo.net.</p>

        <p>Donations are welcome,
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2545483">click here.</a>
        </p>

        <p>
            My other plugins:
            <a href="http://www.satollo.net/plugins/post-layout">Post Layout</a>,
            <a href="http://www.satollo.net/plugins/post-layout-pro">Post Layout Pro</a>,
            <a href="http://www.satollo.net/plugins/other-posts">Other Posts</a>,
            <a href="http://www.satollo.net/plugins/protector">Protector</a>
            <a href="http://www.satollo.net/plugins/newsletter">Newsletter</a>.
        </p>

<table class="form-table">
<tr valign="top"><?php hefo_field_textarea('head_home', __('head_home', 'header-footer'), __('head hint', 'header-footer'), 'rows="4"'); ?></tr>
<tr valign="top"><?php hefo_field_textarea('head', __('head', 'header-footer'), __('head hint', 'header-footer'), 'rows="10"'); ?></tr>
<tr valign="top"><?php hefo_field_textarea('footer', __('footer', 'header-footer'), __('footer hint', 'header-footer'), 'rows="10"'); ?></tr>
</table>

<p class="submit"><input type="submit" name="save" value="<?php _e('save', 'header-footer'); ?>"></p>

</form>
</div>
