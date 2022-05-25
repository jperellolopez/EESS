<?php

class Invoice {
    private $conn;
    private $table_name = "invoices";

    public $invoice_id;
    public $gas_station_id;
    public $user_id;
    public $fuel_type;
    public $fuel_price;
    public $money_spent;
    public $created;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

}