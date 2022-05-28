<?php

class Region {

    private $conn;
    private $table_name = "regions";

    public $region_id;
    public $region;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

}