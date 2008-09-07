<?php
/*
Plugin Name: Header-Footer
Plugin URI: http://www.satollo.com/english/wordpress/header-footer
Description: Header-Footer plugin let you to add html code to the header and to the footer.
Version: 1.0.4
Author: Satollo
Author URI: http://www.satollo.com
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008  Satollo  (email : satollo@gmail.com)

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

add_action('admin_head', 'hefo_admin_head');
function hefo_admin_head()
{
    add_options_page('Header-Footer', 'Header-Footer', 'manage_options', 'header-footer/options.php');
}

add_action('wp_head', 'hefo_wp_head');
function hefo_wp_head()
{
    global $hefo_options;
    
    if (is_home()) echo $hefo_options['head_home'];
    echo $hefo_options['head'];
}

add_action('wp_footer', 'hefo_wp_footer');
function hefo_wp_footer()
{
    global $hefo_options;
    
    echo $hefo_options['footer'];
}
?>
