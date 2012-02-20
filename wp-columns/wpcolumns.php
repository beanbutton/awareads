<?php
/*
Plugin Name: WP Columns
Plugin URI: http://wordpress.org/extend/plugins/wp-columns/
Description: Easily create one or more columns in your posts or pages. Easy styling with a complete set of generated CSS classes.
Version: 1.1
Author: Semantica Software B.V.
Author URI: http://software.semantica.com/
License: GPL2
*/

/*  Copyright 2010  Semantica Software B.V.  (email : info@semantica.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function wpcolumns_convert_page_content($content) {
	$class = get_option('wpcolumns_column_class_prefix');
	$cbas = get_option('wpcolumns_allow_content_before_after_separator');
	$page_columns = explode(get_option('wpcolumns_column_separator'), $content);
	$columnCount = sizeof($page_columns);
	if($columnCount == 1) {
		return $content;
	}
	
	if($cbas && $columnCount <= 3) {
		return $content;
	}
	
	$content = "";
	$trailing = "";
	if($cbas) {
		$content .= $page_columns[0];
		unset($page_columns[0]);
		$trailing = array_pop($page_columns);
		$page_columns = array_values($page_columns);
		$columnCount = sizeof($page_columns);
	}
			
	$content .= "<div class=\"{$class}-wrapper {$class}-wrapper-{$columnCount}\">";	
	foreach ($page_columns as $index => $page_column) {
		$columnClass = "";
		if($index == 0) {
			$columnClass = " {$class}-first";
		} else if ($index == $columnCount - 1) {
			$columnClass = " {$class}-last";
		} else {
			$columnClass = " {$class}-other";
		}
		
		$columnindex = $index + 1;
		$content .= "<div class=\"{$class} {$class}-$columnindex{$columnClass}\">";
		$content .= trim($page_column);
		$content .= "</div>";
	}	
	$content .= "<div class=\"{$class}-clearfix\"></div></div>";	
	$content .= $trailing;
	
	return $content;
}

add_filter ( 'the_content', 'wpcolumns_convert_page_content', 1);	

// create custom plugin settings menu
add_option('wpcolumns_column_separator', '/---/');
add_option('wpcolumns_column_class_prefix', 'wpcolumn');
add_option('wpcolumns_allow_content_before_after_separator', false);
add_action('admin_menu', 'wpcolumns_create_menu');

function wpcolumns_create_menu() {
	//create new menu
	add_submenu_page('plugins.php', 'WP Columns Settings', 'WP Columns', 'administrator', __FILE__, 'wpcolumns_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_wpcolumns_settings' );
}

function register_wpcolumns_settings() {
	//register our settings
	register_setting( 'wpcolumns-settings-group', 'wpcolumns_column_separator', 'sanitize_wpcolumns_column_separator');
	register_setting( 'wpcolumns-settings-group', 'wpcolumns_column_class_prefix', 'sanitize_wpcolumns_column_class_prefix');
	register_setting( 'wpcolumns-settings-group', 'wpcolumns_allow_content_before_after_separator', 'sanitize_wpcolumns_boolean');
}

function sanitize_wpcolumns_column_separator($input) {
	if(empty($input)) {
		$input = "/---/";
	}
	
	return $input;
}

function sanitize_wpcolumns_column_class_prefix($input) {
	if(empty($input)) {
		$input = "wpcolumn";
	}
	
	return $input;
}

function sanitize_wpcolumns_boolean($input) {
	return $input == '1';
}

function wpcolumns_settings_page() {
?>
<div class="wrap">
<h2>WP Columns Settings</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'wpcolumns-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><label for="wpcolumns_column_separator">Column separator</label></th>
        <td><input type="text" id="wpcolumns_column_separator" name="wpcolumns_column_separator" value="<?php echo get_option('wpcolumns_column_separator'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><label for="wpcolumns_column_class_prefix">CSS Column Class Prefix</label></th>
        <td><input type="text" id="wpcolumns_column_class_prefix" name="wpcolumns_column_class_prefix" value="<?php echo get_option('wpcolumns_column_class_prefix'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"></th>
        <td>
			<input type="checkbox" id="wpcolumns_allow_content_before_after_separator" name="wpcolumns_allow_content_before_after_separator" value="1" <?php echo get_option('wpcolumns_allow_content_before_after_separator') ? ' checked="checked"' : "" ?>/>
			<label for="wpcolumns_allow_content_before_after_separator">Normal content before the first and after the last separator.</label>
			<div style="color: #999;">
			<p>When this is checked content before the first and after the last separator will not be part of the columns.
			<br/><strong>Example:</strong> With the following content, <code>a</code> and <code>d</code> will not be part of the columns when this option is selected and you will end up with two columns for <code>b</code> and <code>c</code></p>
			<pre>
a
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

b
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

c
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

d
			</pre>
			<p>If you have this option enable and you want include <code>a</code>, <code>b</code>, <code>c</code> and <code>d</code> as a column you must use tho following content:
			<pre>
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

a
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

b
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

c
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>

d
<?php echo htmlentities(get_option('wpcolumns_column_separator')); ?>
			</pre>
			</div>
		</td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
</div>
<?php } ?>