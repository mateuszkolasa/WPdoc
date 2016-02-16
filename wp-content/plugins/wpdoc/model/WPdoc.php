<?php
/**
 * Model pluginu
 *
 * @author Mateusz Kolasa <mateusz.kolasa@polcode.net>
 */
class WPdoc {
 
    private $tableName;
    private $wpdb;
 
    public function __construct() {
        global $wpdb;
        /*$prefix = $wpdb->prefix;
        $this->tableName = $prefix . "rm_logo_carousel";
        $this->wpdb = $wpdb;*/
    }
 
    /*
     * Pobiera wszystkie obrazki
     * @global $wpdb $wpdb
     * @return array Tablica z obrazkami
     *
    public function getAll() {
        $query = "SELECT * FROM  " . $this->tableName . " ORDER BY id DESC;";
        return $this->wpdb->get_results($query, ARRAY_A);
    }
    
    /*
     * Dodaje obrazki
     * @global $wpdb $wpdb
     * @param array $data
     *
    public function add($data) {
        $this->wpdb->insert($this->tableName, $data, array('%s', '%s', '%s'));
    }
 
    /**
     * Usuwa wszystkie obrazki
     * @global $wpdb $wpdb
     *
    public function deleteAll() {
        $sql = "TRUNCATE TABLE " . $this->tableName;
        $this->wpdb->query($sql);
    }
 	*/
}
