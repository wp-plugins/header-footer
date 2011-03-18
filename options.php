<?php
if (function_exists('load_plugin_textdomain')) {
    load_plugin_textdomain('header-footer', 'wp-content/plugins/header-footer');
}

function hefo_request($name, $default=null) {
    if (!isset($_REQUEST[$name])) return $default;
    return stripslashes_deep($_REQUEST[$name]);
}

function hefo_field_checkbox($name, $label='', $tips='', $attrs='') {
    global $options;
    echo '<th scope="row">';
    echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
    echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' .
        ($options[$name]!= null?'checked':'') . '/>';
    echo ' ' . $tips;
    echo '</td>';
}

function hefo_field_text($name, $label='', $tips='', $attrs='') {
    global $options;


    echo '<th scope="row">';
    echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
    echo '<td><input type="text" name="options[' . $name . ']" value="' .
        htmlspecialchars($options[$name]) . '" size="50"/>';
    echo '<br /> ' . $tips;
    echo '</td>';
}

function hefo_field_textarea($name, $label='', $tips='', $attrs='') {
    global $options;

    if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
    if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';

    echo '<th scope="row">';
    echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
    echo '<td><textarea style="width: 100%; height: 100px" wrap="off" name="options[' . $name . ']">' .
        htmlspecialchars($options[$name]) . '</textarea>';
    echo '<br /> ' . $tips;
    echo '</td>';
}	

if (isset($_POST['save'])) {
    if (!wp_verify_nonce($_POST['_wpnonce'], 'save')) die('Securety violated');
    $options = hefo_request('options');
    update_option('hefo', $options);
}
else {
    $options = get_option('hefo');
}
?>	
<script>
jQuery(document).ready(function(){
    jQuery("textarea").focus(function() {
        jQuery("textarea").css("height", "100px");
        jQuery(this).css("height", "400px");
    });
});
</script>
<div class="wrap">
    <h2>Header and Footer</h2>

<div style="border: 1px solid #6d6; border-radius: 5px; background-color: #efe; padding: 10px;">
<table cellpadding="0" cellspacing="0">
    <tr>
    <td valign="middle" align="left" width="110">
        <a href="http://www.satollo.net/donations" target="_blank"><img src="http://www.satollo.net/images/donate.gif"/></a>
    </td>
    <td valign="top" align="left">
        <strong>Your donation is like a diamond: it's forever.</strong> There is <a href="http://www.satollo.net/donations" target="_blank">something
        to read about donations</a>.
        <br />
        <small>My plugins:
        <a href="http://www.satollo.net/plugins/hyper-cache">Hyper Cache</a>,
        <a href="http://www.satollo.net/plugins/newsletter">Newsletter</a>,
        <a href="http://www.satollo.net/plugins/include-me">Include Me</a>,
        <a href="http://www.satollo.net/plugins/post-layout">Post Layout</a>,
        <a href="http://www.satollo.net/plugins/postacards">Postcards</a>,
        <a href="http://www.satollo.net/plugins/comment-notifier">Comment Notifier</a>,
        <a href="http://www.satollo.net/plugins/comment-image">Comment Image</a>.</small>
    </td>
</tr>
</table>
</div>

    <p>You can read the <a href="http://www.satollo.net/plugins/header-footer">Header and Footer plugin official page</a> for more information. PHP is allowed
    on textareas below.</p>
    
    <form method="post">
        <?php wp_nonce_field('save') ?>

        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('head_home', __('head_home', 'header-footer'), __('head hint', 'header-footer'), 'rows="4"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('head', __('head', 'header-footer'), __('head hint', 'header-footer'), 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_checkbox('og_image', 'Facebook Open Graph Image', 'Adds the Facebook Open Graph metatag with a reference to the first post image'); ?></tr>
            <tr valign="top"><?php hefo_field_text('og_image_default', 'Facebook Open Graph Default Image', 'If no image can be extracted, use this image URL'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('footer', __('footer', 'header-footer'), __('footer hint', 'header-footer'), 'rows="10"'); ?></tr>
        </table>

        <p class="submit"><input type="submit" name="save" value="<?php _e('save', 'header-footer'); ?>"></p>

    </form>
</div>
