<?php

// PHP Approach
error_reporting(E_ALL);

// Global Info
$GLOBALS['fso_gamename'] = "FutureSpace Online ";
$GLOBALS['fso_version'] = " v1.1001";
$GLOBALS['page_title'] = $GLOBALS['fso_gamename'] . $GLOBALS['fso_version'];
$GLOBALS['url_path'] = "http://localhost/futurespace/";
$GLOBALS['protectiontime'] = "86400";
$GLOBALS['mapsize'] = "3000";
$GLOBALS['tilesize'] = "50";
$GLOBALS['asteroid_spawns'] = "10";
$GLOBALS['rat_spawns'] = "5";
$GLOBALS['salvagesite_spawns'] = "5";

class Connection {

    private $_DBSERVER = "localhost";
    private $_DBUSER = "root";
    private $_DBPASSWORD = "root";
    private $_DBTABLE = "futurespace";
    private $_connection;

    public function __construct() {
        $this->_connection = mysqli_connect($this->_DBSERVER, $this->_DBUSER, $this->_DBPASSWORD, $this->_DBTABLE);
    }

    public function fetchInsertId() {
        return mysqli_insert_id($this->_connection);
    }

    public function selectQuery($query) {
        return mysqli_query($this->_connection, $query);
    }

    public function fetchRow($query) {
        return mysqli_fetch_row($query);
    }

    public function fetchNum($query) {
        return mysqli_num_rows($query);
    }

    public function fetchAssoc($query) {
        return mysqli_fetch_assoc($query);
    }

    public function fetchArray($query) {
        return mysqli_fetch_array($query);
    }

    function runQuery($query) {
        $result = mysqli_query($this->_connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        if (!empty($resultset))
            return $resultset;
    }

    public function closeConnection() {
        return mysqli_close($this->_connection);
    }

    public function Error() {
        return "<div style=\"position:fixed;z-index:9999;width:100%;height:100%;color:black;background:green;\">" . mysqli_errno($this->_connection) . ": " . mysqli_error($this->_connection) . "</div>";
    }

}
