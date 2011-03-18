<?php

/*
  Plugin Name: Header and Footer
  Plugin URI: http://www.satollo.net/plugins/header-footer
  Description: Header and Footer plugin let you to add html/javascript code to the head and footer of blog pages. Goto to the admin panel to have more information or give a look to the <a href="http://www.satollo.net/plugins/herader-footer">official page</a>.
  Version: 1.1.5
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

add_action('admin_menu', 'hefo_admin_menu');

function hefo_admin_menu() {
    add_options_page('Header and Footer', 'Header and Footer', 'manage_options', 'header-footer/options.php');
}

add_action('wp_head', 'hefo_wp_head', 1);

function hefo_wp_head() {
    global $hefo_options, $wp_query;
    // Add it as higer as possible, Facebook reads only the first part of a page
    if ($hefo_options['og_image']) {
        if (is_single() || is_page()) {
            $xid = $wp_query->get_queried_object_id();
            $xattachments = get_children(array('post_parent' => $xid, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order'));
            if (!empty($xattachments)) {
                foreach ($xattachments as $id => $attachment) {
                    $ximage = wp_get_attachment_image_src($id, 'thumbnail');
                    echo '<meta property="og:image" content="' . $ximage[0] . '" />';
                }
            }
            else {
                echo '<meta property="og:image" content="' . $hefo_options['og_image_default'] . '" />';
            }
        }
        else {
            echo '<meta property="og:image" content="' . $hefo_options['og_image_default'] . '" />';
        }
    }


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

?>
