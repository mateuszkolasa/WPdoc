<?php
/*
 Plugin Name: WPdoc by Mateusz Kolasa
 Version: 0.1
 Description: Plugin adds documentation system to WP
 Author: Mateusz Kolasa
 Author URI: http://www.icymat.pl/
 */
define('WPDOC_PATH', plugin_dir_path( __FILE__ ));
require WPDOC_PATH . 'model/WPdoc.php';
WPdoc::init();

/**
 * Create database
 *
 * @global $wpdb
 */
function wpdoc_install() {
	global $wpdb;
	$prefix = $wpdb->prefix;
	$wpdoc_db_version = "0.2";
	
	//wpdoc_projects
	if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "wpdoc_projects'") != $wpdb->prefix . 'wpdoc_projects') {
		$wpdb->query("CREATE TABLE " . $wpdb->prefix . "wpdoc_projects (
	        id int(9) NOT NULL AUTO_INCREMENT,
	        name varchar(100) NOT NULL,
	        description varchar(255) NULL,
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
	/*add_menu_page('WPdoc', 'WPdoc', 'administrator', 'wpdoc_settings', 'wpdoc_display_settings');
	add_submenu_page('wpdoc_settings', __('New'), __('New'), 'administrator', 'wpdoc_add', 'wpdoc_display_add');
	add_submenu_page('wpdoc_settings', __('New'), __('New'), 'administrator', 'wpdoc_project_edit', 'wpdoc_project_edit');
	remove_menu_page('wpdoc_project_edit');*/
}

function wpdoc_display_settings() {
	$WPdoc = new WPdoc();
	$list = $WPdoc->listOfProjects();
	
    echo '<div class="wrap">';
    echo '<h1>';
    echo 'Projekty ';
    echo '<a class="page-title-action" href="?page=wpdoc_add">Dodaj nowy</a>';
    echo '</h1>';
    
    echo '<table class="wp-list-table widefat" width="100%" cellpadding="10">';
    echo '<thead>';
    echo '  <tr>';
    echo '      <th>Project</th>';
    echo '      <th>Options</th>';
    echo '  </tr>';
    echo '</thead>';
    
    echo '<tbody>';
    foreach($list as $project) {
    	echo '<tr class="inactive">';
    	echo '	<td><a href="?page=wpdoc_project_edit&project=' . $project['id'] . '">' . $project['name'] . '</a></td>';
    	echo '	<td>' . $project['description'] . '</td>';
    	echo '</tr>';
    }
    echo '</tbody>';
    
    echo '</table>';
}

function wpdoc_display_add() {
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$WPdoc = new WPdoc();
		$teges = $WPdoc->addProject($_POST['name'], $_POST['description']);
		
		if($teges === true) {
			echo '';
		}
	}
	
	echo '<div class="wrap"><form action="?page=wpdoc_add" method="post">';
    echo '<h1>';
    echo '<a href="?page=wpdoc_settings">Projekty</a>';
    echo ' &raquo; Nowy projekt';
    echo '</h1>';
    
    echo '<table class="form-table" width="100%" cellpadding="10">';
    echo '  <tr>';
    echo '      <th>Nazwa</th>';
    echo '      <td><input type="text" name="name" cols="30"></td>';
    echo '  </tr>';

    echo '  <tr>';
    echo '      <th>Opis</th>';
    echo '      <td><textarea name="description" cols="30" rows="5"></textarea></td>';
    echo '  </tr>';
    
    echo '</table>';
    
    echo '<input type="submit" value="Utwórz projekt" class="button button-primary" id="submit" name="submit">';
}

function wpdoc_project_edit() {
    $WPdoc = new WPdoc();
    $WPdoc->get($_GET['project']);

    die();

    echo '<div class="wrap">';
    echo '<h1>';
    echo 'Projekt: ' . $project;
    echo '<a class="page-title-action" href="?page=wpdoc_add">Dodaj nowy</a>';
    echo '</h1>';
}

add_action('admin_menu', 'wpdoc_plugin_menu');
register_deactivation_hook(__FILE__, 'wpdoc_uninstall');
register_activation_hook(__FILE__, 'wpdoc_install');
