<?php
/*
 Plugin Name: WPdoc by Mateusz Kolasa
 Version: 0.1
 Description: Plugin adds documentation system to WP
 Author: Mateusz Kolasa
 Author URI: http://www.icymat.pl/
 */

/**
 * Create database
 *
 * @global $wpdb
 */
function wpdoc_install() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$wpdoc_db_version = "0.1";
	
	//wpdoc_projects
	if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "wpdoc_projects'") != $wpdb->prefix . 'wpdoc_projects') {
		$wpdb->query("CREATE TABLE " . $wpdb->prefix . "wpdoc_projects (
	        id int(9) NOT NULL AUTO_INCREMENT,
	        name varchar(100) NOT NULL,
	        PRIMARY KEY(id)
        )");
	}
	
	//wpdoc_versions
	if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "wpdoc_versions'") != $wpdb->prefix . 'wpdoc_versions') {
		$wpdb->query("CREATE TABLE " . $wpdb->prefix . "wpdoc_versions (
	        id int(9) NOT NULL AUTO_INCREMENT,
	        project_id int NOT NULL,
			version varchar(20) NOT NULL,
	        PRIMARY KEY(id)
        )");
	}
	
	//wpdoc_pages
	if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "wpdoc_pages'") != $wpdb->prefix . 'wpdoc_pages') {
		$wpdb->query("CREATE TABLE " . $wpdb->prefix . "wpdoc_pages (
	        id int(9) NOT NULL AUTO_INCREMENT,
	        project_id int NOT NULL,
			version varchar(20) NOT NULL,
	        title varchar(150) NOT NULL,
			content text NULL,
	        PRIMARY KEY(id)
        )");
	}
	
	add_option('wpdoc_db_version', $wpdoc_db_version);
	//add_option( 'rmlc_speed', '2000' );
	//add_option( 'rmlc_interval', '2000');
	//add_option( 'rmlc_type', 'vertical');
}

/**
 * Usuwa tabele bazy danych
 * @global $wpdb $wpdb
 */
function wpdoc_uninstall() {
	global $wpdb;
	
	foreach(array('wpdoc_projects', 'wpdoc_versions', 'wpdoc_pages') as $table) {
		$wpdb->query('DROP TABLE '.$wpdb->prefix . $table);
	}
}

/**
* Dodaje Logo carousel do menu w PA
*/
function wpdoc_plugin_menu() {
	add_menu_page('WPdoc', 'WPdoc', 'administrator', 'wpdoc_settings', 'wpdoc_display_settings');
	//add_submenu_page('wpdoc_projects', __('Images'), __('Images'), 'edit_themes', 'wpdoc_projects', 'wpdoc_projects');
}

function wpdoc_settings() {
    echo 123;
}

function wpdoc_display_settings() {
    echo '<div class="wrap"><form action="options.php" method="post" name="options">';
    echo '<h2>Select Your Settings</h2>';
    //echo wp_nonce_field('update-options');
    echo '<table class="form-table" width="100%" cellpadding="10">';
    echo '  <tr>';
    echo '      <th>Projekt</th>';
    echo '  </tr>';
    echo '</table>';
    
    echo '</table>';
    echo '<input type="hidden" name="action" value="update'.__('Update').'" />';
    echo ''; 
    echo '<input type="hidden" name="page_options" value="rmlc_speed,rmlc_type,rmlc_interval" />';
    echo ''; 
    echo '<input type="submit" name="Submit" value="Update" /></form></div>';
}

add_action('admin_menu', 'wpdoc_plugin_menu');
register_deactivation_hook(__FILE__, 'wpdoc_uninstall');
register_activation_hook(__FILE__, 'wpdoc_install');
