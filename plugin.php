<?php

/*
  Plugin Name: Header and Footer
  Plugin URI: http://www.satollo.net/plugins/header-footer
  Description: Header and Footer by Satollo.net lets to add html/javascript code to the head and footer of your blog. Some explaes are provided on the <a href="http://www.satollo.net/plugins/herader-footer">official page</a>.
  Version: 1.3.4
  Author: Satollo
  Author URI: http://www.satollo.net
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

/*
  Copyright 2008-2010  Satollo  (email : info@satollo.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$hefo_options = get_option('hefo');

add_action('admin_init', 'hefo_admin_init');
function hefo_admin_init() {
    if (strpos($_GET['page'], 'header-footer/') === 0) {
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }
}

add_action('admin_head', 'hefo_admin_head');
function hefo_admin_head()
{
    if (strpos($_GET['page'], 'header-footer/') === 0) {
        echo '<link type="text/css" rel="stylesheet" href="' .
        get_option('siteurl') . '/wp-content/plugins/header-footer/jquery-ui.css"/>';
        echo '<link type="text/css" rel="stylesheet" href="' .
        get_option('siteurl') . '/wp-content/plugins/header-footer/style.css"/>';
    }

}

add_action('admin_menu', 'hefo_admin_menu');

function hefo_admin_menu() {
    add_options_page('Header and Footer', 'Header and Footer', 'manage_options', 'header-footer/options.php');
}

add_action('wp_head', 'hefo_wp_head_pre', 1);
function hefo_wp_head_pre() {
    global $hefo_options, $wp_query;
    
    if (is_home()) {
        if (empty($hefo_options['og_type_home'])) $hefo_options['og_type_home'] = $hefo_options['og_type'];
        if (!empty($hefo_options['og_type_home'])) echo '<meta property="og:type" content="' . $hefo_options['og_type_home'] . '" />';
    }
    else {
        if (!empty($hefo_options['og_type'])) echo '<meta property="og:type" content="' . $hefo_options['og_type'] . '" />';
    }
    
    // Add it as higer as possible, Facebook reads only the first part of a page
    if (isset($hefo_options['og_image'])) {
        if (is_single() || is_page()) {
            $xid = $wp_query->get_queried_object_id();
            if (function_exists('bbp_get_topic_forum_id')) {
                $object = $wp_query->get_queried_object();
                if ($object != null && $object->post_type == 'topic') {
                    $xid = bbp_get_topic_forum_id($xid);
                }
            }
            $xtid = function_exists('get_post_thumbnail_id')?get_post_thumbnail_id($xid):false;
            if ($xtid) {
                $ximage = wp_get_attachment_image_src($xtid, 'thumbnail');
                echo '<meta property="og:image" content="' . $ximage[0] . '" />';
            }
            else {
                $xattachments = get_children(array('post_parent' => $xid, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order'));
                if (!empty($xattachments)) {
                    foreach ($xattachments as $id => $attachment) {
                        $ximage = wp_get_attachment_image_src($id, 'thumbnail');
                        echo '<meta property="og:image" content="' . $ximage[0] . '" />';
                        break;
                    }
                }
                else {
                    if (!empty($hefo_options['og_image_default'])) {
                        echo '<meta property="og:image" content="' . $hefo_options['og_image_default'] . '" />';
                    }
                }
            }
        }
        else {
            if (!empty($hefo_options['og_image_default'])) {
                echo '<meta property="og:image" content="' . $hefo_options['og_image_default'] . '" />';
            }
        }
    }
}

add_action('wp_head', 'hefo_wp_head_post', 11);

function hefo_wp_head_post() {
    global $hefo_options;
    $buffer = '';
    if (is_home ()) $buffer .= $hefo_options['head_home'];

    $buffer .= $hefo_options['head'];

    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer;
}

add_action('wp_footer', 'hefo_wp_footer');

function hefo_wp_footer() {
    global $hefo_options;

    $buffer = $hefo_options['footer'];

    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $buffer;
}

add_action('the_content', 'hefo_the_content');

function hefo_the_content($content) {
    global $hefo_options;

    if (!is_singular()) return $content;

    if (is_page()) {
        $before = hefo_execute($hefo_options['page_before']);
        $after = hefo_execute($hefo_options['page_after']);
    }
    else {
        $before = hefo_execute($hefo_options['before']);
        $after = hefo_execute($hefo_options['after']);
    }
    return $before . $content . $after;
}

function hefo_execute($buffer) {
    ob_start();
    eval('?>' . $buffer);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}
?>
