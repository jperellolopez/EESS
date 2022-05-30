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

    public function checkGasStationExists(){

        $query = "SELECT gas_station_id, address, postal_code, brand, region_id, province, municipality, opening_hours FROM " . $this->table_name . " WHERE gas_station_id = ?";

        $stmt = $this->conn->prepare( $query );

        $this->gas_station_id=htmlspecialchars(strip_tags($this->gas_station_id));

        $stmt->bindParam(1, $this->gas_station_id);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num>0) {

            // la gasolinera ya existe
            return true;
        } else {
            return false;
        }

    }

    public function insertNewGasStation() {

        $this->created=date('Y-m-d H:i:s');

        $query = "INSERT INTO " . $this->table_name . " 
        SET 
        gas_station_id = :gas_station_id,
        address = :address,
        postal_code = :postal_code,
        latitude = :latitude,
        longitude = :longitude,
        brand = :brand,
        region_id = :region_id,
        province = :province,
        municipality = :municipality,
        opening_hours = :opening_hours,
        created = :created";

        $stmt = $this->conn->prepare($query);

        $this->gas_station_id=htmlspecialchars(strip_tags($this->gas_station_id));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->postal_code=htmlspecialchars(strip_tags($this->postal_code));
        $this->latitude=htmlspecialchars(strip_tags($this->latitude));
        $this->longitude=htmlspecialchars(strip_tags($this->longitude));
        $this->brand=htmlspecialchars(strip_tags($this->brand));
        $this->region_id=htmlspecialchars(strip_tags($this->region_id));
        $this->province=htmlspecialchars(strip_tags($this->province));
        $this->municipality=htmlspecialchars(strip_tags($this->municipality));
        $this->opening_hours=htmlspecialchars(strip_tags($this->opening_hours));

        $stmt->bindParam(':gas_station_id', $this->gas_station_id);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':postal_code', $this->postal_code);
        $stmt->bindParam(':latitude', $this->latitude);
        $stmt->bindParam(':longitude', $this->longitude);
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':region_id', $this->region_id);
        $stmt->bindParam(':province', $this->province);
        $stmt->bindParam(':municipality', $this->municipality);
        $stmt->bindParam(':opening_hours', $this->opening_hours);
        $stmt->bindParam(':created', $this->created);

        if ($stmt->execute())  {
            return true;
        } else {
            return false;
        }

    }




}