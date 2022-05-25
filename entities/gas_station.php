<?php

class Gas_station {

    private $conn;
    private $table_name = "gas_stations";

    public $gas_station_id;
    public $address;
    public $postal_code;
    public $latitude;
    public $longitude;
    public $brand;
    public $region_id;
    public $province;
    public $municipality;
    public $opening_hours;
    public $created;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

}