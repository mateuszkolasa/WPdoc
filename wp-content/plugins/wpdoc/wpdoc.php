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
	add_submenu_page('wpdoc_projects', __('Images'), __('Images'), 'edit_themes', 'wpdoc_projects', 'wpdoc_projects');
}







add_action('admin_menu', 'wpdoc_plugin_menu');
register_deactivation_hook(__FILE__, 'wpdoc_uninstall');
register_activation_hook(__FILE__, 'wpdoc_install');








/**
 * Wyswietla formularz do edycji obrazkow
 */
function wpdoc_images() {
	$model=new LogoCarousel();
	if (isset($_POST['rmlc_images'])) {
		$model->deleteAll();
		foreach ($_POST['rmlc_images'] as $img) {
			$model->add(array('image' => $img['image'], 'link' => $img['link'], 'title' => $img['title']));
		}
	}
	$results = $model->getAll();

	echo '<h2>' . __('Images') . '</h2>';
	echo '<form action="?page=rmlc_images" method="post">';
	echo '<table class="form-table" style="width:auto;" cellpadding="10">
        <thead>
        <tr>
        <td>' . __('Image') . '</td><td>' . __('Link') . '</td><td>' . __('Title') . '</td><td>' . __('Delete') . '</td>
        </tr>
        </thead>
        <tbody class="items">';
	$i=0;
	foreach ($results as $row) {
		echo '<tr>
            <td><input name="rmlc_images['.$i.'][image]" type="text" value="' . $row['image'] . '" /></td>';
		echo '<td><input name="rmlc_images['.$i.'][link]" type="text" value="' . $row['link'] . '" /></td>';
		echo '<td><input name="rmlc_images['.$i.'][title]" type="text" value="' . $row['title'] . '" /></td>';
		echo '<td><a class="delete" href="">' . __('Delete') . '</a></td>
            </tr>';
		$i++;
	}
	echo '</tbody><tr><td colspan="4"><a class="add" href="">' . __('Add') . '</a></td></tr>';
	echo '<tr><td colspan="4"><input type="submit" value="' . __('Save') . '" /></td></tr>';
	echo '</table>';
	echo '</form>';
	 
	echo '
        <script type="text/javascript">
        jQuery(document).ready(function($) {
        $("table .delete").click(function() {
        $(this).parent().parent().remove();
        return false;
        });
        $("table .add").click(function() {
        var count = $("tbody.items tr").length+1;
        var code=\'<tr><td><input type="text" name="rmlc_images[\'+count+\'][image]" /></td><td><input type="text" name="rmlc_images[\'+count+\'][link]" /></td><td><input type="text" name="rmlc_images[\'+count+\'][title]" /></td><td><a class="delete" href="">' . __('Delete') . '</a></td></tr>\';
        $("tbody.items").append(code);
        return false;
        });
        });
        </script>
        ';
}









/*
echo plugin_dir_path() . 'wpdoc';
echo '<br>';
echo plugins_url() . 'wpdoc';
*/