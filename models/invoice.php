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




    // used for paging invoices - count all invoices
    public function countAllInvoices(){

    $query = "SELECT invoice_id FROM invoices where user_id = :user_id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':user_id', $this->user_id);

    // execute query
    $stmt->execute();

    // get number of rows
    $num = $stmt->rowCount();

    // return row count
     return $num;
    }

}