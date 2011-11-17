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
var tabs;
jQuery(document).ready(function(){
    jQuery("textarea").focus(function() {
        jQuery("textarea").css("height", "100px");
        jQuery(this).css("height", "400px");
    });

    jQuery('#upload-image').click(function() {
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {
        var imgurl = jQuery('img',html).attr('src');
        jQuery('#og_image_default').val(imgurl);
        tb_remove();
    }

    jQuery(function() {
        tabs = jQuery("#tabs").tabs({selected: <?php echo (int)$options['tab']; ?>});
    });
});
</script>
<div class="wrap">
    <h2>Header and Footer</h2>

    <iframe src="http://frames.satollo.net/header-footer.php" frameborder="0" width="100%" height="80" style="border: 0"></iframe>

    <p>Detailed documentation and FAQs can be found online on <a href="http://www.satollo.net/plugins/header-footer"><strong>Header and Footer plugin official page</strong></a>.</p>
    <p>PHP is allowed on textareas below. If you use bbPress, read the official page.</p>
    
    <form method="post" action="" onsubmit="this.elements['options[tab]'].value=tabs.tabs('option', 'selected'); return true;">
        <?php wp_nonce_field('save') ?>
        <input type="hidden" name="options[tab]" value="<?php echo (int)$options['tab']; ?>"/>

   
    <div id="tabs">
    <ul>
        <li><a href="#tabs-1">Page head and footer</a></li>
        <li><a href="#tabs-2">Post content</a></li>
        <li><a href="#tabs-3">Page content</a></li>
        <li><a href="#tabs-4">Facebook</a></li>
    </ul>
        
        <div id="tabs-1">
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('head_home', __('head_home', 'header-footer'), __('head hint', 'header-footer'), 'rows="4"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('head', __('head', 'header-footer'), __('head hint', 'header-footer'), 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('footer', __('footer', 'header-footer'), __('footer hint', 'header-footer'), 'rows="10"'); ?></tr>
        </table>
        </div>
        


        <div id="tabs-2">
        <!--<h3>Posts and pages</h3>-->
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('before', 'Code to be inserted before each post', '', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('after', 'Code to be inserted after each post', '', 'rows="10"'); ?></tr>
        </table>
        </div>
        
        <div id="tabs-3">
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('page_before', 'Code to be inserted before each page', '', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('page_after', 'Code to be inserted after each page', '', 'rows="10"'); ?></tr>
        </table>
        </div>
        
        <div id="tabs-4">
        <!--<h3>Facebook</h3>-->
        <table class="form-table">
            <tr valign="top"><?php hefo_field_text('og_type', 'Facebook page type for the generic web page', 'Usually "article" is the right choice, if empty will be skipped'); ?></tr>
            <tr valign="top"><?php hefo_field_text('og_type_home', 'Facebook page type for the home', 'Usually "blog" is a good choice, if empty will be used the generic type'); ?></tr>
            <tr valign="top"><?php hefo_field_checkbox('og_image', 'Facebook Open Graph Image', 'Adds the Facebook Open Graph metatag with a reference to the first post image'); ?></tr>
            <tr valign="top">
                <th scope="row">
                    <label for="options[' . $name . ']">Facebook Open Graph Default Image</label></th>
                    <td>
                        <input type="text" id="og_image_default" name="options[og_image_default]" value="<?php echo htmlspecialchars($options['og_image_default']); ?>" size="50"/>
                        <input type="button" id="upload-image" value="Select/Upload an image"/>
                        <br />
                        If no image can be extracted from a post, use this image URL.
                    </td>
            </tr>
        </table>
        </div>
    </div>
    <p class="submit"><input type="submit" class="button" name="save" value="<?php _e('save', 'header-footer'); ?>"></p>

    </form>
</div>
