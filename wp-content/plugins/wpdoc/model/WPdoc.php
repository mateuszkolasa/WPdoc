<?php
/**
 * Model pluginu
 *
 * @author Mateusz Kolasa <mateusz.kolasa@polcode.net>
 */
class WPdoc {

    public function init() {
        // Load functions files.
        require_once(WPDOC_PATH . 'inc/functions-post-types.php' );
        if ( is_admin() ) {
        }
    }

    public function listOfProjects() {
        global $wpdb;
        
        $data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wpdoc_projects', 'ARRAY_A');
        
        return $data; 
    }

    public function addProject($name, $description) {
        global $wpdb;

        if($name == null) {
            return 'Nazwa projektu nie może być pusta';
        }

        $wpdb->insert( $wpdb->prefix . 'wpdoc_projects', array(
            'name' => $name,
            'description' => $description
        ), array('%s', '%s'));

        return true;
    }

    public function getProject($name, $description) {
        global $wpdb;

        $wpdb->get( $wpdb->prefix . 'wpdoc_projects', array(
            'name' => $name,
            'description' => $description
        ), array('%s', '%s'));

        return true;
    }
}
