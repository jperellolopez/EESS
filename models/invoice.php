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
    public $refuel_date;
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

    public function insertNewInvoice() {

        $this->created=date('Y-m-d H:i:s');

        $query = "INSERT INTO
                " . $this->table_name . "
            SET 
            gas_station_id = :gas_station_id,
            user_id = :user_id,
            fuel_type = :fuel_type,
            fuel_price = :fuel_price,
            money_spent = :money_spent,
            refuel_date = :refuel_date,
            created = :created";

        $stmt = $this->conn->prepare($query);

        $this->gas_station_id=htmlspecialchars(strip_tags($this->gas_station_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->fuel_type=htmlspecialchars(strip_tags($this->fuel_type));
        $this->fuel_price=htmlspecialchars(strip_tags($this->fuel_price));
        $this->money_spent=htmlspecialchars(strip_tags($this->money_spent));
        $this->refuel_date=htmlspecialchars(strip_tags($this->refuel_date));

        $stmt->bindParam('gas_station_id', $this->gas_station_id);
        $stmt->bindParam('user_id', $this->user_id);
        $stmt->bindParam('fuel_type', $this->fuel_type);
        $stmt->bindParam('fuel_price', $this->fuel_price);
        $stmt->bindParam('money_spent', $this->money_spent);
        $stmt->bindParam('refuel_date', $this->refuel_date);
        $stmt->bindParam('created', $this->created);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function countUserInvoices($from_record_num, $records_per_page){
        $query = "SELECT i.invoice_id, gs.brand, gs.address, gs.municipality, i.refuel_date  FROM gas_stations as gs inner join invoices as i on i.gas_station_id = gs.gas_station_id WHERE user_id = ? ORDER BY i.invoice_id DESC LIMIT ?, ?;";

        $stmt = $this->conn->prepare($query);

        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(1, $this->user_id);

        $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

}