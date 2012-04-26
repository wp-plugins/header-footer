<?php
if (function_exists('load_plugin_textdomain')) {
    load_plugin_textdomain('header-footer', false, 'header-footer/languages');
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
jQuery.cookie = function(name, value, options) {
  if (typeof value != 'undefined') { // name and value given, set cookie
    options = options || {};
    if (value === null) {
      value = '';
      options.expires = -1;
    }
    var expires = '';
    if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
      var date;
      if (typeof options.expires == 'number') {
        date = new Date();
        date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
      } else {
        date = options.expires;
      }
      expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
    }
    // CAUTION: Needed to parenthesize options.path and options.domain
    // in the following expressions, otherwise they evaluate to undefined
    // in the packed version for some reason...
    var path = options.path ? '; path=' + (options.path) : '';
    var domain = options.domain ? '; domain=' + (options.domain) : '';
    var secure = options.secure ? '; secure' : '';
    document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
  } else { // only name given, get cookie
    var cookieValue = null;
    if (document.cookie && document.cookie != '') {
      var cookies = document.cookie.split(';');
      for (var i = 0; i < cookies.length; i++) {
        var cookie = jQuery.trim(cookies[i]);
        // Does this cookie string begin with the name we want?
        if (cookie.substring(0, name.length + 1) == (name + '=')) {
          cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
          break;
        }
      }
    }
    return cookieValue;
  }
};  
  
var hefo_tabs;
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
        jQuery("#tabs").tabs();
    }

    jQuery("#tabs").tabs({
          cookie: {
            expires: 30
          }
        });
});
</script>
<div class="wrap">
    <h2>Header and Footer</h2>

    <iframe src="http://frames.satollo.net/header-footer.php" frameborder="0" width="100%" height="80" style="border: 0"></iframe>

    <p><?php _e('Detailed documentation and FAQs can be found online on <a href="http://www.satollo.net/plugins/header-footer"><strong>Header and Footer plugin official page</strong></a>.'); ?></p>
    <p><?php _e('PHP is allowed on textareas below.'); ?> <?php _e('If you use bbPress, read the official page.'); ?></p>
    
    <form method="post" action="">
        <?php wp_nonce_field('save') ?>
   
    <div id="tabs">
    <ul>
        <li><a href="#tabs-1"><?php _e('Page head and footer', 'header-footer'); ?></a></li>
        <li><a href="#tabs-2"><?php _e('Post content', 'header-footer'); ?></a></li>
        <li><a href="#tabs-3"><?php _e('Page content', 'header-footer'); ?></a></li>
        <li><a href="#tabs-4"><?php _e('Facebook', 'header-footer'); ?></a></li>
        <li><a href="#tabs-5"><?php _e('Snippets', 'header-footer'); ?></a></li>
        <li><a href="#tabs-6"><?php _e('BBPress', 'header-footer'); ?></a></li>
        <!--<li><a href="#tabs-8"><?php _e('Advanced', 'header-footer'); ?></a></li>-->
        <li><a href="#tabs-7"><?php _e('Notes and...', 'header-footer'); ?></a></li>
    </ul>
       
        <div id="tabs-1">
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('head_home', __('Code to be added on HEAD section of the home', 'header-footer'), '', 'rows="4"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('head', __('Code to be added on HEAD section of every page', 'header-footer'), 'It will be added on HEAD section of the home as well', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('footer', __('Code to be added before the end of the page', 'header-footer'), 'It works if your theme has the wp_footer call. It should be just before the &lt;/body&gt; closing tag', 'rows="10"'); ?></tr>
        </table>
        </div>
        


        <div id="tabs-2">
        <!--<h3>Posts and pages</h3>-->
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('before', __('Code to be inserted before each post', 'header-footer'), '', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('after', __('Code to be inserted after each post', 'header-footer'), '', 'rows="10"'); ?></tr>
        </table>
        </div>
        
        <div id="tabs-3">
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('page_before', __('Code to be inserted before each page', 'header-footer'), '', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('page_after', __('Code to be inserted after each page', 'header-footer'), '', 'rows="10"'); ?></tr>
        </table>
        </div>
        
        <div id="tabs-4">
        <!--<h3>Facebook</h3>-->
        <table class="form-table">
            <tr valign="top"><?php hefo_field_text('og_type', __('Facebook page type for the generic web page', 'header-footer'), __('Usually "article" is the right choice, if empty will be skipped', 'header-footer')); ?></tr>
            <tr valign="top"><?php hefo_field_text('og_type_home', __('Facebook page type for the home', 'header-footer'), __('Usually "blog" is a good choice, if empty will be used the generic type', 'header-footer')); ?></tr>
            <tr valign="top"><?php hefo_field_checkbox('og_image', __('Facebook Open Graph Image', 'header-footer'), __('Adds the Facebook Open Graph metatag with a reference to the first post image', 'header-footer')); ?></tr>
            <tr valign="top">
                <th scope="row">
                    <label for="options[' . $name . ']"><?php _e('Facebook Open Graph default image'); ?></label></th>
                    <td>
                        <input type="text" id="og_image_default" name="options[og_image_default]" value="<?php echo htmlspecialchars($options['og_image_default']); ?>" size="50"/>
                        <input type="button" id="upload-image" value="Select/Upload an image"/>
                        <br />
                        <?php _e('If no image can be extracted from a post, that image URL will be used (if present).'); ?><br />
                        <?php _e('<strong>Warning.</strong> On some versions of WordPress after the image selection button is pressed the tabs above does not change anymore. Just save so
                        this page is reloaded (<a href="http://wordpress.org/support/topic/wp-32-thickbox-jquery-ui-tabs-conflict" target="_blank">reference</a>).'); ?>
                    </td>
            </tr>
        </table>
        <!--
        <table class="form-table">
            <tr valign="top"><?php hefo_field_checkbox('fb_sdk', __('Facebook async SDK', 'header-footer'), __('Adds the Facebook SDK', 'header-footer')); ?></tr>
            <tr valign="top"><?php hefo_field_text('fb_app_id', __('Facebook app id', 'header-footer'), __('Optional', 'header-footer')); ?></tr>
        </table>
        -->
        </div>

        <div id="tabs-5">
        <p>
            <?php _e('Common snippets that can be used in any header or footer area referring them as [snippet_N] where N is the snippet number
            from 1 to 5. Snippets are inserted before PHP evaluation.', 'header-footer'); ?><br />
            <?php _e('Useful for social button to be placed before and after the post or in posts and pages.', 'header-footer'); ?>
        </p>
        <table class="form-table">
            <? for ($i=1; $i<=5; $i++) { ?>
            <tr valign="top"><?php hefo_field_textarea('snippet_' . $i, __('Snippet ' . $i, 'header-footer'), '', 'rows="10"'); ?></tr>
            <? } ?>
        </table>
        </div>        

        <div id="tabs-6">
        <p>
            Injection points on bbPress default theme structure are not always clear to me, so consider this feature experimental.
        </p>
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('bbp_theme_before_reply_content', __('Before reply content', 'header-footer'), 'Hook: bbp_theme_before_reply_content', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('bbp_theme_after_reply_content', __('After reply content', 'header-footer'), 'Hook: bbp_theme_after_reply_content', 'rows="10"'); ?></tr>
            <tr valign="top"><?php hefo_field_textarea('bbp_template_before_single_topic', __('Before single topic', 'header-footer'), 'Hook: bbp_template_before_single_topic', 'rows="10"'); ?></tr>
        </table>
            
        </div>  
      
        <!--
        <div id="tabs-8">
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('init', __('PHP code to be executed on plugin init', 'header-footer'), '', 'rows="10"'); ?></tr>
        </table>
        </div>        
        -->
        
        <div id="tabs-7">
        <table class="form-table">
            <tr valign="top"><?php hefo_field_textarea('notes', __('Notes and parked codes', 'header-footer'), '', 'rows="10"'); ?></tr>
        </table>
        </div>        
    </div>
    <p class="submit"><input type="submit" class="button" name="save" value="<?php _e('save', 'header-footer'); ?>"></p>

    </form>
</div>


      